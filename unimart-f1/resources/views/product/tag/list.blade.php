@extends('layouts.master')


@section('title')
<title>Eshopper</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('home/home.css')}}">
<link rel="stylesheet" href="{{asset('menu/menu.css')}}">
<link rel="stylesheet" href="{{asset('SweetAlert/SweetAlert.css')}}">
<link rel="stylesheet" href="{{asset('product/product_related/owl.carousel.min.css')}}">
<link rel="stylesheet" href="{{asset('product/product_related/owl.theme.default.min.css')}}">

<style>
    .owl-carousel .owl-nav {
        margin-top: 0px !important;
    }

    .owl-carousel .owl-nav button {
        margin-top: 0px !important;
    }

    .owl-item .single-products button {
        margin-bottom: 0px !important;
    }
</style>
@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script>

<script src="{{asset('home/home.js')}}"></script>
<script src="{{asset('menu/menu.js')}}"></script>
<script src="{{asset('SweetAlert/SweetAlert.js')}}"></script>


<script src="{{asset('product/product_related/owl.carousel.js')}}"></script>

<script>
    $(document).ready(function() {
        $('button.add-to-cart').click(function() {


            var id = $(this).data('id_product');
            var cart_product_id = $('.cart_product_id_' + id).val();
            var _token = $('input[name="_token"]').val();
            $.ajax({

                type: "POST",
                url: "{{ route('cart.add_ajax') }}",
                data: {
                    cart_product_id: cart_product_id,
                    _token: _token
                },
                success: function() {

                    swal({
                            title: "Bạn đã thêm sản phẩm vào giỏ hàng thành công!",
                            text: "Bạn có muốn đi tới trang giỏ hàng không?",
                            type: "success",
                            showCancelButton: true,
                            confirmButtonClass: 'btn-warning',
                            confirmButtonText: 'Có, đi tới!',
                            cancelButtonText: 'không',
                            closeOnConfirm: true,
                        },
                        function() {
                            window.location.href = "{{url('cart/show')}}"
                        });
                }

            });
        });
    });
</script>
<script>
    $('.owl-carousel').owlCarousel({
        loop: true,
        margin: 10,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: true
            },
            600: {
                items: 2,
                nav: false
            },
            1000: {
                items: 3,
                nav: true,
                loop: false
            }
        }
    })
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
                    <?php $base_url = config('app.base_url');
                    ?>
                    <div class="features_items" style="min-height:907px;">
                        <!--features_items-->
                        <h2 class="title text-center"> Tag - {{$tag->name}}</h2>
                        @if(count($product_tags) > 0)


                        <form>
                            @foreach ($product_tags as $item )
                            @csrf

                            <input type="hidden" value="{{$item->id}}" class="cart_product_id_{{$item->id}}">
                            <div class="col-sm-4">
                                <div class="product-image-wrapper">
                                    <div class="single-products">
                                        <div class="productinfo text-center">
                                            <a href="{{route('product.detail',['slug'=>$item->slug,'id'=>$item->id])}}">

                                                <img style="width:255px; height:255px;" src="{{$base_url.$item->thumbnail}}" alt="" />
                                                <h2>${{number_format($item->price, 1, ',', ' ')}}</h2>
                                                <p>{{$item->name}}</p>

                                            </a>
                                            <button type="button" class="btn btn-default add-to-cart" data-id_product="{{$item->id}}" name="add-to-cart"><i class="fa fa-shopping-cart"></i>Add to cart</button>
                                        </div>
                                        {{-- <div class="product-overlay">
                            <div class="overlay-content">
                                <h2>${{number_format($item->price, 1, ',', ' ')}}</h2>
                                        <p>{{$item->name}}</p>
                                        <a href="#" class="btn btn-default add-to-cart"><i class="fa fa-shopping-cart"></i>Thêm vào giỏ hàng</a>
                                    </div>
                                </div> --}}
                            </div>

                            <div class="choose">
                                <ul class="nav nav-pills nav-justified">
                                    <li><a href="#"><i class="fa fa-plus-square"></i>Yêu thích</a></li>
                                    <li><a href="#"><i class="fa fa-plus-square"></i>So sánh</a></li>
                                </ul>
                            </div>
                    </div>
                </div>
                @endforeach
                </form>

                @else
                <p>Không có sản phẩm nào.</p>

                @endif

            </div>
            <!--features_items-->

            @include('home.components.recommended_items')

        </div>
        </div>
        </div>
    </section>





</body>

@endsection

</html>