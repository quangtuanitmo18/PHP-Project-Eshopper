<?php $base_url = config('app.base_url');
?>
@extends('layouts.master')


@section('title')
<title>Eshopper</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('home/home.css')}}">

<style>
    html {
        scroll-behavior: smooth;
    }

    .table_of_content ul li {
        padding: 2px;
        font-size: 16px;

    }

    .table_of_content ul li a {
        color: #000;
    }

    .table_of_content ul li a:hover {
        color: #FE980F;
    }

    .table_of_content ul li {
        list-style-type: decimal;
    }

    .table_of_content h1 {
        font-size: 20px;
        color: brown;
    }
</style>

@endsection

@section('js')
<script src="{{asset('home/home.js')}}"></script>
<script src="{{asset('menu/menu.js')}}"></script>

@endsection

@section('content')

<body>

    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    @include('post.components.sidebar')
                </div>

                <div class="col-sm-9 padding-right">
                    <div class="features_items" style="margin-bottom:60px;">
                        <!--features_items-->
                        <h2 class="title text-center">Bài viết - {{$post_cat->name}} </h2>
                        <h3>{!!$post->title!!}</h3>
                        {!! $post->content!!}




                    </div>

                    <!--features_items-->
                    @include('post.components.related')
                </div>
                <!-- Related posts -->



            </div>

        </div>

    </section>






</body>

@endsection