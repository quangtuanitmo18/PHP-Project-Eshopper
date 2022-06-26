<?php $base_url = config('app.base_url');
?>

<style>
    .modal-ku {
        margin-top: 140px;
        width: 850px;
    }
</style>
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

        $(document).on('click', '.add-to-cart-quick-view', function() {

            var id_product = $(this).data('id_product');
            var num_product = $('.num_product').val();
            var _token = $('input[name="_token"]').val();

            // $.ajax({
            //     url: "{{ route('cart.cart_quick_view') }}",
            //     type: "POST",
            //     headers: {
            //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            //     },
            //     data: form_data,
            //     dataType: 'JSON',
            //     contentType: false,
            //     cache: false,
            //     processData: false,
            //     success: function() {

            //         alert("You have successfully added the product to your cart!");
            //     }
            // });

            $.ajax({

                type: "POST",
                url: "{{ route('cart.cart_quick_view') }}",
                data: {
                    id_product: id_product,
                    num_product: num_product,
                    _token: _token
                },
                success: function() {
                    alert("Bạn đã thêm sản phẩm vào giỏ hàng thành công!");
                }

            });
        });
    });
</script>

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
            alert('Sản phẩm đã tồn tại trong danh sách yêu thích!');
        } else {
            old_data.push(newIitem_wishproduct);
            alert('Bạn đã thêm sản phẩm vào danh sách yêu thích thành công!')
        }
        localStorage.setItem('data', JSON.stringify(old_data));
    }
</script>
<?php

use App\Product;

$base_url = config('app.base_url');
?>
<div class="features_items" style="min-height:907px;">
    <!--features_items-->
    <h2 class="title text-center">
        @if($check==0)
        Sản phẩm nổi bật
        @else
        Tìm kiếm với từ khóa "{{request()->input('keyword')}}"
        @endif
    </h2>
    @if(count($product_display) > 0)

    <form>
        @foreach ($product_display as $item )

        @csrf

        <input type="hidden" value="{{$item->id}}" class="cart_product_id_{{$item->id}}">
        <div class="col-sm-4" style="display:block;">
            <div class="product-image-wrapper">
                <div class="single-products">
                    <div class="productinfo text-center">
                        <a id="url_product_{{$item->id}}" href="{{route('product.detail',['slug'=>$item->slug,'id'=>$item->id])}}">

                            <img style="width:255px; height:255px;" id='thumbnail_product_{{$item->id}}' src="{{$base_url.$item->thumbnail}}" alt="" />

                            <h2>${{number_format($item->price, 1, ',', ' ')}}</h2>
                            <input type="hidden" id='price_product_{{$item->id}}' value="{{number_format($item->price,1,',','')}}">
                            <p>{{$item->name}}</p>
                            <input type="hidden" id='name_product_{{$item->id}}' value="{{$item->name}}">

                        </a>
                        <button type="button" class="btn btn-default add-to-cart" data-id_product="{{$item->id}}" name="add-to-cart"><i class="fa fa-shopping-cart"></i>Thêm vào giỏ hàng</button>
                    </div>

                </div>

                <div class="choose">
                    <ul class="nav nav-pills nav-justified">
                        <li id="{{$item->id}}" onclick="add_wishlist(this.id);"><a style="cursor:pointer;"><i class=" fa fa-plus-square"></i>Yêu thích</a></li>
                        <li class="quick_view" data-id_product="{{$item->id}}" data-toggle="modal" data-target="#exampleModalCenter"><a href="#"><i class="fa fa-plus-square"></i>Xem nhanh</a></li>

                    </ul>
                </div>
            </div>
        </div>
        @endforeach
    </form>

    @include('home.components.quick_view')
    <br>

    @else
    <p>không có sản phẩm nào</p>
    @endif
</div>
{{$product_display->links()}}
<!--features_items-->