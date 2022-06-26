<?php $base_url = config('app.base_url');
?>
<div class="recommended_items">
    <!--related_items-->
    <h2 class="title text-center">Sản phẩm liên quan</h2>


    <div class="owl-carousel owl-theme">
        @foreach($product_related as $item)

        <div class="item">
            <div class="single-products">
                <div class="productinfo text-center">
                    <input type="hidden" value="{{$item->id}}" class="cart_product_id_{{$item->id}}">
                    <a href="{{route('product.detail',['slug'=>$item->slug,'id'=>$item->id])}}">

                        <img src="{{$base_url.$item->thumbnail}}" alt="" />
                        <h2>${{number_format($item->price, 1, ',', ' ')}}</h2>
                        <p>{{$item->name}}</p>

                    </a>
                    <button type="button" class="btn btn-default add-to-cart" data-id_product="{{$item->id}}" name="add-to-cart"><i class="fa fa-shopping-cart"></i>Thêm vào giỏ hàng</button>
                </div>

            </div>
        </div>
        @endforeach


    </div>
</div>

<!--/recommended_items-->