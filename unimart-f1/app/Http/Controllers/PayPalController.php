<?php

namespace App\Http\Controllers;

use App\Order;
use App\Order_coupon;
use App\Order_detail;
use App\Product;
use Carbon\Carbon;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalController extends Controller
{
    /**
     * create transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function createTransaction()
    {
        return view('transaction');
    }

    /**
     * process transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function processTransaction(Request $request)
    {

        $data_VNpay_total_price = $request->session()->get('final_total');
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $paypalToken = $provider->getAccessToken();

        $response = $provider->createOrder([
            "intent" => "CAPTURE",
            "application_context" => [
                "return_url" => route('successTransaction'),
                "cancel_url" => route('cancelTransaction'),
            ],
            "purchase_units" => [
                0 => [
                    "amount" => [
                        "currency_code" => "USD",
                        "value" => $data_VNpay_total_price
                    ]
                ]
            ]
        ]);

        if (isset($response['id']) && $response['id'] != null) {

            // redirect to approve href
            foreach ($response['links'] as $links) {
                if ($links['rel'] == 'approve') {
                    return redirect()->away($links['href']);
                }
            }

            return redirect()
                ->route('createTransaction')
                ->with('error', 'Something went wrong.');
        } else {
            return redirect()
                ->route('createTransaction')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    /**
     * success transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function successTransaction(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {

            $data_VNpay = $request->session()->get('data_VNpay');

            $province = $data_VNpay['province'];
            $district = $data_VNpay['district'];
            $ward = $data_VNpay['ward'];

            // if ($request->payment == 2 || $request->payment == 3) { // thanht toan VNpay
            //     $data_VNpay = array();
            //     $data_VNpay['name'] = $request->name;
            //     $data_VNpay['email'] = $request->email;
            //     $data_VNpay['phone_number'] = $request->phone_number;
            //     $data_VNpay['province'] = $province->name;
            //     $data_VNpay['district'] = $district->name;
            //     $data_VNpay['ward'] = $ward->name;
            //     $data_VNpay['total_price'] = $request->total_price;
            //     $total_price = $request->total_price;
            //     $request->session()->put('data_VNpay', $data_VNpay);
            //     if ($request->payment == 2) {
            //         $menus = Menu::where('parent_id', 0)->get();
            //         return view('cart.VNpay.index', compact('total_price', 'menus'));
            //     } else if ($request->payment == 3) {
            //         return redirect()->route('processTransaction');
            //     }
            // }

            $address = $ward . ", " . $district . ", " . $province;

            $now = Carbon::now()->toDateString();
            $order = Order::create([

                'code_bill' => "E#" . \Str::random(5),
                'customer_id' => Auth::id(),
                'total' => Cart::total(),
                'payment' => 1,
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
            return redirect('sendmail/check_order');

            // return redirect()
            //     ->route('createTransaction')
            //     ->with('success', 'Transaction complete.');
        } else {
            return redirect()
                ->route('createTransaction')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }

    /**
     * cancel transaction.
     *
     * @return \Illuminate\Http\Response
     */
    public function cancelTransaction(Request $request)
    {
        return redirect()
            ->route('createTransaction')
            ->with('error', $response['message'] ?? 'You have canceled the transaction.');
    }
}
