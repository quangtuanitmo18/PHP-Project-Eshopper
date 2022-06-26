@extends('layouts.admin')
@section('js')

<script>
    $(document).ready(function() {

        $('select#select_status ').on('change', function() {

            $select_status = this.value;

            $current_status = $('#current_status').val();

            if ($select_status == $current_status) {
                $('.btn-update-status').prop('disabled', true);
            } else {
                $('.btn-update-status').prop('disabled', false);
            }

        });


    });
</script>

@endsection

@section('content')



<table style="display: block;
    margin-top: 20px;
    margin-left: 20px;">
    <tbody style="">

        <tr style="">
            <td style="">
                <h1 style="font-size:17px;font-weight:bold;padding:0px 0px 5px;margin:0px;color:rgb(68,68,68)">Quý khách {{$customer->name}} đã đặt hàng tại UNITOP</h1>

                {{-- <p style="margin:4px 0px;font-size:12px;line-height:18px;font-weight:normal;color:rgb(68,68,68)">UNITOP rất vui thông báo đơn hàng #{{$order_detail[0]->order->code_bill}} của quý khách đã được tiếp nhận và đang trong quá trình xử lý. Unitop sẽ thông báo đến quý khách ngay khi hàng chuẩn bị được giao.</p> --}}

                <h3 style="font-size:13px;font-weight:bold;text-transform:uppercase;margin:20px 0px 0px;border-bottom-width:1px;border-bottom-style:solid; ;border-bottom-color:rgb(221,221,221);color:rgb(2,172,234)">Thông tin đơn hàng #{{$order_detail[0]->order->code_bill}} <span style="font-size:12px;text-transform:none;font-weight:normal; ;color:rgb(119,119,119)">({{$order->created_at}})</span></h3>
            </td>
        </tr>
        <tr style="">
            <td style="font-size:12px;line-height:18px;color:rgb(68,68,68)">
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="">
                    <thead style="">
                        <tr style="">
                            <th align="left" style="padding:6px 9px 0px,sans-serif;font-size:12px;font-weight:bold;color:rgb(68,68,68)" width="50%">Thông tin thanh toán</th>
                            <th align="left" style="padding:6px 9px 0px;font-size:12px;font-weight:bold;color:rgb(68,68,68)" width="50%"> Địa chỉ giao hàng </th>
                        </tr>
                    </thead>
                    <tbody style="">
                        <tr style="">
                            <td style="padding:3px 9px 9px;border-top-width:0px;font-size:12px;line-height:18px;font-weight:normal;color:rgb(68,68,68)" valign="top"><span style="text-transform:capitalize; ">{{$customer->name}}</span><br>
                                <a href="mailto:vanquangnguyenmp@gmail.com" style="" target="_blank">{{$customer->email}}</a><br>
                                {{$customer->phone_number}}
                            </td>
                            <td style="padding:3px 9px 9px;border-top-width:0px;border-left-width:0px;font-size:12px;line-height:18px;font-weight:normal;color:rgb(68,68,68)" valign="top"><span style="text-transform:capitalize; ">{{$order->name}}</span><br>
                                <a href="mailto:vanquangnguyenmp@gmail.com" style="" target="_blank">{{$order->email}}</a><br>
                                <a href="https://www.google.com/maps/search/S%E1%BB%91+236+Ho%C3%A0ng+Qu%E1%BB%91c+Vi%E1%BB%87t,+C%E1%BB%95+Nhu%E1%BA%BF+1,+B%E1%BA%AFc+T%E1%BB%AB+Li%C3%AAm,+H%C3%A0+N%E1%BB%99i?entry=gmail&amp;source=g" style=" " target="_blank" data-saferedirecturl="https://www.google.com/url?q=https://www.google.com/maps/search/S%25E1%25BB%2591%2B236%2BHo%25C3%25A0ng%2BQu%25E1%25BB%2591c%2BVi%25E1%25BB%2587t,%2BC%25E1%25BB%2595%2BNhu%25E1%25BA%25BF%2B1,%2BB%25E1%25BA%25AFc%2BT%25E1%25BB%25AB%2BLi%25C3%25AAm,%2BH%25C3%25A0%2BN%25E1%25BB%2599i?entry%3Dgmail%26source%3Dg&amp;source=gmail&amp;ust=1640352872348000&amp;usg=AOvVaw1mFZVY2RaqNTRaXiTschnE">{{$order->address}}</a><br>
                                T: {{$order->phone_number}}
                            </td>
                        </tr>
                        <tr style="">
                            <td colspan="2" style="padding:7px 9px 0px;border-top-width:0px;font-size:12px;color:rgb(68,68,68)" valign="top">
                                <p style="font-size:12px;line-height:18px;font-weight:normal;color:rgb(68,68,68)"><strong style=" ">Phương thức thanh toán: </strong> {{$payment[$order->payment]}}<br>
                                <p style="display: inline-block; font-size:12px;line-height:18px;font-weight:normal;color:rgb(68,68,68)"><strong style=" ">Trạng thái: </strong>

                                    @can('order-edit')
                                <div class="form-action form-inline p-3" style="display: inline-block;">
                                    <form action="{{route('admin.order.update',$order->id)}}" method="POST">
                                        @csrf
                                        <select style="width: auto;height: 27px;font-size: 14px;padding-top: 0px;" class="custom-select form-select-sm mr-sm-2 " id='select_status' name="status">
                                            @foreach($order_statuses as $k=>$value)
                                            <option value="{{$k}}" {{$k==$order->status?"selected='selected'":''}}>{{$value}}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" id='current_status' value='{{$order->status}}'>
                                        <input type="submit" style="    height: 27px;padding-top: 0px;" name="btn_update" value="Áp dụng" class="btn btn-primary btn-update-status" disabled>
                                    </form>
                                </div>
                                <br>
                                @endcan
                                <strong style="">Sử dụng bọc sách cao cấp Bookcare: </strong> Không <br>

                                </p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr style="">
            <td style="">
                <p style="margin:4px 0px;font-size:12px;line-height:18px;font-weight:normal;color:rgb(68,68,68)"><i style=" ">Lưu ý: Đối với đơn hàng đã được thanh toán trước, nhân viên giao nhận có thể yêu cầu người nhận hàng cung cấp CMND / giấy phép lái xe để chụp ảnh hoặc ghi lại thông tin.</i></p>
            </td>
        </tr>
        <tr style="">
            <td style="">
                <h2 style="text-align:left;margin:10px 0px;border-bottom-width:1px;border-bottom-style:solid;padding-bottom:5px;font-size:13px ;border-bottom-color:rgb(221,221,221);color:rgb(2,172,234)">CHI TIẾT ĐƠN HÀNG</h2>
                <table border="0" cellpadding="0" cellspacing="0" style="background-color:rgb(245,245,245)" width="100%">
                    <thead style="">
                        <tr style="">
                            <th align="left" bgcolor="#02acea" style="padding:6px 9px;font-size:12px;line-height:14px;color:rgb(255,255,255)">Stt</th>
                            <th align="left" bgcolor="#02acea" style="padding:6px 9px;font-size:12px;line-height:14px;color:rgb(255,255,255)">Sản phẩm</th>
                            <th align="left" bgcolor="#02acea" style="padding:6px 9px;font-size:12px;line-height:14px;color:rgb(255,255,255)">Hình ảnh</th>

                            <th align="left" bgcolor="#02acea" style="padding:6px 9px;font-size:12px;line-height:14px;color:rgb(255,255,255)">Đơn giá</th>
                            <th align="left" bgcolor="#02acea" style="padding:6px 9px;font-size:12px;line-height:14px;color:rgb(255,255,255)">Số lượng</th>
                            <th align="right" bgcolor="#02acea" style="padding:6px 9px;font-size:12px;line-height:14px;color:rgb(255,255,255)">Tổng tạm</th>
                        </tr>
                    </thead>
                    <tbody bgcolor="#eee" style="font-size:12px;line-height:18px;color:rgb(68,68,68)">
                        @php
                        $t=0;
                        @endphp
                        @foreach($order_detail as $value)
                        @php
                        $t++;
                        $base_url = config('app.base_url');
                        @endphp
                        <tr style="">
                            <td align="left" style="padding:3px 9px;" valign="top">{{$t}}</td>

                            <td align="left" style="padding:3px 9px;" valign="top"><span style="">{{$value->product->name}}</span><br>
                                <span>- Danh mục: {{$value->product->product_cat->name}}</span><br>
                                <span>- Nhãn hiệu: {{$value->product->product_brand->name}}</span><br>
                            </td>
                            <td align="left" style="padding:3px 9px;" valign="top"> <img style="width:100px; height:auto;" src="{{$base_url.$value->product->thumbnail}}" alt=""> <br>
                            </td>
                            <td align="left" style="padding:3px 9px;" valign="top"><span style="">{{number_format($value->price,1,',','.')}}&nbsp;$</span></td>
                            <td align="left" style="padding:3px 9px;" valign="top">{{$value->qty}}</td>
                            {{-- <td align="left" style="padding:3px 9px;" valign="top"><span style="">0&nbsp;₫</span></td> --}}
                            <td align="right" style="padding:3px 9px;" valign="top"><span style="">{{number_format($value->price*$value->qty,1,',','.')}}&nbsp;$</span></td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="font-size:12px;line-height:18px;color:rgb(68,68,68)">
                        <tr style="">
                            <td align="right" colspan="5" style="padding:5px 9px;">Tổng tạm tính</td>
                            <td align="right" style="padding:5px 9px;"><span style="">{{number_format($order->total,1,',','.')}}&nbsp;$</span></td>
                        <tr>
                            <td align="right" colspan="5" style="padding:5px 9px;">Giảm giá</td>
                            <?php
                            if (!empty($order->order_coupon)) {
                                $coupon = $order->order_coupon;
                                if ($coupon->condition == 0) {
                                    $discount = ($order->total * $coupon->value) / 100;
                            ?>
                                    <td align="right" style="padding:5px 9px;"><span style="">{{$coupon->value}}% - {{number_format($discount,1,',','.')}}&nbsp;$</span></td>

                                <?php
                                } else if ($coupon->condition == 1) {
                                    $discount = $coupon->value;
                                ?>
                                    <td align="right" style="padding:5px 9px;"><span style="">{{number_format($discount,1,',','.')}}&nbsp;$</span></td>

                                <?php
                                }

                                ?>
                            <?php
                            } else {
                            ?>

                                <td align="right" style="padding:5px 9px;"><span style="">0&nbsp;$</span></td>

                            <?php
                            }
                            ?>

                        </tr>
        </tr>
        <tr style="">
            <td align="right" colspan="5" style="padding:5px 9px;">Phí vận chuyển</td>

            <?php
            if (!empty($order->fee_shipping_id)) {
            ?>


                <td align="right" style="padding:5px 9px;"><span style="">{{$order->fee_shipping->fee_shipping}}&nbsp;$</span></td>
            <?php } else {
            ?>
                <td align="right" style="padding:5px 9px;"><span style="">10&nbsp;$</span></td>

            <?php } ?>
        </tr>
        <tr bgcolor="#eee" style="">
            <td align="right" colspan="5" style="padding:7px 9px;"><strong style=""><big style="">Tổng giá trị đơn hàng</big> </strong></td>
            <td align="right" style="padding:7px 9px;"><big style=""><span style="">{{number_format($order->final_total,1,',','.')}}</span>&nbsp;$</big> </strong></td>
        </tr>
        </tfoot>
</table>

</td>
</tr>


</tbody>
</table>





</body>
@endsection