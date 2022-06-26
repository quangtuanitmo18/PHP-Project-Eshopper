<?php $base_url = config('app.base_url');
$base_url_f = config('app.base_url_f');
?>
@extends('layouts.master')


@section('title')
<title>Eshopper</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('home/home.css')}}">
<link rel="stylesheet" href="{{asset('menu/menu.css')}}">
<link rel="stylesheet" href="{{asset('cart/checkout/checkout.css')}}">


@endsection

@section('js')
<script src="{{asset('home/home.js')}}"></script>
<script src="{{asset('menu/menu.js')}}"></script>


<script>
    $(document).ready(function() {
        $('.choose').change(function() {
            var id = $(this).val();
            var name_id = $(this).attr('id');

            var result = "";
            if (name_id == "city") {
                result = "district";
            }
            if (name_id == "district") {
                result = "ward_street";
            }

            var _token = $('input[name="_token"]').val();
            $.ajax({

                type: "POST",

                url: "{{ route('Fee_shipping.select_delivery') }}",

                data: {
                    id: id,
                    name_id: name_id,
                    _token: _token
                },
                success: function(data) {

                    if (name_id == "city") {
                        $('.district').html(data['output_city']);
                        $('.ward_street').html(data['output_district']);

                    } else if (name_id = "district") {
                        $('.ward_street').html(data['output_district']);
                    }
                }

            });
        });
    });


    $(document).ready(function() {
        $(".ward_street").blur(function() {
            var province_id = $(".city").val();
            var district_id = $(".district").val();
            var ward_id = $(".ward_street").val();
            var _token = $('input[name="_token"]').val();
            //alert(_token);

            $.ajax({

                type: "POST",

                url: "{{ route('Fee_shipping.charge_shipping') }}",

                data: {
                    province_id: province_id,
                    district_id: district_id,
                    ward_id: ward_id,
                    _token: _token
                },
                success: function(data) {
                    console.log(data);

                    $('.fee_shipping').text(data['fee_shipping_value']);
                    $('.final_total').text("$" + " " + data['total']);
                }

            });
        });
    });
</script>
<script src="https://www.paypalobjects.com/api/checkout.js"></script>

<script>
    $(document).ready(function() {
        var total = $("#total_price").val();

        paypal.Button.render({
            // Configure environment
            env: 'sandbox',
            client: {
                sandbox: 'AeXDqsYQN82v2iSuFWTH3VEGnZR91xQ9AjWyg3ApbahpEPxEaZtPS1IY7jSuuQnaDmG60S2dH0TO0DPJ',
                production: 'demo_production_client_id'
            },
            // Customize button (optional)
            locale: 'en_US',
            style: {
                size: 'small',
                color: 'gold',
                shape: 'pill',
            },
            // Enable Pay Now checkout flow (optional)
            commit: true,
            // Set up a payment

            payment: function(data, actions) {
                return actions.payment.create({
                    transactions: [{
                        amount: {
                            total: total,
                            currency: 'USD'
                        }
                    }]
                });
            },
            // Execute the payment
            onAuthorize: function(data, actions) {
                return actions.payment.execute().then(function() {
                    // Show a confirmation message to the buyer
                    // var currentLocation = window.location;

                    // window.location.href = "{{$base_url_f}}" + 'order/add';
                    var province_id = $("#city").val();
                    var district_id = $('#district').val();
                    var ward_id = $('#ward_street').val();
                    var phone_number = $('#phone_number').val();
                    var name = $("#name").val();
                    var email = $("#email").val();
                    var _token = $('input[name="_token"]').val();
                    var payment = 1;

                    $.ajax({

                        type: "POST",

                        url: "{{ route('order.add') }}",

                        data: {
                            city: province_id,
                            district: district_id,
                            ward_street: ward_id,
                            payment: payment,
                            phone_number: phone_number,
                            name: name,
                            email: email,
                            _token: _token
                        },
                        success: function(data) {
                            window.alert('Your order has been successfully, please check your email address!');

                            window.location.href = "{{$base_url_f}}" + 'order/purchase_order';
                        }
                    });
                });
            }
        }, '#paypal-button');
    });
