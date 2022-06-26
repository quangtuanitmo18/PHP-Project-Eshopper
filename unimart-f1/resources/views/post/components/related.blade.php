<?php $base_url = config('app.base_url');
?>
<div class="col-sm-9 padding-right">
    <div class="features_items">
        <h2 class="title text-center">Bài viết liên quan -</h2>
        @foreach ($post_related as $value )
        <div class="postbox-list__dflex post-item" style="width:auto; display:flex; margin-top:20px;  overflow: hidden; text-overflow: ellipsis; margin-bottom:20px;">
            <div class="postbox">
                <div class="postbox__thumb img-180" style="width:200px;">
                    <a style="display:block; font-size:16px;" href="{{route('blogs.detail',['slug_cat'=>$value->post_cat->slug,'slug_post'=>$value->slug,'id'=>$value->id])}}">
                        <img style="max-width:100%" data-src="{{$base_url.$value->value}}" src="{{$base_url.$value->thumbnail}}" title="{{$value->title}}" alt="{{$value->title}}" class="image-cut lazyloaded"> </a>
                </div>
            </div>
            <div class="postbox__text" style="margin-left:20px; width:auto;">
                <h3 style="margin-top:0px; font-size:14px;" class="fs-20 letter-spacing-02 fw-700  pt-10">
                    <a style="display:block; color:black;" href="{{route('blogs.detail',['slug_cat'=>$value->post_cat->slug,'slug_post'=>$value->slug,'id'=>$value->id])}}">{{$value->title}}</a>
                </h3>
                <div class="postbox__text-meta pb-10">
                    <ul style="display:flex; padding-left:0px;">
                        <li style="margin-right:5px;">
                            <a href="/"><span></span></a>
                            <a rel="nofollow" class="post-category" href="{{route('blogs.category',['slug'=>$value->post_cat->slug,'id'=>$value->post_cat->id])}}">
                                {{$value->post_cat->name}} </a>
                        </li>
                        <li style="margin-right:5px;">
                            <span class="line-top">|</span>
                        </li>
                        <li>
                            <span>{{$value->created_at}}</span>
                        </li>
                    </ul>
                </div>
                <?php
                $content = Str::of($value->content)->limit(300);
                ?>
                <div class="desc-text  font-family-Arial">
                    <p style="font-size:12px;">{!!$content!!}</p>
                </div>
            </div>
        </div>
        @endforeach

    </div>


</div>
<!--/recommended_items-->