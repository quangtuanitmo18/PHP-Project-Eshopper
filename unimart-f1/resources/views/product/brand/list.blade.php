<?php $base_url = config('app.base_url') ?>
@extends('layouts.master')
@section('title')
<title>Eshopper</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('home/home.css')}}">
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.1/themes/base/jquery-ui.css">
<style>
    .modal-ku {
        margin: 140px auto;
        width: 850px;
    }
</style>
@endsection

@section('js')
<script src="{{asset('home/home.js')}}"></script>
<script src="{{asset('menu/menu.js')}}"></script>
<script src="https://code.jquery.com/ui/1.13.1/jquery-ui.js"></script>
<script>
    $(document).ready(function() {

        $('#sort_product').change(function() {
            var validate_sort = $(this).val();

            var url = window.location.href;

            if (url.indexOf("?") != -1) {
                var resUrl = url.split("?");
                if (typeof window.history.pushState == 'function') {
                    window.history.pushState({}, "Hide", resUrl[0]);
                }
                var url_final = resUrl[0] + '?sort_product=' + validate_sort;
                window.location = url_final;
            } else {
                window.location = url + "?sort_product=" + validate_sort;
            }
        });
    })
    $(function() {
        $min_price_range = $("#min_price_range").val();
        $max_price_range = $("#max_price_range").val();
        $min_price = $("#min_price").val();
        $max_price = $("#max_price").val();

        $("#slider-range").slider({
            range: true,
            min: 1,
            max: $max_price_range,
            step: 10,
            values: [$min_price, $max_price],
            slide: function(event, ui) {
                $("#amount").val("$" + ui.values[0] + " - $" + ui.values[1]);
                $('#start_price').val(ui.values[0]);
                $('#end_price').val(ui.values[1]);
            }
        });
        $("#amount").val("$" + $("#slider-range").slider("values", 0) +
            " - $" + $("#slider-range").slider("values", 1));
    });
</script>
<script>
    $(document).ready(function() {
        $(document).on('click', '.quick_view', function() {
            var id_product = $(this).data('id_product');



            var form_data = new FormData();

            form_data.append("id_product", id_product);
            $.ajax({
                url: "{{ route('product.quick_view') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form_data,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#product_name').html(data.output_product_name);
                    $('#product_image').html(data.output_product_image);
                    $('#product_id').html(data.output_product_id);
                    $('#product_detail').html(data.ouput_product_detail);
                    $('#product_availability').html(data.output_product_availability);
                    $('#product_category').html(data.output_product_category);
                    $('#product_brand').html(data.output_product_brand);

                    // $('#demo_video').html(data.output_video);
                    $('#product_content').html(data.output_product_content);
                    $('#form_quick_view').attr('action', data.url_form_quick_view)
                }
            });
        });
        $('button.add-to-cart').click(function() {
            var id = $(this).data('id_product');
            var cart_product_id = $('.cart_product_id_' + id).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({

                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: "{{ route('cart.add_ajax') }}",
                data: {
                    cart_product_id: cart_product_id,
                    _token: _token
                },
                success: function() {
                    // swal({
                    // 		title: "You have successfully added the product to your cart",
                    // 		text: "Do you want to go to the shopping cart page?",
                    // 		type: "success",
                    // 		showCancelButton: true,
                    // 		confirmButtonClass: 'btn-warning',
                    // 		confirmButtonText: 'yes, go there!',
                    // 		closeOnConfirm: true,
                    // 	},
                    // 	function() {
                    // 		window.location.href = "{{url('cart/show')}}"
                    // 	});
                    swal({
                            title: "Bạn đã thêm sản phẩm vào giỏ hàng thành công!",
                            type: "success",

                            closeOnConfirm: true,
                        },
                        function() {
                            window.location.href = "{{route('cart.show')}}"
                        });
                }
            });
        });
    });
</script>
<script>
    function add_wishlist(clicked_id) {

        var id = clicked_id;
        var name = $("#name_product_" + id).val();
        var price = $("#price_product_" + id).val();

        var thumbnail = document.getElementById('thumbnail_product_' + id).src;
        var url = document.getElementById('url_product_' + id).href;

        var newIitem_wishproduct = {
            'id': id,
            'name': name,
            'price': price,
            'thumbnail': thumbnail,
            'url': url
        }
        if (localStorage.getItem('data') == null) {
            localStorage.setItem('data', '[]');
        }
        var old_data = JSON.parse(localStorage.getItem('data'));
        var matches = $.grep(old_data, function(object) {
            return object.id == id;
        });
        if (matches.length) {
            alert('Sản phẩm đã tồn tại trong danh sách yêu thích');
        } else {
            old_data.push(newIitem_wishproduct);
            alert('Bạn đã thêm sản phẩm vào danh sách yêu thích thành công!')
        }
        localStorage.setItem('data', JSON.stringify(old_data));
    }
