<style>
    .owl-dots {
        display: none;
    }

    button.owl-prev,
    button.owl-next {
        font-size: 30px !important;

    }
</style>
<?php $base_url = config('app.base_url');
?>
<div class="recommended_items">
    <!--recommended_items-->
    <h2 class="title text-center">Sản phẩm bán chạy</h2>
    <div class="owl-carousel owl-theme">
        @foreach($recommended_product as $item)
        <div style='width:100%; padding-left:25px; ' class="item">
            <div class="single-products">
                <div class="productinfo text-center">
                    <a href="{{route('product.detail',['slug'=>$item->slug,'id'=>$item->id])}}">

                        <img style='width:181px;height:181px;' src="{{$base_url.$item->thumbnail}}" alt="" />
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