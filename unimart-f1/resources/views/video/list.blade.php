<?php $base_url = config('app.base_url');
?>
@extends('layouts.master')


@section('title')
<title>Eshopper</title>
@endsection

@section('css')
<link rel="stylesheet" href="{{asset('home/home.css')}}">

<link rel="stylesheet" href="{{asset('menu/menu.css')}}">
<style>
    body {
        overflow-x: hidden;
    }

    .card {
        /* Add shadows to create the "card" effect */
        box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
        transition: 0.3s;
    }

    /* On mouse-over, add a deeper shadow */
    .card:hover {
        box-shadow: 0 8px 16px 0 rgba(0, 0, 0, 0.2);
    }

    /* Add some padding inside the card container */
    .container {
        padding: 2px 16px;
    }

    .modal-ku {
        width: 1200px;
        margin: 140px auto;
    }

    .card .container {
        width: 100%;
        text-overflow: ellipsis;
        overflow: hidden;
        height: 93px;
        white-space: nowrap;


    }

    .modal-content {
        height: 785px;
        overflow-y: auto;
    }

    button.close span {
        font-size: 30px;
        position: absolute;
        top: 8px;
        right: 10px;
    }
</style>

@endsection

@section('js')
<script src="{{asset('home/home.js')}}"></script>
<script src="{{asset('menu/menu.js')}}"></script>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.js"></script> -->
<script>
    $(document).ready(function() {
        $(document).on('click', '.click_demo_video', function() {
            var id_video = $(this).data('id_video');
            //alert(id_video);


            var form_data = new FormData();

            form_data.append("id_video", id_video);
            $.ajax({
                url: "{{ route('videos.demo_video_ajax') }}",
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
                    $('#video_title').html(data.output_title);
                    $('#video_desc').html(data.output_desc);
                    $('#demo_video').html(data.output_video);
                }
            });
        });



    });
</script>
@endsection


@section('content')

<body>





    <section>
        <div class="container">
            <div class="row">


                <div class="col-sm-12 padding-right">
                    <h2 class="title text-center">Danh sách video</h2>
                    @foreach($videos as $value)
                    <div class="col-sm-6">

                        <!--features_items-->
                        <div class="card" style="margin-bottom:15px; ">
                            <!-- <img src="{{$base_url.$value->image_path}}" alt="Avatar" style="width:100%"> -->
                            <iframe width="100%" height="304" src='https://www.youtube.com/embed/{{substr($value->link, 17)}}' title=' YouTube video player' frameborder='0' allow='accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture' allowfullscreen></iframe>
                            <div class="container ">
                                <h4><b>{{$value->title}}</b></h4>

                                <!-- <button type='button' class='btn btn-warning ' style="text-align:center!important; margin-bottom:10px;">Watch video</button> -->
                                <!-- <button type="button" style="text-align:center!important; margin-bottom:10px;" class="btn btn-primary btn-sm click_demo_video" data-id_video="{{$value->id}}" data-toggle="modal" data-target="#exampleModalCenter"> -->
                                <!-- </button> -->
                                <p>{!!$value->desc!!}</p>
                            </div>
                        </div>
                        <a style="text-align:center!important; margin-bottom:30px; margin-top:0px;" class="btn btn-primary btn-sm click_demo_video" data-id_video="{{$value->id}}" data-toggle="modal" data-target="#exampleModalCenter">Xem chi tiết</a>

                    </div>



                    @endforeach
                    <!--features_items-->
                    {{$videos->links()}}
                    <!-- Modal -->
                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog modal-lg modal-ku modal-dialog-centered" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="video_title"></h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>

                                </div>
                                <div class="modal-header">
                                    <p id="video_desc"></p>

                                </div>
                                <div class="modal-body" id="demo_video">

                                </div>

                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </div>

    </section>






</body>

@endsection