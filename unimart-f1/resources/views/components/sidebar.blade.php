<div class="left-sidebar">
    <h2>Danh mục</h2>
    <div class="panel-group category-products" id="accordian">
        <!--category-productsr-->
        <div class="panel panel-default">

            @foreach ($product_cats as $product_cat )

            <div class="panel-heading">


                <h4 class="panel-title">
                    @if($product_cat->product_cat_children->count())
                    <a data-toggle="collapse" data-parent="#accordian" href="#sportswear_{{$product_cat->id}}">
                        <span class="badge pull-right">

                            <i class="fa fa-plus"></i>

                        </span>

                        {{$product_cat->name}}
                    </a>
                    @else
                    <a href="{{route('product_cat.index',['slug'=>$product_cat->slug,'id'=>$product_cat->id])}}">
                        <span class="badge pull-right">
                        </span>
                        {{$product_cat->name}}
                    </a>
                    @endif
                </h4>
            </div>

            <div id="sportswear_{{$product_cat->id}}" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php

                    ?>
                    @foreach ($product_cat->product_cat_children as $item)
                    <ul>
                        <li><a href="{{route('product_cat.index',['slug'=>$item->slug,'id'=>$item->id])}}">{{$item->name}}</a></li>

                    </ul>
                    @endforeach
                </div>
            </div>

            @endforeach
        </div>









    </div>
    <div class="brands_products">
        <!--brands_products-->
        <h2>Thương hiệu</h2>
        <div class="brands-name">
            <ul class="nav nav-pills nav-stacked">
                @foreach($brands as $value)
                <li><a href="{{route('product_brand.index',['slug'=>$value->slug,'id'=>$value->id])}}"> <span class="pull-right">({{$value->products->count()}})</span>{{$value->name}}</a></li>
                @endforeach
            </ul>
        </div>
    </div>
    <!--/brands_products-->

    <!--/category-products-->
    <!--/price-range-->

    <div class="shipping text-center">
        <!--shipping-->
        <img src="{{asset('home/sidebar/shipping.jpg')}}" alt="" />
    </div>
    <div class="shipping text-center">
        <!--shipping-->
        <img style="width:270px;" src="{{asset('home/sidebar/image_3.jpg')}}" alt="" />
    </div>
    <div class="shipping text-center">
        <!--shipping-->
        <img style="width:270px;" src="{{asset('home/sidebar/image_1.jpg')}}" alt="" />
    </div>
    <div class="shipping text-center">
        <!--shipping-->
        <img style="width:270px;" src="{{asset('home/sidebar/image_2.jpg')}}" alt="" />
    </div>
    <!--/shipping-->



</div>