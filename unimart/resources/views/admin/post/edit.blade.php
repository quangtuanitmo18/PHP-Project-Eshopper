@extends('layouts.admin')



@section('js')
<script>
    $(document).ready(function() {
        show_gallery();

        function show_gallery() {
            var id_post = $("#id_post").val();
            var _token = $('input[name="_token"]').val();
            $.ajax({

                type: "POST",
                url: "{{ route('admin.post.show_image_ajax') }}",
                data: {
                    id_post: id_post,
                    _token: _token
                },
                success: function(data) {
                    $("#show_images").html(data);

                }

            });
        };
        $(document).on('change', '.choose_file_image', function() {
            var id_post = $("#id_post").val();

            var post_images = document.getElementById('thumbnail').files[0];

            var form_data = new FormData();

            form_data.append("thumbnail", document.getElementById('thumbnail').files[0]);
            form_data.append("id_post", id_post);
            $.ajax({
                url: "{{ route('admin.post.insert_image_ajax') }}",
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
        $(document).ready(function() {
            $(document).on('click', 'a.delete_image', function() {
                var id_post = $(this).data('id_post');
                var form_data = new FormData();
                if (confirm('Bạn có chắc chắn muốn xóa hình ảnh này không?')) {

                    form_data.append("id_post", id_post);
                    $.ajax({
                        url: "{{ route('admin.post.delete_image_ajax') }}",
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
            Chỉnh sửa bài viết
        </div>
        <div class="card-body">
            <form action="{{route("admin.post.update",$post->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <input type="hidden" name="id_post" id="id_post" value="{{$post->id}}">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="name">Mô tả bài viết</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{$post->title}}">
                            @error('title')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Nội dung bài viết</label>
                            <textarea class="form-control" id="content" name="content" cols="30" rows="5"> {{$post->content}}</textarea>
                            @error('content')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Danh mục</label>
                    <?php
                    $categories = array();
                    showCategories($post_cats, $categories);
                    ?>
                    <select class="form-control" id="post_cat_id" name="post_cat_id">
                        @foreach($categories as $k=>$post_cat)
                        <option value="{{$k}}" {{$k==$post->post_cat_id?"selected='selected'":""}}>{{$post_cat['name']}}</option>
                        @endforeach
                    </select>
                    @error('post_cat_id')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="thumbnail">Chọn hình ảnh</label>
                    <input type="file" class="form-control-file choose_file_image" name="thumbnail" id="thumbnail">

                    {{-- @error('thumbnail')
                    <small class="text-danger">{{$message}}</small>

                    @enderror --}}
                    <div class="col-md-6 mt-2">
                        <div class="row" id="show_images">
                            <!-- <img src="{{url("$post->thumbnail")}}" alt="" style="max-width:100%; height:130px; object-fit:cover; "> -->
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="">Bài viết</label><br>

                    <div class="form-check">

                        <input class="form-check-input" type="radio" name="featured" id="featured" {{$post->post_featured==1?"checked":""}}>
                        <label class="form-check-label" for="featured">Nổi bật</label><br>

                    </div>
                </div>
                <div class="form-group">
                    <label for="">Trạng thái</label>
                    @foreach($statuses as $k=>$status)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="{{$k}}" value="{{$k}}" {{$k==$post->status?"checked":""}}>
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