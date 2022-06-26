<?php $base_url_f = config('app.base_url_f'); ?>
@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css">

@endsection


@section('js')
<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js"></script>
<script>
    $(function() {
        $("#datepicker1").datepicker({
            dateFormat: "yy-mm-dd",
        });
    });
    $(function() {
        $("#datepicker2").datepicker({
            dateFormat: "yy-mm-dd",
        });
    });

    $(document).ready(function() {

        chart_set_30day();

        function chart_set_30day() {
            var _token = $('input[name="_token"]').val();

            $.ajax({

                type: "POST",
                dataType: "JSON",
                url: "{{ route('dashboard.chart_set_30day') }}",
                data: {

                    _token: _token,
                },
                success: function(data) {

                    chart.setData(data);
                }
            });

        }
        $('.filter_sales').click(function() {
            var date_from = $('.date_from').val();
            var date_end = $('.date_end').val();
            //alert(date_end + date_from);
            var _token = $('input[name="_token"]').val();

            $.ajax({

                type: "POST",
                dataType: "JSON",
                url: "{{ route('dashboard.filter_sales') }}",
                data: {
                    date_from: date_from,
                    date_end: date_end,
                    _token: _token,
                },
                success: function(data) {

                    chart.setData(data);
                }
            });
        });
        var chart = new Morris.Area({
            element: 'chart',
            lineColors: ['#819C79', '#fc8710', '#FF6541', '#A4ADD3', '#766B56'],
            pointFillColors: ['#ffffff'],
            pointStrokeColors: ['black'],
            fillOpacity: 0.3,
            hideHover: 'auto',
            parseTime: false,
            xkey: 'period',
            ykeys: ['number_order', 'sales', 'profit', 'number_product'],
            labels: ['Đơn hàng', 'Doanh số', 'Lợi nhuận', 'Số lượng sản phẩm']
        });

        Morris.Donut({
            element: 'donut',
            resize: true,

            data: [{
                    label: "Products",
                    value: <?php echo $product_qty ?>
                },
                {
                    label: "Posts",
                    value: <?php echo $post_qty ?>
                },
                {
                    label: "Orders",
                    value: <?php echo $order_qty ?>
                },
                {
                    label: "Admins",
                    value: <?php echo $admin_qty ?>
                },
                {
                    label: "Users",
                    value: <?php echo $customer_qty ?>
                },

            ]
        });

    })
</script>
@endsection


@section('content')
<div class="container-fluid py-5">

    <div class="row">

        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                <div class="card-header">DOANH SỐ</div>
                <div class="card-body">
                    <h5 class="card-title">{{number_format($total_sales,0,',','.')}} $</h5>
                    <p class="card-text">Doanh số hệ thống</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                <div class="card-header">LỢI NHUẬN</div>
                <div class="card-body">
                    <h5 class="card-title">{{number_format($total_profit,0,',','.')}} $</h5>
                    <p class="card-text">Doanh số hệ thống</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning mb-3" style="max-width: 18rem;">
                <div class="card-header">SỐ LƯỢNG SẢN PHẨM BÁN RA</div>
                <div class="card-body">
                    <h5 class="card-title">{{$number_products_sold}}</h5>
                    <p class="card-text">Doanh số hệ thống</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info mb-3" style="max-width: 18rem;">
                <div class="card-header">ĐƠN HÀNG THÀNH CÔNG</div>
                <div class="card-body">
                    <h5 class="card-title">{{$orders_success}}</h5>
                    <p class="card-text">Đơn hàng giao dịch thành công</p>
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                <div class="card-header">ĐANG XỬ LÝ</div>
                <div class="card-body">
                    <h5 class="card-title">{{$orders_processing}}</h5>
                    <p class="card-text">Số lượng đơn hàng đang xử lý</p>
                </div>
            </div>
        </div>


        <div class="col-md-3">
            <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                <div class="card-header">ĐƠN HÀNG HỦY</div>
                <div class="card-body">
                    <h5 class="card-title">{{$orders_delete}}</h5>
                    <p class="card-text">Số đơn bị hủy trong hệ thống</p>
                </div>
            </div>
        </div>
    </div>

    <form>
        @csrf
        <span>Từ ngày: <input type="text" id="datepicker1" class='date_from' name='date_from'></span>
        <span>đến ngày: <input type="text" id="datepicker2" class='date_end' name='date_end'></span>
        <input type="button" class='btn btn-sm btn-primary filter_sales' name='filter_sales' value='Lọc kết quả'>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div id="chart">

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
            <div id="donut">

            </div>
        </div>
        <div class="col-md-4">
            <h4>Sản phẩm nổi bật</h4>
            <div id="products_most_viewed">
                <?php $count = 0 ?>
                @foreach($product_most_viewed as $value)
                <?php $count++; ?>
                <span>{{$count}}. </span>
                <a target="_blank" href="{{$base_url_f.'product_detail/'.$value->slug.'/'.$value->id}}">{{$value->name}}</a> | <span>{{$value->views_count}}</span>
                <br>
                @endforeach
            </div>
        </div>

        <div class="col-md-4">
            <h4>Sản phẩm nổi bật</h4>
            <div id="posts_most_viewed">
                <?php $count = 0 ?>
                @foreach($post_most_viewed as $value)
                <?php $count++; ?>
                <span>{{$count}}. </span>
                <a target='_blank' href="{{$base_url_f.'blogs/'.$value->post_cat->slug.'/'.$value->slug.'/'.$value->id}}">{{$value->title}}</a> | <span>{{$value->views_count}}</span>
                <br>
                @endforeach
            </div>
        </div>
    </div>






    <!-- end analytic  -->
    <div class="card">
        @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
        @endif
        <div class="card-header font-weight-bold">
            ĐƠN HÀNG MỚI
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Mã đơn hàng</th>
                        <th scope="col">Khách hàng</th>
                        <th scope="col">Giá trị</th>
                        <th scope="col">Trạng thái</th>
                        <th scope="col">Thời gian tạo</th>
                        <th scope="col">Thời gian cập nhật</th>
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

                        <td>{{$t}}</td>
                        <td>{{$order->code_bill}}</td>
                        <td>
                            {{$order->customer->name}} <br>
                            {{$order->customer->phone_number}}
                        </td>
                        <td>{{number_format($order->final_total,0,',','.')}}$</td>
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
                        <td>{{$order->updated_at}}</td>
                        <td>
                            @can('dashboard-detail')
                            <a href="{{route('dashboard.order.detail',$order->id)}}">chi tiết</a>
                            @endcan
                        </td>
                        <td>
                            @can('dashboard-print')

                            <a href="{{route('dashboard.order.print',$order->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit" target="_blank"><i class="fa fa-print"></i></a>

                            @endcan


                            @can('dashboard-delete')
                            <a href="{{route('dashboard.order.delete',$order->id)}}" class="btn btn-danger btn-sm rounded-0 text-white" onclick="return confirm('bạn có chắc chắn muốn xóa đơn hàng này không?')" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>

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
            {{$orders->links()}}
        </div>
    </div>

</div>
@endsection