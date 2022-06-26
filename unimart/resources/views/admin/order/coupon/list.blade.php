@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
        @endif
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách mã giảm giá</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="" class="form-control form-search" name="keyword" id="keyword" placeholder="Tìm kiếm">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{request()->fullUrlwithQuery(['status'=>'active']) }}" class="text-primary">Kích hoạt<span class="text-muted">({{$count[0]}})</span></a>
                <a href="{{request()->fullUrlwithQuery(['status'=>'trash']) }}" class="text-primary">Vô hiệu hóa<span class="text-muted">({{$count[1]}})</span></a>
            </div>
            <form action="{{url("admin/order/coupon/action")}}">
                @can('order-coupon-action')
                <div class="form-action form-inline py-3">
                    <select class="form-control mr-1" id="" name="act">
                        <option>Chọn</option>
                        @foreach($list_act as $k=>$value)
                        <option value="{{$k}}">{{$value}}</option>
                        @endforeach
                    </select>
                    <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                </div>
                @endcan
                <table class="table table-striped table-checkall" id="myTable">
                    <thead>
                        <tr>
                            <th scope="col">
                                <input name="checkall" type="checkbox">
                            </th>
                            <th scope="col">#</th>
                            <th scope="col">Mã</th>
                            <th scope="col">Sự kiện</th>
                            <th scope="col">Số lượng còn lại</th>
                            <th scope="col">Điều kiện</th>
                            <th scope="col">Giá trị</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Ngày hết hạn</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($order_coupons->total()>0)
                        @php
                        $t=0;
                        @endphp
                        @foreach($order_coupons as $order_coupon)
                        @php
                        $t++;
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" name="list_check[]" value="{{$order_coupon->id}}">
                            </td>
                            <td scope="row">{{$t}}</td>
                            <td><a href="">{{$order_coupon->code}}</a></td>
                            <td>{{$order_coupon->event->name}}</td>
                            <td>{{$order_coupon->qty}}</td>
                            <td>{{$conditions[$order_coupon->condition]}}</td>
                            <td>{{$order_coupon->value}}</td>
                            <?php
                            if ($order_coupon->status == 0) {
                                $color_badge = "badge-primary";
                            } else if ($order_coupon->status == 1) {
                                $color_badge = "badge-success";
                            } else if ($order_coupon->status == 2) {
                                $color_badge = "badge-dark";
                            } else if ($order_coupon->status == 3) {
                                $color_badge = "badge-warning";
                            }
                            ?>
                            <td><span class="badge {{$color_badge}}">{{$statuses[$order_coupon->status]}}</span></td>
                            <td>{{$order_coupon->created_at}}</td>
                            <td>{{$order_coupon->date_end}}</td>

                            <td>
                                @if(request()->input('status')!="trash")
                                @can('order-coupon-edit')
                                <a href="{{route('admin.order.coupon.edit',$order_coupon->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                @endcan
                                @can('order-coupon-delete')
                                <a href="{{route('admin.order.coupon.delete',$order_coupon->id)}}" class="btn btn-danger btn-sm rounded-0 text-white" onclick="return confirm('bạn có chắc chắn muốn xóa mã này không?')" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>

                                @endcan
                                @endif

                            </td>

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="bg-white">
                                không tìm thấy bản ghi
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </form>
            {{$order_coupons->links()}}
        </div>
    </div>
</div>

@endsection