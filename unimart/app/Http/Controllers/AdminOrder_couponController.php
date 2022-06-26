<?php

namespace App\Http\Controllers;

use App\Event;
use App\Order_coupon;
use Illuminate\Http\Request;

class AdminOrder_couponController extends Controller
{
    //
    public $coupon_statuses = [
        0 => "Chờ duyệt",
        1 => "Công khai",

    ];
    public $coupon_conditions = [
        0 => "Giảm theo %",
        1 => "Giam theo số tiền"

    ];
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'order_coupon']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        $statuses = $this->coupon_statuses;
        $conditions = $this->coupon_conditions;
        $list_act = [
            'delete' => 'Xoá tạm thời'
        ];

        if ($request->input('status') == "active") {
            $order_coupons = Order_coupon::orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->input('status') == "trash") {
            $order_coupons = Order_coupon::onlyTrashed()->orderBy('created_at', 'desc')->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
        } else {
            $order_coupons = Order_coupon::orderBy('created_at', 'desc')->paginate(10);
        }

        $count_coupon_active = Order_coupon::all()->count();

        $count_coupon_trash = Order_coupon::onlyTrashed()->count();
        $count = [$count_coupon_active, $count_coupon_trash];
        return view('admin.order.coupon.list', compact('order_coupons', 'statuses', 'conditions', 'count', 'list_act'));
    }

    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if (!empty($list_check)) {



            $act = $request->input('act');
            if ($act == 'delete') {
                Order_coupon::destroy($list_check);
                return redirect('admin/order/coupon/list')->with('status', 'Bạn đã xóa mã thành công');
            } else if ($act == 'restore') {
                Order_coupon::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect('admin/order/coupon/list')->with('status', 'Bạn đã khôi phục mã thành công');
            } else if ($act == 'forceDelete') {
                Order_coupon::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect('admin/order/coupon/list')->with('status', 'Bạn đã xóa vĩnh viễn mã thành công');
            } else {
                return redirect('admin/order/coupon/list')->with('status', 'Bạn cần chọn 1 thao tác');
            }
        } else {
            return redirect('admin/order/coupon/list')->with('status', 'Bạn cần chọn mã để thực thi');
        }
    }
    function delete($id)
    {
        $order_coupon = Order_coupon::find($id);
        $order_coupon->delete();
        return redirect('admin/order/coupon/list')->with('status', 'Bạn đã xóa mã thành công');
    }

    function edit($id)
    {
        $order_coupon = Order_coupon::find($id);
        $events = Event::where('status', 1)->get();
        $statuses = $this->coupon_statuses;
        $conditions = $this->coupon_conditions;
        return view('admin.order.coupon.edit', compact('order_coupon', 'events', 'statuses', 'conditions'));
    }

    function update(Request $request, $id)
    {
        $request->validate(
            [
                'code' => 'required|string|max:255',
                'event_id' => 'required|integer',
                'condition' => 'required|integer',
                'value' => 'required|integer',
                'qty' => 'required|integer',
                'status' => 'required|string',
                'date_end' => 'required'
            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'code' => 'Mã giảm giá',
                'event_id' => "Sự kiện",
                'condition' => 'Điều kiện',
                'Value' => 'Giá trị',
                'qty' => 'Số lượng',
                'status' => 'Trạng thái',
                'date_end' => "Ngày hết hạn"


            ]
        );

        Order_coupon::where('id', $id)->update([
            'code' => $request->input('code'),
            'qty' => $request->input('qty'),
            'condition' => $request->input('condition'),
            'value' => $request->input('value'),
            'status' => $request->input('status'),
            'event_id' => $request->input('event_id'),
            'date_end' => $request->input('date_end'),
        ]);
        return redirect('admin/order/coupon/list')->with('status', 'Bạn đã cập nhật mã thành công');
    }

    function add(Request $request)
    {
        $statuses = $this->coupon_statuses;
        $conditions = $this->coupon_conditions;
        $events = Event::where('status', 1)->get();
        return view('admin.order.coupon.add', compact('statuses', 'conditions', 'events'));
    }
    function store(Request $request)
    {

        $request->validate(
            [
                'code' => 'required|string|max:255',
                'event_id' => 'required|integer',
                'condition' => 'required|integer',
                'value' => 'required|integer',
                'qty' => 'required|integer',
                'status' => 'required|string',
                'date_end' => 'required'
            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'code' => 'Mã giảm giá',
                'event_id' => "Sự kiện",
                'condition' => 'Điều kiện',
                'Value' => 'Giá trị',
                'qty' => 'Số lượng',
                'status' => 'Trạng thái',
                'date_end' => "Ngày hết hạn"


            ]
        );

        Order_coupon::create([
            'code' => $request->input('code'),
            'qty' => $request->input('qty'),
            'condition' => $request->input('condition'),
            'value' => $request->input('value'),
            'status' => $request->input('status'),
            'event_id' => $request->input('event_id'),
            'date_end' => $request->input('date_end'),
        ]);
        return redirect('admin/order/coupon/list')->with('status', 'Đã thêm mã thành công');
    }
}
