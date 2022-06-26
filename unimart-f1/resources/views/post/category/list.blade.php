<?php $base_url = config('app.base_url');
?>
@extends('layouts.master')


@section('title')
<title>Eshopper</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('home/home.css')}}">


@endsection

@section('js')
<script src="{{asset('home/home.js')}}"></script>
<script src="{{asset('menu/menu.js')}}"></script>

@endsection


@section('content')

<body>


    @include('home.components.slider')



    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    @include('post.components.sidebar')
                </div>

                <div class="col-sm-9 padding-right">
                    <div class="features_items">
                        <!--features_items-->
                        <h2 class="title text-center">{{$post_category->name}} Posts -</h2>
                        @foreach($posts as $value)
                        <div class="postbox-list__dflex post-item" style="width:auto; display:flex; margin-top:20px;  overflow: hidden; text-overflow: ellipsis; margin-bottom:20px;">
                            <div style="" class="postbox">
                                <div class="postbox__thumb img-180" style="width:300px;">
                                    <a style="display:block;" href="{{route('blogs.detail',['slug_cat'=>$value->post_cat->slug,'slug_post'=>$value->slug,'id'=>$value->id])}}">
                                        <img style="max-width:100%" data-src="{{$base_url.$value->value}}" src="{{$base_url.$value->thumbnail}}" title="{{$value->title}}" alt="{{$value->title}}" class="image-cut lazyloaded"> </a>
                                </div>
                            </div>
                            <div class="postbox__text" style="margin-left:20px; width:auto;">
                                <h3 style="margin-top:0px; font-size:18px;" class="fs-20 letter-spacing-02 fw-700  pt-10">
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
                                    <p style="">{!!$content!!}</p>
                                </div>
                            </div>
                        </div>
                        @endforeach



                        {{$posts->links()}}

                    </div>

                    <!--features_items-->
                </div>

            </div>

        </div>

    </section>






</body>

@endsection