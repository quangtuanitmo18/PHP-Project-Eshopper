<?php

namespace App\Http\Controllers;

use App\Event;
use App\Order_coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Order_couponController extends Controller
{
    //
    function check_coupon(Request $request)
    {

        $id_event = $request->input('event');
        if ($id_event == "") {
            return  redirect('cart/show')->with(['status_coupon' => 'Bạn cần chọn 1 sự kiện', 'color' => 'alert-danger']);
        } else {
            $event = Event::find($id_event);
            $coupons = $event->order_coupons;
            $coupon = $request->input('coupon');

            if (session()->get('user_id') != Auth::id()) {
                foreach ($coupons as $key => $item) {
                    if ($item->code == $coupon) {
                        if ($item->status == 1) {
                            if ($item->qty > 0) {
                                $coupon_used = $item->coupon_used;
                                $coupon_used = explode(',', $coupon_used);
                                if (!in_array(Auth::id(), $coupon_used)) {
                                    if (strtotime($item->date_end) - time() >= 0) {
                                        $request->session()->put([
                                            'user_id' => Auth::id(),
                                            'id' => $item->id,
                                            'event_id' => $item->event_id,
                                            'code' => $item->code,
                                            'qty' => $item->qty,
                                            'value' => $item->value,
                                            'condition' => $item->condition
                                        ]);
                                        Order_coupon::where('id', $item->id)->update([
                                            'qty' => $item->qty - 1,
                                        ]);
                                        return  redirect('cart/show')->with(['status_coupon' => 'Bạn đã nhập mã giảm giá thành công!', 'color' => 'alert-success']);
                                    } else {
                                        return  redirect('cart/show')->with(['status_coupon' => 'Mã giảm giá bạn nhập đã hết hạn.', 'color' => 'alert-danger']);
                                    }
                                } else {
                                    return  redirect('cart/show')->with(['status_coupon' => 'Mã giảm giá này đã được tài khoản của bạn sử dụng.', 'color' => 'alert-danger']);
                                }
                            } else {
                                return  redirect('cart/show')->with(['status_coupon' => 'Mã giảm giá này đã hết.', 'color' => 'alert-danger']);
                            }
                        }
                    }
                }
                return  redirect('cart/show')->with(['status_coupon' => 'Mã giảm giá bạn nhập không tồn tại', 'color' => 'alert-danger']);
            } else {
                return  redirect('cart/show')->with(['status_coupon' => 'Bạn đã sử dụng mã giảm giá cho đơn hàng này rồi.', 'color' => 'alert-danger']);
            }
        }
    }
    function delete()
    {
        session()->pull('user_id');

        session()->pull('id');
        session()->pull('event_id');
        session()->pull('code');
        session()->pull('qty');
        session()->pull('value');
        session()->pull('condition');
        return  redirect('cart/show')->with(['status_coupon' => 'Bạn đã xóa mã giảm giá cho đơn hàng thành công!', 'color' => 'alert-success']);
    }
}
