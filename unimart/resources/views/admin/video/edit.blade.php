@extends('layouts.admin')

@section('js')
<script>
    $(document).ready(function() {
        show_gallery();

        function show_gallery() {
            var id_video = $("#id_video").val();
            var _token = $('input[name="_token"]').val();
            $.ajax({

                type: "POST",
                url: "{{ route('admin.video.show_image_ajax') }}",
                data: {
                    id_video: id_video,
                    _token: _token
                },
                success: function(data) {
                    $("#show_images").html(data);

                }

            });
        };
        $(document).on('change', '.choose_file_image', function() {
            var id_video = $("#id_video").val();

            var video_images = document.getElementById('image_path').files[0];

            var form_data = new FormData();

            form_data.append("image_path", document.getElementById('image_path').files[0]);
            form_data.append("id_video", id_video);
            $.ajax({
                url: "{{ route('admin.video.insert_image_ajax') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form_data,
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    show_gallery();
                }
            });
        });



    });
</script>
@endsection

@section('content')

<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Chỉnh sửa video
        </div>
        <div class="card-body">
            <form action="{{route("admin.video.update",$video->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_video" id="id_video" value="{{$video->id}}">

                <div class=" form-group">
                    <label for="title">Tiêu đề</label>
                    <input class="form-control" type="text" name="title" id="title" value="{{$video->title}}">
                    @error('title')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="intro">Mô tả</label>
                    <textarea class="form-control" id="desc" name="desc" cols="30" rows="5">{{$video->desc}}</textarea>
                    @error('desc')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="name">Đường dẫn</label>
                    <input class="form-control" type="text" name="link" id="link" value="{{$video->link}}">
                    @error('link')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>






                <div class="form-group">
                    <label for="">Trạng thái</label>
                    @foreach($statuses as $k=>$status)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="{{$k}}" value="{{$k}}" {{$k==$video->status?"checked":""}}>
                        <label class="form-check-label" for="{{$k}}">
                            {{$status}}
                        </label>
                    </div>
                    @endforeach
                    @error('status')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <button type="submit" name="btn-update" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection