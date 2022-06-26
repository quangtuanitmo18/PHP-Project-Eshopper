<?php $base_url = config('app.base_url');
?>

@extends('layouts.master')


@section('title')
<title>Eshopper</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('home/home.css')}}">
<style>
    .modal-ku {
        margin: 140px auto;
        width: 1000px;
    }
</style>

@endsection

@section('js')
<script src="{{asset('home/home.js')}}"></script>
<script src="{{asset('menu/menu.js')}}"></script>

<script>
    $(document).ready(function() {
        $(document).on('click', '.order_detail', function() {
            var id_order = $(this).data('id_order');
            var _token = $('input[name="_token"]').val();



            $.ajax({
                url: "{{ route('order.detail') }}",
                type: "POST",

                data: {
                    'id_order': id_order,
                    '_token': _token
                },
                dataType: 'JSON',

                success: function(data) {
                    $('#order_code_bill').html(data.order['code_bill']);
                    $('#order_total').html(data.order_total);
                    $('#order_discount').html(data.show_discount);
                    $('#order_date').html(data.order_date);
                    $('#fee_shipping').html(data.fee_shipping);
                    $('#final_total').html(data.final_total);
                    $('#order_status').html(data.order_status);
                    $("#order_detail_show").html(data.order_detail_show);
                    $("#payment").html(data.payment[data.order['payment']]);

                }
            });
        });

    });
</script>
@endsection


@section('content')

