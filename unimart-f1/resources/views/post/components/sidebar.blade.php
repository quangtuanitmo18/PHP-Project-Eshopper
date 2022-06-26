<div class="left-sidebar">
    <h2>Danh mục</h2>
    <h4 class="panel-title" style="margin-bottom:20px;">
        <!-- <a href="{{route('blogs.list_featured')}}">
            Bài viết nổi bật
        </a> -->
    </h4>
    <div class="panel-group category-products" id="accordian">
        <!--category-productsr-->

        <div class="panel panel-default">

            @foreach ($post_cats as $post_cat )

            <div class="panel-heading">

                <h4 class="panel-title">
                    @if($post_cat->post_cat_children->count())
                    <a data-toggle="collapse" data-parent="#accordian" href="#sportswear_{{$post_cat->id}}">
                        <span class="badge pull-right">
                            <i class="fa fa-plus"></i>
                        </span>

                        {{$post_cat->name}}
                    </a>
                    @else
                    <a href="{{route('blogs.category',['slug'=>$post_cat->slug,'id'=>$post_cat->id])}}">
                        <span class="badge pull-right">
                        </span>
                        {{$post_cat->name}}
                    </a>
                    @endif
                </h4>
            </div>


            <div id="sportswear_{{$post_cat->id}}" class="panel-collapse collapse">
                <div class="panel-body">
                    <?php

                    ?>
                    @foreach ($post_cat->post_cat_children as $item)
                    <ul>
                        <li><a href="{{route('blogs.category',['slug'=>$item->slug,'id'=>$item->id])}}">{{$item->name}}</a></li>

                    </ul>
                    @endforeach
                </div>
            </div>

            @endforeach
        </div>

        <div class="shipping text-center">
            <!--shipping-->
            <img style="width:270px;" src="{{asset('post/sidebar/image_1.jpg')}}" alt="" />
        </div>
        <div class="shipping text-center">
            <!--shipping-->
            <img style="width:270px;" src="{{asset('post/sidebar/image_2.png')}}" alt="" />
        </div>
        <div class="shipping text-center">
            <!--shipping-->
            <img style="width:270px;" src="{{asset('post/sidebar/image_3.png')}}" alt="" />
        </div>
        <div class="shipping text-center">
            <!--shipping-->
            <img style="width:270px;" src="{{asset('post/sidebar/image_4.jpg')}}" alt="" />
        </div>

    </div>
    <!--/category-products-->



</div>