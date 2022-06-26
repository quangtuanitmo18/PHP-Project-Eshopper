<?php $base_url = config('app.base_url');
?>

@extends('layouts.master')


@section('title')
<title>Eshopper</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('home/home.css')}}">


@endsection

@section('js')
<script src="{{asset('home/home.js')}}"></script>
<script src="{{asset('menu/menu.js')}}"></script>
<script>
    $(document).ready(function() {
        $('input.num_product').change(function() {

            var id = $(this).attr('data-rowId');
            var number_product = $(this).val();
            var _token = $('input[name="_token"]').val();

            $.ajax({

                type: "POST",
                url: "{{ route('cart.change_number_product_ajax') }}",
                data: {
                    id: id,
                    number_product: number_product,
                    _token: _token
                },
                success: function($response) {

                    $("#sub_total_" + $response.id).text("$ " + $response.sub_total);
                    $("#total_price span").text("$ " + $response.total);

                    $('#discount_amount').text("$ " + $response.discount);
                    $("#total_final_price span").text("$ " + $response.total_after_discount);

                }
            });
        });
    });
</script>

@endsection

@section('content')

<body>

    <section>
        <div class="container">
            <div class="row">

                <form action="{{route('cart.update')}}" method="post">
                    @csrf
                    <section id="cart_items">
                        <div class="container">
                            <div class="breadcrumbs">
                                <ol class="breadcrumb">
                                    <li><a href="{{url("")}}">Trang chủ </a></li>
                                    <li class="active">Giỏ hàng</li>
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
                                            <td class="image">Sản phẩm</td>
                                            <td class="description"></td>
                                            <td class="price">Đơn giá</td>
                                            <td class="quantity">Số lượng</td>
                                            <td class="total">Thành tiền</td>
                                            <td></td>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(Cart::count() > 0)
                                        @foreach(Cart::content() as $row)
                                        <tr>
                                            <td class="cart_product">
                                                <a href=""><img style='width:150px; height:auto;' src="{{$base_url.$row->options->thumbnail}}" alt=""></a>
                                            </td>
                                            <td class="cart_description">
                                                <h4><a href="">{{$row->name}}</a></h4><br>
                                                <span>Web ID: {{$row->id}}</span><br>
                                                <span>Danh mục: {{$row->options->product_cat}}</span><br>
                                                <span>Thương hiệu: {{$row->options->product_brand}}</span><br>

                                            </td>
                                            <td class="cart_price">
                                                <p>$ {{number_format($row->price, 1, ',', ' ')}}</p>
                                            </td>
                                            <td class="cart_quantity">
                                                <div class="cart_quantity_button">

                                                    <input class="num_product" type="number" data-rowId="{{$row->rowId}}" min=1 max="{{$row->options->max_qty}}" name="qty[{{$row->rowId}}]" style="width:50px; text-align: center" value="{{$row->qty}}">
                                                </div>
                                            </td>
                                            <td class="cart_total">
                                                <p id="sub_total_{{$row->rowId}}" class="cart_total_price">$ {{number_format($row->qty * $row->price , 1, ',', ' ')}}</p>
                                            </td>
                                            <td class="cart_delete">
                                                <a href="{{route("cart.remove",$row->rowId)}}" onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này khỏi đơn hàng?')" class="cart_quantity_delete"><i class="fa fa-times"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                        @else
                                        <tr>
                                            <td>
                                                <p>
                                                    Không có sản phẩm nào trong giỏ hàng,
                                                    vui lòng quay lại cửa hàng<a href="{{url("/")}}">tại đây</a>
                                                </p>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </section>
                    <!--/#cart_items-->
                    @if(Cart::count() > 0)
                    <a href="{{url('cart/destroy')}}" onclick="return confirm('Bạn có chắc chắn muốn xóa tất cả sản phẩm khỏi giỏ hàng?')" class="btn btn-default update">Xóa giỏ hàng</a>

                    <input type="submit" class="btn btn-default update" name="btn_update" value="Cập nhật giỏ hàng">
                    @endif
                </form>
                @if(Cart::count() > 0)
                <section id="do_action">
                    <div class="container">
                        <div class="heading">
                            <h3>Mã giảm giá</h3>
                            <div class="row">
                                <div class="col-md-6">
                                    @if(session('status_coupon'))
                                    <div class="alert {{session('color')}}">
                                        {{session('status_coupon')}}
                                    </div>
                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-sm-6">
                                <form method="POST" action="{{route('order.check_coupon')}}">
                                    @csrf
                                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                    <div class="chose_area">
                                        <ul class="user_option">
                                            <p>Nhập mã giảm giá</p>
                                        </ul>

                                        <ul class="user_info">
                                            <li class="single_field">
                                                <label>Sự kiện:</label>
                                                <select name="event">
                                                    <option value="">---Chọn sự kiện---</option>

                                                    @foreach ($events as $event )

                                                    <option value="{{$event->id}}" {{session()->get('event_id')==$event->id?"selected='selected'":""}}>{{$event->name}}</option>

                                                    @endforeach
                                                </select>

                                            </li>

                                            <li class="single_field zip-field">
                                                <label>Mã:</label>
                                                <input type="text" class="form-control" name="coupon" value="{{session()->get('code')}}">
                                            </li>
                                        </ul>
                                        @if(session()->get('code'))
                                        <a onclick="return confirm('Are you sure you want to delete the discount code?')" class="btn btn-default update" href="{{url("order_coupon/delete")}}">Delete code</a>
                                        @endif
                                        <input type="submit" class="btn btn-default check_out" name="submit" value="Nhập">
                                    </div>
                                </form>

                            </div>
                            <div class="col-sm-6">
                                <div class="total_area">
                                    <ul>
                                        <?php
                                        $total = 0;
                                        foreach (Cart::content() as $row) {
                                            $total += $row->price * $row->qty;
                                        }
                                        ?>
                                        <li id="total_price">Tổng tạm tính <span>$ {{number_format($total, 1, ',', ' ')}}</span></li>
                                        <li>Thuế <span>$ {{number_format(Cart::tax(),1,',','.')}}</span></li>

                                        <li>Mã giảm giá
                                            @if(session()->get('code'))
                                            @if(session()->get('condition')==0)
                                            <?php
                                            $discount = ($total * session()->get('value')) / 100;
                                            ?>
                                            <span>{{session()->get('value')}}% - <span id="discount_amount"> ${{$discount}}</span></span>

                                            @elseif(session()->get('condition')==1)
                                            <?php
                                            $discount = session()->get('value');
                                            ?>
                                            <span id="discount_amount">$ {{number_format(session()->get('value'), 1, ',', ' ')}}</span>
                                            @endif
                                            <p style="margin:0;">-- Mã: {{session()->get('code')}}</p>
                                            <p class="m-0">-- Sự kiện: {{$event->name}} </p>
                                            @else
                                            <span id="discount_amount">$ 0.0</span>
                                            @endif
                                        </li>
                                        <?php
                                        ?>
                                        <li id="total_final_price">Tổng
                                            <span>

                                                @if(!empty($discount))
                                                @if($total-$discount>0)

                                                $ {{number_format($total-$discount, 1, ',', ' ')}}
                                                @else
                                                $ 0.0
                                                @endif
                                                @else
                                                $ {{number_format($total, 1, ',', ' ')}}
                                                @endif
                                            </span>
                                        </li>

                                    </ul>
                                    <a class="btn btn-default check_out" href="{{route("cart.checkout")}}">Thanh Toán</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!--/#do_action-->
                @endif

            </div>
        </div>
    </section>
</body>
@endsection