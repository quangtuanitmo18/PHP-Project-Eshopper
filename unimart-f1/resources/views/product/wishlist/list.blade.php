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
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> -->

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
                items: 2,
                nav: true
            },
            600: {
                items: 3,
                nav: false
            },
            1000: {
                items: 4,
                nav: true,
                loop: false
            }
        }
    })
</script>
<script>
    function view() {
        if (localStorage.getItem('data') != null) {
            var data = JSON.parse(localStorage.getItem('data'));
            for (i = 0; i < data.length; i++) {
                var id = data[i].id;
                var name = data[i].name;
                var price = data[i].price;
                var thumbnail = data[i].thumbnail;
                var url = data[i].url;
                $("#wishlist").append(
                    "<input type='hidden'" + "value='" + id + "'" + "class='cart_product_id_" + id + "'" + ">" +
                    "<div class='col-sm-3'" + "style='display:block;'>" +
                    "<div class='product-image-wrapper'>" +
                    "<div class='single-products'>" +
                    "<div class='productinfo text-center'>" +
                    "<a id='url_product_" + id + "'" + " href='" + url + "'" + ">" +

                    "<img style='width:207px; height:207px;' id='thumbnail_product_" + id + "'" + "src='" + thumbnail + "'" + "alt=''" + "/>" +

                    "<h2>$" + price + "</h2>" +

                    "<p>" + name + "</p>" +

                    "</a>" +
                    "<button style='margin-bottom:20px;' type='button' class='btn btn-default add-to-cart'" + "data-id_product='" + id + "'" + "name='add-to-cart'><i class='fa fa-shopping-cart'></i>Thêm vào giỏ hàng</button><br>" +
                    "<a style='color:red;' type='button' class='delete-wishlist' " + "data-id_product='" + id + "'" + "name='add-to-cart'>Xóa sản phẩm</a><br>" +

                    "</div>" +

                    "</div>" +

                    "<div class='choose'" + ">" +
                    "<ul class='nav nav-pills nav-justified'>" +

                    "</ul>" +
                    "</div>" +
                    "</div>" +
                    "</div>"
                );
            }
        }
    }
    view();
</script>
<script>
    $(".delete-wishlist").on('click', function() {
        var id_product = $(this).data('id_product');
        if (confirm('Bạn có chắc chắc muốn xóa sản phẩm này khỏi danh mục yêu thích?')) {
            var data = JSON.parse(localStorage.getItem('data'));
            for (i = 0; i < data.length; i++) {
                if (data[i].id == id_product) {
                    var tmp1 = data.slice(0, i);
                    var tmp2 = data.slice(i + 1, data.length);
                    var new_data = tmp1.concat(tmp2);
                }

            }
            localStorage.setItem('data', JSON.stringify(new_data));
            location.reload();
        }


    });
</script>

@endsection

@section('content')

<body>


    @include('home.components.slider')

    <section>
        <div class="container">
            <div class="row">


                <div class="col-sm-12 padding-right">
                    <?php $base_url = config('app.base_url');
                    ?>
                    <div class="features_items" style="min-height:907px;">
                        <!--features_items-->
                        <h2 class="title text-center">Sản phẩm yêu thích</h2>
                        <div id='wishlist'>

                        </div>

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