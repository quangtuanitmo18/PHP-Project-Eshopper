<?php $base_url = config('app.base_url');
?>
<div class="category-tab">
    <!--category-tab-->
    <div class="col-sm-12">
        <ul class="nav nav-tabs">
            @foreach($tags_product as $k=>$item)

            <li class="{{$k==0?'active':''}}"><a href="#{{$item->name}}" data-toggle="tab">{{$item->name}}</a></li>

            @endforeach
        </ul>
    </div>
    <div class="tab-content">
        @foreach($tags_product as $k=>$item)

        <div class="tab-pane fade {{$k==0?'active in':''}}" id="{{$item->name}}">
            <?php $count = 0; ?>
            @foreach ($item->products as $product)
            <?php $count++; ?>
            <form>
                @csrf
                <input type="hidden" value="{{$product->id}}" class="cart_product_id_{{$product->id}}">

                <div class="col-sm-3">
                    <div class="product-image-wrapper">
                        <div class="single-products">
                            <div class="productinfo text-center">
                                <a href="{{route('product.detail',['slug'=>$product->slug,'id'=>$product->id])}}">

                                    <img style='width:255px; height:255px;' src="{{$base_url.$product->thumbnail}}" alt="" />
                                    <h2>${{number_format($product->price, 1, ',', ' ')}}</h2>
                                    <p>{{$product->name}}</p>
                                </a>
                                <button type="button" class="btn btn-default add-to-cart" data-id_product="{{$product->id}}" name="add-to-cart"><i class="fa fa-shopping-cart"></i>Thêm vào giỏ hàng</button>

                            </div>

                        </div>
                    </div>
                </div>
            </form>
            @if($count==8)
            @break
            @endif
            @endforeach


        </div>

        @endforeach

    </div>
</div>
<!--/category-tab-->