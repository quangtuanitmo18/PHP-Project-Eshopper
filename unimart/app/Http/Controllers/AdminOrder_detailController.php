<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Order;
use App\Order_detail;
use Illuminate\Http\Request;

class AdminOrder_detailController extends Controller
{
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'order']);
            return $next($request);
        });
    }
    public $payment = [
        0 => 'Thanh toán tại nhà',
        1 => 'Thanh toán paypal',
        2 => 'Thanh toán VNpay'
    ];
    public $order_statuses = [
        0 => "Đang xử lý",
        1 => "Đang giao hàng",
        2 => "Thành công",
        3 => "Hủy",
    ];
    function show($order_id)
    {

        $payment = $this->payment;
        $order_statuses = $this->order_statuses;
        $order_detail = Order_detail::where('order_id', '=', $order_id)->get();
        $order = Order::find($order_id);
        //return $order_detail;
        $customer = Customer::where('id', '=', $order->customer_id)->first();
        return view('admin.order.detail', compact('order_detail', 'customer', 'order', 'payment', 'order_statuses'));
    }
}
