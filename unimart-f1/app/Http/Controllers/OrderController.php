<?php

namespace App\Http\Controllers;

use App\Customer;
use App\District;
use App\Mail\check_order;
use App\Menu;
use App\Order;
use App\Order_coupon;
use App\Order_detail;
use App\Payment_VNpay;
use App\Product;
use App\Product_cat;
use App\Province;
use App\Slider;
use App\Ward;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Gloudemans\Shoppingcart\Facades\Cart;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    //
    public $order_statuses = [
        0 => 'Đang xử lý', // may phan nay can chuyen sang tieng anh de dong nhat voi trang nguoi dung 
        1 => 'Đang giao hàng',
        2 => 'Thành công',
        3 => 'Hủy'
    ];
    public $payment = [
        0 => 'Payment on delivery', //

        1 => 'Payment by PayPal',
        2 => 'Payment by VNpay',
    ];
    public $fee_shipping_default = 10;
    function sendmail(Request $request)
    {
        $id = $request->session()->get('order_id');
        $payment = $this->payment;
        $order_statuses = $this->order_statuses;
        $order_detail = Order_detail::where('order_id', '=', $id)->get();

        $order = Order::find($id);
        //return $order_detail;
        $customer = Customer::where('id', '=', $order->customer_id)->first();
        $data = [
            'payment' => $payment,
            'order_statuses' => $order_statuses,
            'order_detail' => $order_detail,
            'order' => $order,
            'customer' => $customer

        ];
        Mail::to($customer->email)->send(new check_order($data));
        // chỗ này có thể đẻe là mảng các email (nghĩa là gửi đi cho tất cả các email có trong đó)
        Cart::destroy();
        session()->pull('id');
        session()->pull('user_id');
        session()->pull('event_id');
        session()->pull('code');
        session()->pull('qty');
        session()->pull('value');
        session()->pull('condition');
        session()->pull('fee_shipping_value');
        session()->pull('fee_shipping_id');
        session()->put('success_checkout', 'success');

        return redirect('order/purchase_order')->with('status', 'Đơn hàng của bạn đã được đặt thành công, vui lòng kiểm tra địa chỉ email!');
    }
    function add(Request $request)
    {


        //return $request->session()->get('final_total');
        $request->validate(
            [

                'phone_number' => 'required|string',
                'city' => 'required|string',
                'email' => 'required|string',
                'name' => 'required|string',
                'district' => 'required|string',
                'ward_street' => 'required|string',

            ],
            [
                'required' => ":attribute cannot be empty",
                'max' => ':attribute up to :max characters in length',
            ],
            [
                'address' => 'Address',
                'name' => 'Name',
                'city' => 'Province',
                'disrict' => 'District',
                'ward_street' => 'Ward',
                'email' => 'Email',
                'phone_number' => 'Phone number'
            ]
        );

        $province_id = $request->city;
        $district_id = $request->district;
        $ward_id = $request->ward_street;
        $province = Province::where('id', $province_id)->first();
        $district = District::where('id', $district_id)->first();
        $ward = Ward::where('id', $ward_id)->first();

        if ($request->payment == 2 || $request->payment == 3) { // thanht toan VNpay
            $data_VNpay = array();
            $data_VNpay['name'] = $request->name;
            $data_VNpay['email'] = $request->email;
            $data_VNpay['phone_number'] = $request->phone_number;
            $data_VNpay['province'] = $province->name;
            $data_VNpay['district'] = $district->name;
            $data_VNpay['ward'] = $ward->name;
            $data_VNpay['total_price'] = $request->total_price;
            $total_price = $request->session()->get('final_total');
            $request->session()->put('data_VNpay', $data_VNpay);
            if ($request->payment == 2) {
                $menus = Menu::where('parent_id', 0)->get();
                return view('cart.VNpay.index', compact('total_price', 'menus'));
            } else if ($request->payment == 3) {
                return redirect()->route('processTransaction');
            }
        }



        $address = $ward->name . ", " . $district->name . ", " . $province->name;

        $now = Carbon::now()->toDateString();
        $order = Order::create([

            'code_bill' => "E#" . Str::random(5),
            'customer_id' => Auth::id(),
            'total' => Cart::total(),
            'payment' => $request->payment,
            'status' => 0,
            'order_coupon_id' => $request->session()->get('id'),
            'fee_shipping_id' => $request->session()->get('fee_shipping_id'),
            'final_total' =>  $request->session()->get('final_total'),
            'address' => $address,
            'phone_number' => $request->phone_number,
            'name' => $request->name,
            'email' => $request->email,
            'date_order' => $now,

        ]);
        $order_coupon = Order_coupon::where('id', $request->session()->get('id'))->first();
        if ($request->session()->has('event_id')) {
            $order_coupon->update([
                'coupon_used' => $order_coupon->coupon_used . "," . Auth::id(),
            ]);
        }


        // Customer::where('id',Auth::id())->update([
        //     'phone_number'=>$request->phone_number,
        //     'address'=>$request->address

        // ]);
        foreach (Cart::content() as $item) {
            $product_find = Product::find($item->id);
            $price_cost = $product_find->price_cost;
            Order_detail::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'price' => $item->price,
                'qty' => $item->qty,
                'price_cost' => $price_cost,
            ]);
            $product = Product::where('id', $item->id)->first();
            $qty_tmp = $product->qty - $item->qty;
            Product::where('id', $item->id)->update([
                'qty' => $qty_tmp,
            ]);
        };
        $request->session()->put('order_id', $order->id);
        return redirect('sendmail/check_order');
    }

    function payment_method_VNpay(Request $request)
    {
        // error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        // date_default_timezone_set('Asia/Ho_Chi_Minh');

        $vnp_Url = env('VNP_URL');

        $vnp_Returnurl =   config('app.base_url_f') . 'order/payment_method/VNpay/return';
        $vnp_TmnCode = env('VNP_TMN_CODE'); //Mã website tại VNPAY 
        $vnp_HashSecret = env('VNP_HASHSECRET'); //Chuỗi bí mật

        $vnp_TxnRef = "E#" . Str::random(5); //Mã đơn hàng. Trong thực tế Merchant cần insert đơn hàng vào DB và gửi mã này sang VNPAY
        $vnp_OrderInfo = $request->OrderDescription;
        $vnp_OrderType = $request->ordertype;
        $vnp_Amount =  ($request->Amount) * 100; // từ đô đổi ra tiền việt
        $vnp_Locale = $request->language;
        $vnp_BankCode = $request->bankcode;
        $vnp_IpAddr = $_SERVER['REMOTE_ADDR'];


        //Add Params of 2.0.1 Version
        //$vnp_ExpireDate = $_POST['txtexpire'];
        //Billing
        // $vnp_Bill_Mobile = $_POST['txt_billing_mobile'];
        // $vnp_Bill_Email = $_POST['txt_billing_email'];
        // $fullName = trim($_POST['txt_billing_fullname']);
        // if (isset($fullName) && trim($fullName) != '') {
        //     $name = explode(' ', $fullName);
        //     $vnp_Bill_FirstName = array_shift($name);
        //     $vnp_Bill_LastName = array_pop($name);
        // }
        // $vnp_Bill_Address = $_POST['txt_inv_addr1'];
        // $vnp_Bill_City = $_POST['txt_bill_city'];
        // $vnp_Bill_Country = $_POST['txt_bill_country'];
        // $vnp_Bill_State = $_POST['txt_bill_state'];
        // // Invoice
        // $vnp_Inv_Phone = $_POST['txt_inv_mobile'];
        // $vnp_Inv_Email = $_POST['txt_inv_email'];
        // $vnp_Inv_Customer = $_POST['txt_inv_customer'];
        // $vnp_Inv_Address = $_POST['txt_inv_addr1'];
        // $vnp_Inv_Company = $_POST['txt_inv_company'];
        // $vnp_Inv_Taxcode = $_POST['txt_inv_taxcode'];
        // $vnp_Inv_Type = $_POST['cbo_inv_type'];
        $inputData = array(
            "vnp_Version" => "2.1.0",
            "vnp_TmnCode" => $vnp_TmnCode,
            "vnp_Amount" => $vnp_Amount,
            "vnp_Command" => "pay",
            "vnp_CreateDate" => date('YmdHis'),
            "vnp_CurrCode" => "VND",
            "vnp_IpAddr" => $vnp_IpAddr,
            "vnp_Locale" => $vnp_Locale,
            "vnp_OrderInfo" => $vnp_OrderInfo,
            "vnp_OrderType" => $vnp_OrderType,
            "vnp_ReturnUrl" => $vnp_Returnurl,
            "vnp_TxnRef" => $vnp_TxnRef,
            // "vnp_ExpireDate" => $vnp_ExpireDate,
            // "vnp_Bill_Mobile" => $vnp_Bill_Mobile,
            // "vnp_Bill_Email" => $vnp_Bill_Email,
            // "vnp_Bill_FirstName" => $vnp_Bill_FirstName,
            // "vnp_Bill_LastName" => $vnp_Bill_LastName,
            // "vnp_Bill_Address" => $vnp_Bill_Address,
            // "vnp_Bill_City" => $vnp_Bill_City,
            // "vnp_Bill_Country" => $vnp_Bill_Country,
            // "vnp_Inv_Phone" => $vnp_Inv_Phone,
            // "vnp_Inv_Email" => $vnp_Inv_Email,
            // "vnp_Inv_Customer" => $vnp_Inv_Customer,
            // "vnp_Inv_Address" => $vnp_Inv_Address,
            // "vnp_Inv_Company" => $vnp_Inv_Company,
            // "vnp_Inv_Taxcode" => $vnp_Inv_Taxcode,
            // "vnp_Inv_Type" => $vnp_Inv_Type
        );

        if (isset($vnp_BankCode) && $vnp_BankCode != "") {
            $inputData['vnp_BankCode'] = $vnp_BankCode;
        }
        if (isset($vnp_Bill_State) && $vnp_Bill_State != "") {
            $inputData['vnp_Bill_State'] = $vnp_Bill_State;
        }

        //var_dump($inputData);
        ksort($inputData);
        $query = "";
        $i = 0;
        $hashdata = "";
        foreach ($inputData as $key => $value) {
            if ($i == 1) {
                $hashdata .= '&' . urlencode($key) . "=" . urlencode($value);
            } else {
                $hashdata .= urlencode($key) . "=" . urlencode($value);
                $i = 1;
            }
            $query .= urlencode($key) . "=" . urlencode($value) . '&';
        }

        $vnp_Url = $vnp_Url . "?" . $query;
        if (isset($vnp_HashSecret)) {
            $vnpSecureHash =   hash_hmac('sha512', $hashdata, $vnp_HashSecret); //  
            $vnp_Url .= 'vnp_SecureHash=' . $vnpSecureHash;
        }
        $returnData = array(
            'code' => '00', 'message' => 'success', 'data' => $vnp_Url
        );
        if (isset($_POST['payment_on_vnpay'])) {
            header('Location: ' . $vnp_Url);
            die();
        } else {
            echo json_encode($returnData);
        }
    }

    function payment_method_VNpay_return(Request $request)
    {

        $data_VNpay = $request->session()->get('data_VNpay');

        $address = $data_VNpay['ward'] . ", " . $data_VNpay['district'] . ", " . $data_VNpay['province'];

        $now = Carbon::now()->toDateString();
        $customer_id = Auth::id();
        $order = Order::create([

            'code_bill' => $request->vnp_TxnRef,
            'customer_id' => $customer_id,
            'total' => Cart::total(),
            'payment' => 2,
            'status' => 0,
            'order_coupon_id' => $request->session()->get('id'),
            'fee_shipping_id' => $request->session()->get('fee_shipping_id'),
            'final_total' =>  $request->session()->get('final_total'),
            'address' => $address,
            'phone_number' => $data_VNpay['phone_number'],
            'name' => $data_VNpay['name'],
            'email' => $data_VNpay['email'],
            'date_order' => $now,

        ]);
        $order_coupon = Order_coupon::where('id', $request->session()->get('id'))->first();
        if ($request->session()->has('event_id')) {
            $order_coupon->update([
                'coupon_used' => $order_coupon->coupon_used . "," . Auth::id(),
            ]);
        }


        // Customer::where('id',Auth::id())->update([
        //     'phone_number'=>$request->phone_number,
        //     'address'=>$request->address

        // ]);
        foreach (Cart::content() as $item) {
            $product_find = Product::find($item->id);
            $price_cost = $product_find->price_cost;
            Order_detail::create([
                'order_id' => $order->id,
                'product_id' => $item->id,
                'price' => $item->price,
                'qty' => $item->qty,
                'price_cost' => $price_cost,
            ]);
            $product = Product::where('id', $item->id)->first();
            $qty_tmp = $product->qty - $item->qty;
            Product::where('id', $item->id)->update([
                'qty' => $qty_tmp,
            ]);
        };
        $request->session()->put('order_id', $order->id);

        Payment_VNpay::create([
            'order_id' => $order->id,
            'customer_id' => $customer_id,
            'money' => Cart::total(),
            'order_code' => $request->vnp_TxnRef,
            'note' => $request->vnp_OrderInfo,
            'vnp_response_code' => $request->vnp_ResponseCode,
            'code_vnpay' => $request->vnp_TransactionNo,
            'code_bank' => $request->vnp_BankCode,
            'transfer_time' => $now,

        ]);

        return redirect('sendmail/check_order');
    }

    function purchase_order()
    {
        $sliders = Slider::orderBy('position')->where('status', 1)->where('deleted_at', null)->get();
        $product_cats = Product_cat::where('parent_id', 0)->where('deleted_at', '=', null)->orderby('position')->get();
        $menus = Menu::where('parent_id', 0)->get();
        $purchase_order = Order::where('customer_id', Auth::id())->orderBy('created_at', 'DESC')->paginate(12);
        $order_statuses = $this->order_statuses;
        return view('cart.purchase_order', compact('sliders', 'product_cats', 'menus', 'purchase_order', 'order_statuses'));
    }
    function order_detail(Request $request)
    {
        $order_id = $request->id_order;
        $order = Order::find($order_id);
        $payment = $this->payment;
        $order_statuses = $this->order_statuses;
        $order_detail = Order_detail::where('order_id', $order_id)->get();

        $order_detail_show = "
        <div class='row'>
            <div class='col-md-2'>
            <p class=' mb-0'>Hình ảnh</p></div>
            <div class='col-md-4'> <p style='text-align:center;' class=' mb-0'>Tên sản phẩm</p></div>
            <div class='col-md-2'> <p style='text-align:center;' class='mb-0'>Giá</p></div>
            <div class='col-md-2'> <p style='text-align:center;' class=' mb-0'>Số lượng</p></div>
            <div class='col-md-2'> <p style='text-align:center;' class='mb-0'>Tổng tạm tính</p></div>
        </div>
        ";

        foreach ($order_detail as $key => $value) {
            $order_detail_show .= "
            <div class='card shadow-0 border mb-4'>
            <div class='card-body'>" .
                "
                <div class='row'>
                    <div class='col-md-2'>
                        <img src='" . config('app.base_url') . $value->product->thumbnail . "' style='width:100px;' class='img-fluid' alt='" . $value->product->name . "'>
                    </div>
                    <div class='col-md-4 text-center d-flex justify-content-center align-items-center'>
                        <p class='text-muted mb-0'>" . $value->product->name . "</p>" .
                "<span class='text-muted mb-0'> Category: " . $value->product->product_cat->name . "</span><br>" .
                "<span class='text-muted mb-0'> Brand: " . $value->product->product_brand->name . "</span>" .
                "
                    </div>
                    <div class='col-md-2 text-center d-flex justify-content-center align-items-center'>
                        <p class='text-muted mb-0 small'>" . number_format($value->price, 1, ',', '.') . "</p>
                    </div>
                  
                    <div class='col-md-2 text-center d-flex justify-content-center align-items-center'>
                        <p class='text-muted mb-0 small'>Qty: " . $value->qty . "</p>
                    </div>
                    <div class='col-md-2 text-center d-flex justify-content-center align-items-center'>
                        <p class='text-muted mb-0 small'>$ " . number_format($value->price * $value->qty, 1, ',', '.') . "</p>
                    </div>
                </div>
                <hr class='mb-4' style='background-color: #e0e0e0; opacity: 1;'>
               
            </div>
        </div>
       
            </div>
        </div>";
        }


        //return $order_detail;
        $customer = Customer::where('id', '=', $order->customer_id)->first();

        $order_total = number_format($order->total, 1, ',', '.');
        $final_total = number_format($order->final_total, 1, ',', '.');

        $discount = 0;
        $show_discount = "";
        if (!empty($order->order_coupon)) {

            $coupon = $order->order_coupon;
            if ($coupon->condition == 0) {
                $discount = ($order->total * $coupon->value) / 100;
                $show_discount = "<span >" . number_format($discount, 1, ',', '.') . " - " . $coupon->value  . "%" . "</span>";
            } else if ($coupon->condition == 1) {
                $discount = $coupon->value;
                $show_discount = "<span  >" . number_format($discount, 1, ',', '.') . "</span>";
            }
        } else {
            $show_discount = "<span  >" . number_format(0, 1, ',', '.') . "</span>";
        }

        $order_date = $order->created_at->format('d.m.Y');
        $fee_shipping = $this->fee_shipping_default;
        if (!empty($order->fee_shipping_id)) {
            $fee_shipping = $order->fee_shipping->fee_shipping;
        }

        $fee_shipping = number_format($fee_shipping, 1, ',', '.');

        $class_badge = "";
        if ($order->status == 0) {
            $class_badge = "#007bff";
        } else if ($order->status == 1) {
            $class_badge = "#ffc107";
        } else if ($order->status == 2) {
            $class_badge = "#28a745";
        } else if ($order->status == 3) {
            $class_badge = "#343a40";
        }

        $order_status = "<p><span style='background:" . $class_badge . ";' class='badge'>" . $order_statuses[$order->status] . "</span></p>";

        $data = [
            'payment' => $payment,
            'order_detail' => $order_detail,
            'order' => $order,
            'customer' => $customer,
            'order_total' => $order_total,
            'final_total' => $final_total,

            'order_date' => $order_date,
            'fee_shipping' => $fee_shipping,
            'order_status' => $order_status,
            'order_detail_show' => $order_detail_show,
            'show_discount' => $show_discount

        ];
        return json_encode($data);
    }

    function cancel($id)
    {
        $order = Order::find($id);
        $order->update([
            'status' => 3
        ]);
        return redirect('order/purchase_order')->with('status', 'Bạn đã hủy đơn hàng thành công!');
    }
}
