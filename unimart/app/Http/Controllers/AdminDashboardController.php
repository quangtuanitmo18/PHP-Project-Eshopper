<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use App\Order_detail;
use App\Post;
use App\Product;
use App\Statistical;
use App\User;
use App\Video;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PDF;

class AdminDashboardController extends Controller
{
    //
    public $order_statuses = [
        0 => 'Đang xử lý',
        1 => 'Đang giao hàng',
        2 => 'Thành công',
        3 => 'Hủy'
    ];
    public $payment = [
        0 => 'Thanh toán tại nhà',
        1 => 'Thanh toán paypal',
        2 => 'Thanh toán VNpay'
    ];

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'dashboard']);
            return $next($request);
        });
    }
    function filter_sales(Request $request)
    {
        $date_from = $request['date_from'];

        $date_end = $request['date_end'];


        $data = Statistical::whereBetween('date_order', [$date_from, $date_end])->orderBy('date_order', 'ASC')->get();

        $chart_data = array();

        foreach ($data as $key => $value) {
            $chart_data[] = array(
                'period' => $value->date_order,
                'number_order' => $value->number_order,
                'number_product' => $value->number_product,
                'sales' => $value->sales,
                'profit' => $value->profit

            );
        }

        echo $data = json_encode($chart_data);
    }
    function chart_set_30day(Request $request)
    {
        $sub30day = Carbon::now('Asia/Ho_Chi_Minh')->subdays(30)->toDateString();
        $now = Carbon::now('Asia/Ho_Chi_Minh')->toDateString();
        $data = Statistical::whereBetween('date_order', [$sub30day, $now])->orderBy('date_order', 'ASC')->get();

        $data_chart = array();
        foreach ($data as $key => $value) {
            $data_chart[] = array(
                'period' => $value->date_order,
                'number_order' => $value->number_order,
                'number_product' => $value->number_product,
                'sales' => $value->sales,
                'profit' => $value->profit

            );
        }
        return json_encode($data_chart);
    }
    function show()
    {
        $order_statuses = $this->order_statuses;
        $orders = Order::orderBy('created_at', 'desc')->paginate(10);
        $orders_success = array();
        $orders_processing = array();
        $orders_delete = array();
        $total_sales = Statistical::sum('sales');
        $total_profit = Statistical::sum('profit');
        $number_products_sold = Statistical::sum('number_product');
        $product_qty = Product::where('browse', 1)->count();
        $post_qty = Post::where('status', 1)->count();
        $order_qty = $orders->count();
        $video_qty = Video::where('status', 1)->count();
        $admin_qty = User::count();
        $customer_qty = Customer::count();
        $product_most_viewed = Product::orderBy('views_count', 'DESC')->take(10)->get();
        $post_most_viewed = Post::orderBy('views_count', 'DESC')->take(10)->get();
        $orders_success =    $orders_processing = $orders_delete = 0;

        foreach ($orders as $order) {
            if ($order->status == 2) {
                $orders_success++;
            }
            if ($order->status == 1) {
                $orders_processing++;
            }
            if ($order->status == 3) {
                $orders_delete++;
            }
        }
        return view('admin.dashboard.list', compact('number_products_sold', 'total_profit', 'orders', 'orders_success', 'orders_processing', 'orders_delete', 'total_sales', 'order_statuses', 'product_qty', 'post_qty', 'order_qty', 'admin_qty', 'customer_qty', 'product_most_viewed', 'post_most_viewed'));
    }

    function order_detail($id)
    {
        $payment = $this->payment;
        $order_statuses = $this->order_statuses;
        $order_detail = Order_detail::where('order_id', '=', $id)->get();

        $order = Order::find($id);
        //return $order_detail;
        $customer = Customer::where('id', '=', $order->customer_id)->first();
        return view('admin.dashboard.detail', compact('order_detail', 'customer', 'order', 'payment', 'order_statuses'));
    }

    function delete($id)
    {
        $order = Order::find($id);
        $order->delete();
        return redirect('dashboard')->with('status', 'Bạn đã xóa đơn hàng thành công');
    }
    function update(Request $request, $id)
    {

        Order::where('id', $id)->update([
            'status' => $request->input('status')
        ]);
        return redirect('dashboard')->with('status', 'Bạn đã cập nhật trạng thái đơn hàng thành công');
    }
    public function order_print($id)
    {
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
        $pdf = PDF::loadView('admin.dashboard.order_detail', $data);
        return $pdf->stream('Đơn hàng.pdf');
        // return $pdf->download('Đơn hàng.pdf');
    }
}
