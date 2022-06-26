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
            <h5 class="m-0 ">Danh sách đơn hàng</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="" class="form-control form-search" placeholder="Tìm kiếm" name="keyword" id="keyword" value="{{request()->input('keyword')}}">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">

                <a href="{{request()->fullUrlwithQuery(['status'=>'processing']) }}" class="text-primary">Đang xử lý<span class="text-muted">({{$count[1]}})</span></a>
                <a href="{{request()->fullUrlwithQuery(['status'=>'delivery']) }}" class="text-primary">Đang giao hàng<span class="text-muted">({{$count[2]}})</span></a>
                <a href="{{request()->fullUrlwithQuery(['status'=>'success']) }}" class="text-primary">Thành công<span class="text-muted">({{$count[3]}})</span></a>
                <a href="{{request()->fullUrlwithQuery(['status'=>'delete']) }}" class="text-primary">Hủy<span class="text-muted">({{$count[4]}})</span></a>
                <a href="{{request()->fullUrlwithQuery(['status'=>'trash']) }}" class="text-primary">Vô hiệu hóa<span class="text-muted">({{$count[5]}})</span></a>

            </div>
            <form action="{{url("admin/order/action")}}">
                @can('order-action')
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
                            <th>
                                <input type="checkbox" name="checkall">
                            </th>
                            <th scope="col">#</th>
                            <th scope="col">Mã</th>
                            <th scope="col">Khách hàng</th>
                            <th scope="col">Tổng tiền</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Thời gian</th>
                            <th scope="col">Chi tiết</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($orders->total()>0)
                        @php
                        $t=0;
                        @endphp
                        @foreach($orders as $order)
                        @php
                        $t++;
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" name="list_check[]" value={{$order->id}}>
                            </td>
                            <td>{{$t}}</td>
                            <td>{{$order->code_bill}}</td>
                            <td>
                                {{$order->customer->name}} <br>
                                {{$order->customer->phone_number}}
                            </td>

                            <td>

                                {{number_format($order->final_total,0,',','.')}}$
                            </td>
                            @php
                            $class_badge="";
                            if($order->status==0){
                            $class_badge="badge-primary";
                            }
                            else if($order->status==1){
                            $class_badge="badge-warning";
                            }
                            else if($order->status==2){
                            $class_badge="badge-success";
                            }
                            else if($order->status==3){
                            $class_badge="badge-dark";
                            }
                            @endphp
                            <td><span class="badge {{$class_badge}}">{{$order_statuses[$order->status]}}</span></td>

                            <td>{{$order->created_at}}</td>
                            <td>
                                @can('order-detail')
                                <a href="{{route('admin.order.detail',$order->id)}}">chi tiết</a>
                                @endcan
                            </td>
                            <td>
                                @can('order-delete')
                                <a href="{{route('admin.order.delete',$order->id)}}" class="btn btn-danger btn-sm rounded-0 text-white" onclick="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này không?')" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                @endcan

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

            {{$orders->links()}}

        </div>
    </div>
</div>

@endsection