<body>


    {{-- @include('home.components.slider') --}}



    <section>
        <div class="container">
            <div class="row">

                <form action="{{route('cart.update')}}" method="post">
                    @csrf
                    <section id="cart_items">
                        <div class="container">
                            <div class="breadcrumbs">
                                <ol class="breadcrumb">
                                    <li><a href="{{url("")}}">Trang chủ</a></li>
                                    <li class="active">Orders</li>
                                </ol>
                            </div>
                            @if(session('status'))
                            <div class="alert alert-success">
                                {{session('status')}}
                            </div>
                            @endif
                            @if(session('status_danger'))
                            <div class="alert alert-danger">
                                {{session('status_danger')}}
                            </div>
                            @endif
                            <div class="table-responsive cart_info">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr class="cart_menu">

                                            <td class="">#</td>

                                            <td class="image">Mã đơn hàng</td>
                                            <td class="description">Tổng tiền</td>
                                            <td class="price">Trạng thái</td>
                                            <td class="quantity">Ngày mua hàng</td>
                                            <td class="total">Thao tác</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    @if($purchase_order->count()>0)

                                    <tbody>
                                        <?php
                                        $t = 0;
                                        ?>
                                        @foreach($purchase_order as $row)
                                        <?php
                                        $t++;
                                        ?>
                                        <tr>
                                            <td>{{$t}}</td>
                                            <td class="cart_description">
                                                <h4><a href="">{{$row->code_bill}}</a></h4>
                                                <span>Web ID: {{$row->id}}</span><br>

                                            </td>

                                            <td class="cart_total">
                                                <p class="cart_total_price">{{number_format($row->final_total , 1, ',', ' ')}}$</p>
                                            </td>

                                            @php
                                            $class_badge="";
                                            if($row->status==0){
                                            $class_badge="#007bff";
                                            }
                                            else if($row->status==1){
                                            $class_badge="#ffc107";
                                            }
                                            else if($row->status==2){
                                            $class_badge="#28a745";
                                            }
                                            else if($row->status==3){
                                            $class_badge="#343a40";
                                            }
                                            @endphp
                                            <td><span style="background:{{$class_badge}};" class="badge">{{$order_statuses[$row->status]}}</span></td>

                                            <td>{{$row->created_at}}</td>

                                            <td class="cart_cancel">
                                                @if($row->status!=3)
                                                <a href="{{route("order.cancel",$row->id)}}" style="color:#6f767c;" onclick="return confirm('Bạn có chắc chắn muốn hủy đơn hàng này không?')" class="cart_quantity_delete"><i class="fa fa-times-circle" aria-hidden="true"></i>
                                                    Hủy</a>
                                                @endif
                                                <p style='color:black' class="order_detail" data-id_order="{{$row->id}}" data-toggle="modal" data-target="#order_detail"><a href=""><i class="fa fa-list" aria-hidden="true"></i>
                                                        Chi tiết</a></p>

                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                    @else
                                    <p>Bạn chưa có đơn hàng nào.</p>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </section>
                    <!--/#cart_items-->
                    {{$purchase_order->links()}}


                </form>

            </div>
        </div>
    </section>

    <div class="modal fade" id="order_detail" tabindex="-1" role="dialog" aria-labelledby="order_detail" aria-hidden="true">
        <div class="modal-dialog modal-ku modal-lg modal-dialog-centered" role="document">
            <div class="modal-content" style=" overflow:auto; height:632px; border-radius: 0px!important;background-clip: border-box;">
                <div class="modal-header" style="position:fixed; ;width: 100%;background: #FE980F;z-index: 3;padding-bottom: 0px!important; border-radius: 0px!important;background-clip: border-box;">
                    <h5 class="modal-title"> Chi tiết đơn hàng</h5>
                    <button type="button" style="    position: relative;top: -12px;" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>

                </div>



                <div class="modal-body" style="top: 40px;position: relative;padding: 20px;">

                    <section class="h-100 gradient-custom">
                        <div class="container py-5 h-100">
                            <div class="row d-flex justify-content-center align-items-center h-100">
                                <div class="col-lg-10 col-xl-8">
                                    <div class="card" style="border-radius: 10px;">
                                        <div class="card-header px-4 py-5">
                                            <h5 class="text-muted mb-0">Cảm ơn bạn đã mua hàng tại Eshopper, <span style="color: #a8729a;">{{Auth()->user()->name}}</span>!</h5>
                                        </div>
                                        <div class="card-body p-4">
                                            <div class="d-flex justify-content-between align-items-center mb-4">
                                                <p class="lead fw-normal mb-0" style="color: #a8729a;">Biên nhận</p>
                                                <p class="small text-muted mb-0">Mã đơn hàng : <span id='order_code_bill'></span> </p>
                                                <p class="small text-muted mb-0" id="payment"> </p>

                                                <div id='order_status'></div>

                                            </div>

                                            <div id="order_detail_show" style='margin-top:20px;'>

                                            </div>




                                            <div class="d-flex justify-content-between pt-2">
                                                <p class="text-muted mb-0"><span class="fw-bold me-4">Tổng tạm tính $ <span id='order_total'></span></span></p>
                                            </div>

                                            <div class="d-flex justify-content-between pt-2">
                                                <p class="text-muted mb-0"><span class="fw-bold me-4">Giảm giá $</span> <span id='order_discount'> </span></p>
                                            </div>

                                            <div class="d-flex justify-content-between">
                                                <p class="text-muted mb-0">Ngày mua hàng : <span id='order_date'></span></p>
                                                <p class="text-muted mb-0"><span class="fw-bold me-4">Thuế $</span> 0,0</p>
                                            </div>

                                            <div class="d-flex justify-content-between mb-5">
                                                <p class="text-muted mb-0"><span class="fw-bold me-4">Phí vận chuyển $</span> <span id='fee_shipping'></span></p>
                                            </div>
                                        </div>
                                        <div class="card-footer border-0 px-4 py-5" style="background-color: #a8729a; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                                            <h5 style='background-color:snow;' class="d-flex align-items-center justify-content-end text-white text-uppercase mb-0">Tổng: <span class="h2 mb-0 ms-2">$ <span id='final_total'> </span> </span></h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <!--/product-information-->
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">

                </div>
            </div>





        </div>



    </div>
    <!-- <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
    </div> -->
    </div>
    </div>
    </div>




</body>

@endsection