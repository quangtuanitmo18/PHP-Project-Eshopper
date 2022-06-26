<?php

namespace App\Http\Controllers;

use App\Order;
use App\Product;
use App\Statistical;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminOrderController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'order']);
            return $next($request);
        });
    }
    public $order_statuses = [
        0 => 'Đang xử lý',
        1 => 'Đang giao hàng',
        2 => 'Thành công',
        3 => 'Hủy'
    ];
    function list(Request $request)
    {
        $keyword = "";
        $order_statuses = $this->order_statuses;
        $list_act = [
            'delete' => 'Xóa',
        ];
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        if ($request->input('status') == "processing") {
            $orders = Order::where('status', 0)->where('code_bill', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->input('status') == "delivery") {
            $orders = Order::where('status', 1)->where('code_bill', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->input('status') == "success") {
            $orders = Order::where('status', 2)->where('code_bill', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->input('status') == "delete") {
            $orders = Order::where('status', 3)->where('code_bill', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->input('status') == "all_orders") {
            $orders = Order::where('code_bill', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->input('status') == "trash") {
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
            $orders = Order::onlyTrashed()->where('code_bill', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $orders = Order::where('code_bill', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        }

        $count_order_processing = Order::where('status', 0)->count();
        $count_order_delivery = Order::where('status', 1)->count();
        $count_order_success = Order::where('status', 2)->count();
        $count_order_delete = Order::where('status', 3)->count();
        $count_order_trash = Order::onlyTrashed()->count();

        $count_order_all_orders = $count_order_processing + $count_order_delivery + $count_order_success + $count_order_delete;
        $count = [$count_order_all_orders, $count_order_processing, $count_order_delivery, $count_order_success, $count_order_delete, $count_order_trash];


        return view('admin.order.list', compact('orders', 'order_statuses', 'count', 'list_act'));
    }

    function delete($id)
    {
        // return session('module_active');
        $order = Order::find($id);
        $order->delete();
        return redirect('admin/order/list')->with('status', 'Bạn đã xóa đơn hàng thành công');
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        //return $list_check;
        if (!empty($list_check)) {

            $act = $request->input('act');
            if ($act == 'delete') {
                Order::whereIn('id', $list_check)->delete();
                return redirect('admin/order/list')->with('status', 'Bạn đã xóa đơn hàng thành công');
            } else if ($act == 'restore') {
                Order::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect('admin/order/list')->with('status', 'Bạn đã khôi phục đơn hàng thành công');
            } else if ($act == 'forceDelete') {
                Order::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect('admin/order/list')->with('status', 'Bạn đã xóa vĩnh viễn đơn hàng thành công');
            } else {
                return redirect('admin/order/list')->with('status', 'Bạn cần chọn 1 thao tác');
            }
        } else {
            return redirect('admin/order/list')->with('status', 'Bạn cần chọn đơn hàng để thực thi');
        }
    }
    function update(Request $request, $id)
    {

        Order::where('id', $id)->update([
            'status' => $request->input('status')
        ]);

        $order = Order::where('id', $id)->first();

        if ($request->input('status') == 2) { // don hang thanh cong


            $orders_detail = $order->orders_detail;
            $qty_product = 0;
            $price_cost = 0;
            foreach ($orders_detail as $value) {
                $product = $value->product;
                $qty_product += $value->qty;
                $price_cost += ($product->price_cost * $value->qty);
                Product::where('id', $value->product_id)->update([
                    'qty_sold' => $product->qty_sold + $value->qty,
                    'qty' => $product->qty - $value->qty,
                ]);
            }

            $data_order = Carbon::now()->toDateString();

            $data = Statistical::where('date_order', $data_order)->get();


            if ($data->count() > 0) {
                $data[0]->update([
                    'number_product' => $data[0]->number_product + $qty_product,
                    'number_order' => $data[0]->number_order + 1,
                    'sales' => $data[0]->sales + $order->final_total,
                    'profit' => $data[0]->profit + ($order->final_total - $price_cost),
                ]);
            } else {
                Statistical::create([
                    'number_product' =>  $qty_product,
                    'number_order' =>  1,
                    'sales' => $order->final_total,
                    'profit' =>  $order->final_total - $price_cost,
                    'date_order' => $data_order,
                ]);
            }



            $request->session()->put('check_qty_sold', 1);
        } else {
            if ($request->session()->get('check_qty_sold') == 1) {

                $orders_detail = $order->orders_detail;
                $qty_product = 0;
                $price_cost = 0;
                foreach ($orders_detail as $value) {
                    $product = $value->product;
                    $qty_product += $value->qty;
                    $price_cost += ($value->price_cost * $value->qty);

                    Product::where('id', $value->product_id)->update([
                        'qty_sold' => $product->qty_sold - $value->qty,

                    ]);
                }
                $date_order = $order->date_order;


                $data = Statistical::where('date_order', $date_order)->get();
                if ($data->count() > 0) {
                    if ($data[0]->number_order > 1) {
                        $data[0]->update([
                            'number_product' => $data[0]->number_product - $qty_product,
                            'number_order' => $data[0]->number_order - 1,
                            'sales' => $data[0]->sales - $order->final_total,
                            'profit' => $data[0]->profit - ($order->final_total - $price_cost),
                        ]);
                    } else {
                        $data[0]->delete();
                    }
                }
            }
            $request->session()->put('check_qty_sold', 0);
            if ($request->input('status') == 3) {
                $orders_detail = $order->orders_detail;

                foreach ($orders_detail as $value) {
                    $product = $value->product;
                    if ($request->session()->get('check_qty_sold') == 1) {
                        Product::where('id', $value->product_id)->update([
                            'qty' => $product->qty + $value->qty,
                            'qty_sold' => $product->qty_sold - $value->qty,

                        ]);
                    } else {
                        Product::where('id', $value->product_id)->update([
                            'qty' => $product->qty + $value->qty,

                        ]);
                    }
                }
            }
        }

        return redirect('admin/order/list')->with('status', 'Bạn đã cập nhật trạng thái đơn hàng thành công');
    }
}