</script>

@endsection


@section('content')

<body>


    @include('home.components.slider')



    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    @include('components.sidebar')
                </div>

                <div class="col-sm-9 padding-right">
                    <div class="features_items">
                        <!--features_items-->
                        <h2 class="title text-center">Thương Hiệu - {{$product_brand->name}}</h2>
                        <div class="row">
                            <div class="" style='margin-left:15px;'>
                                <input type="hidden" name='min_price' id='min_price' value="{{$min_price}}">
                                <input type="hidden" name='min_price_range' id='min_price_range' value="{{$min_price_range}}">
                                <input type="hidden" name='max_price' id='max_price' value="{{$max_price}}">
                                <input type="hidden" name='max_price_range' id='max_price_range' value="{{$max_price_range}}">

                                <form action="" method="get">

                                    <?php
                                    if (isset($_GET['sort_product'])) {
                                        $url = $_GET['sort_product'];
                                    } else {
                                        $url = '';
                                    }
                                    ?>
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <div class="sort_product">
                                                <select name="sort_product" id="sort_product">
                                                    <option value="none" {{$url==''?"selected":""}}>--Chọn bộ lọc--</option>
                                                    <option value="price_from_low_to_high" {{$url=='price_from_low_to_high'?"selected":""}}>Giá từ thấp đến cao</option>
                                                    <option value="price_from_high_to_low" {{$url=='price_from_high_to_low'?"selected":""}}>Giá từ cao xuống thấp</option>
                                                    <option value="aA-zZ" {{$url=='aA-zZ'?"selected":""}}>aA - zZ</option>
                                                    <option value="zZ-aA" {{$url=='zZ-aA'?"selected":""}}>zZ - aA</option>

                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row" style='position:relative'>
                                        <div class="col-sm-6">
                                            <div class="filter_product">
                                                <p>
                                                    <label for="amount">Lọc giá:</label>
                                                    <input type="text" id="amount" readonly style="border:0; color:#f6931f; font-weight:bold;">
                                                    <input type="hidden" name='start_price' id='start_price' value='{{$min_price}}'>
                                                    <input type="hidden" name='end_price' id='end_price' value='{{$max_price}}'>
                                                </p>
                                                <div id="slider-range" style="margin-left:10px;"></div>
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <input style='position:absolute; top:13px;' type="submit" name='filter_price_product' id="filter_price_product" class=' btn btn-default add-to-cart' value='Lọc'>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        @foreach ($products as $product )
                        <input type="hidden" value="{{$product->id}}" class="cart_product_id_{{$product->id}}">
                        <a href="{{route("product.detail",['slug'=>$product->slug,'id'=>$product->id])}}">
                            <div class="col-sm-4">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <a id="url_product_{{$product->id}}" href="{{route('product.detail',['slug'=>$product->slug,'id'=>$product->id])}}">
                                                <img style='width:255px;height:255px;' id='thumbnail_product_{{$product->id}}' src="{{config('app.base_url').$product->thumbnail}}" alt="" />
                                                <h2>${{number_format($product->price, 1, ',', ' ')}}</h2>
                                                <input type="hidden" id='price_product_{{$product->id}}' value="{{number_format($product->price,1,',','')}}">
                                                <p>{{$product->name}}</p>
                                                <input type="hidden" id='name_product_{{$product->id}}' value="{{$product->name}}">
                                            </a>
                                            <button type="button" class="btn btn-default add-to-cart" data-id_product="{{$product->id}}" name="add-to-cart"><i class="fa fa-shopping-cart"></i>Thêm vào giỏ hàng</button>
                                        </div>

                                    </div>
                                    <div class="choose">
                                        <ul class="nav nav-pills nav-justified">
                                            <li id="{{$product->id}}" onclick="add_wishlist(this.id);"><a style="cursor:pointer;"><i class=" fa fa-plus-square"></i>Yêu thích</a></li>
                                            <li class="quick_view" data-id_product="{{$product->id}}" data-toggle="modal" data-target="#exampleModalCenter"><a href="#"><i class="fa fa-plus-square"></i>Xem nhanh</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </a>
                        @endforeach

                        {{$products->links()}}

                        @include('product.brand.components.quick_view')

                    </div>
                    <!--features_items-->
                </div>
            </div>
        </div>
    </section>






</body>

@endsection