</script>

<script>

</script>



@endsection


@section('content')

<body>



    <section>
        <div class="container">
            <div class="row">
                <section id="cart_items">
                    <div class="container">
                        <div class="breadcrumbs">
                            <ol class="breadcrumb">
                                <li><a href="#">Trang chủ</a></li>
                                <li class="active">Thanh toán</li>
                            </ol>
                        </div>
                        <!--/breadcrums-->




                        @if(session('status'))
                        <div class="alert alert-success">
                            {{session('status')}}
                        </div>
                        @endif
                        <form class="form-main" action="{{route('order.add')}}" method="post">
                            @csrf
                            <div class="shopper-informations">
                                <div class="row">

                                    <div class="col-sm-5 clearfix">
                                        <div class="bill-to">
                                            <p>Thông tin đơn hàng: </p>
                                            <div class="form-one">

                                                <input name="name" id="name" type="text" placeholder="Họ và tên (*)" value="{{Auth::user()->name}}" required>
                                                @error('name')
                                                <small class="text-danger name_customer">{{$message}}</small>
                                                @enderror

                                                <input name="email" id="email" type="email" placeholder="Email (*)" value="{{Auth::user()->email}}" required>
                                                @error('email')
                                                <small class="text-danger email_customer">{{$message}}</small>
                                                @enderror

                                                <!-- <input name="address" id="address" type="text" placeholder="Address*" value="{{Auth::user()->address }}">
                                                @error('address')
                                                <small class="text-danger">{{$message}}</small>
                                                @enderror -->

                                                <input name="phone_number" id="phone_number" type="text" placeholder="Số điện thoại (*)" value="{{Auth::user()->phone_number}}" required>
                                                @error('phone_number')
                                                <small class="text-danger phone_number_customer">{{$message}}</small>
                                                @enderror
                                            </div>


                                            <div class="form-two">


                                                <select class="choose city" id="city" name="city" style="margin-bottom:10px; height:40px;" required>
                                                    <option value="">---Tỉnh/Thành Phố---</option>
                                                    @foreach($provinces as $province)
                                                    <option value="{{$province->id}}">{{$province->name}}</option>
                                                    @endforeach

                                                </select>
                                                @error('city')
                                                <small class="text-danger city_customer">{{$message}}</small>
                                                @enderror

                                                <select class="choose district" id="district" name="district" style="margin-bottom:10px; height:40px;" required>
                                                    <option selected value="">---Quận/Huyện---</option>

                                                </select>
                                                @error('district')
                                                <small class="text-danger district_customer">{{$message}}</small>
                                                @enderror

                                                <select class="ward_street" id="ward_street" name="ward_street" style="margin-bottom:10px; height:40px;" required>
                                                    <option selected value="">---Xã/Phường---</option>

                                                </select>
                                                @error('ward_street')
                                                <small class="text-danger ward_customer">{{$message}}</small>
                                                @enderror

                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="order-message">
                                            <p>Nội dung đơn hàng</p>
                                            <textarea name="message" id="message" placeholder="Ghi chú về đơn đặt hàng của bạn, ghi chú đặc biệt khi giao hàng" rows="16">{{old('message')}}</textarea>
                                            @error('message')
                                            <small class="text-danger">{{$message}}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="review-payment">
                                <h2>Xem lại & Thanh toán</h2>
                            </div>

                            <div class="table-responsive cart_info">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr class="cart_menu">
                                            <td class="image">Sản phẩm</td>
                                            <td class="description"></td>
                                            <td class="price">Đơn giá</td>
                                            <td class="quantity">Số lượng</td>
                                            <td class="total">Thành tiền</td>
                                        </tr>
                                    </thead>
                                    <tbody>
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

                                                    <p style="width:50px; text-align: center">{{$row->qty}}</p>
                                                </div>
                                            </td>
                                            <td class="cart_total">
                                                <p class="cart_total_price">$ {{number_format($row->qty * $row->price , 1, ',', ' ')}}</p>
                                            </td>

                                        </tr>
                                        @endforeach


                                        <td colspan="3">&nbsp;</td>
                                        <td colspan="4">
                                            <table class="table table-condensed total-result">
                                                <?php
                                                $total = 0;
                                                $discount = 0;
                                                foreach (Cart::content() as $row) {
                                                    $total += $row->price * $row->qty;
                                                }
                                                ?>
                                                <tr>
                                                    <td>Tổng tạm tính</td>
                                                    <td>$ {{number_format(Cart::total(), 1, ',', ' ')}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Thuế</td>
                                                    <td>$ {{number_format(Cart::tax(), 1, ',', ' ')}}</td>
                                                </tr>
                                                <tr>
                                                    <td>Giảm giá </td>
                                                    @if(session()->get('code'))

                                                    @if(session()->get('condition')==0)
                                                    <?php
                                                    $discount = ($total * session()->get('value')) / 100;
                                                    ?>
                                                    <td>{{session()->get('value')}} % - $ {{$discount}}</td>
                                                    @elseif(session()->get('condition')==1)
                                                    <?php
                                                    $discount = session()->get('value');
                                                    ?>
                                                    <td>$ {{number_format(session()->get('value'), 1, ',', ' ')}}</td>
                                                    @endif


                                                    @else
                                                    <td>$ 0.0</td>

                                                    @endif


                                                </tr>

                                                <tr class="shipping-cost">
                                                    <td>Phí vận chuyển</td>
                                                    <?php

                                                    $fee_shipping_value = session()->get('fee_shipping_value');
                                                    ?>
                                                    <td>$ <span style="color:black; font-weight:400;" class="fee_shipping">{{number_format($fee_shipping_value, 1, ',', ' ')}}</span></td>
                                                </tr>



                                                <tr>
                                                    <td>Tổng</td>
                                                    <?php

                                                    $total = $total + Cart::tax() - $discount + $fee_shipping_value;
                                                    request()->session()->put('paypal_total', $total);

                                                    ?>
                                                    <input type="hidden" name='total_price' id='total_price' value="{{$total}}">

                                                    @if($total>0)

                                                    <td> <span class="final_total">$ {{number_format($total, 1, ',', ' ')}}</span></td>
                                                    @else
                                                    <?php $total = 0; ?>
                                                    <td> <span class="final_total">$ 0.0</span></td>
                                                    @endif


                                                </tr>
                                            </table>
                                        </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div>






                                <div class="payment_method">

                                    <button style="margin-left:0; background:#FE980F!important; margin-top:0px; margin-right:15px;" type='submit' class="btn btn-default check_out" id="payment_on_delivery" name='payment' value="0">Payment on delivery</button>
                                    <button style="margin-left:0; background:#FE980F!important;  margin-top:0px; margin-right:15px;" type='submit' class="btn btn-default check_out" id="payment_on_vnpay" name='payment' value="2">Payment by VNpay</button>

                                    <!-- <div style='margin-top:15px;' id="paypal-button"></div> -->

                                    <button style="margin-left:0; background:#FE980F!important;  margin-top:0px; margin-right:15px;" type='submit' class="btn btn-default check_out" id="payment_on_vnpay" name='payment' value="3">Payment by PayPal</button>
                                    @if(\Session::has('error'))
                                    <div class=" alert alert-danger">{{ \Session::get('error') }}
                                    </div>
                                    {{ \Session::forget('error') }}
                                    @endif
                                    @if(\Session::has('success'))
                                    <div class="alert alert-success">{{ \Session::get('success') }}</div>
                                    {{ \Session::forget('success') }}
                                    @endif

                                </div>

                            </div>




                            <!-- <input style="margin-left:0; background:#FE980F!important;" type="submit" class="btn btn-default check_out" name="btn_update" value="Submit"> -->



                        </form>




                    </div>
                </section>
                <!--/#cart_items-->

            </div>
        </div>
    </section>





</body>

@endsection

</html>

{{-- --}}