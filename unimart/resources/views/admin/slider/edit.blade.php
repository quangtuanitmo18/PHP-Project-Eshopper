@extends('layouts.admin')

@section('js')
<script>
    $(document).ready(function() {
        show_gallery();

        function show_gallery() {
            var id_slider = $("#id_slider").val();
            var _token = $('input[name="_token"]').val();
            $.ajax({

                type: "POST",
                url: "{{ route('admin.slider.show_image_ajax') }}",
                data: {
                    id_slider: id_slider,
                    _token: _token
                },
                success: function(data) {
                    $("#show_images").html(data);

                }

            });
        };
        $(document).on('change', '.choose_file_image', function() {
            var id_slider = $("#id_slider").val();

            var slider_images = document.getElementById('image_path').files[0];

            var form_data = new FormData();

            form_data.append("image_path", document.getElementById('image_path').files[0]);
            form_data.append("id_slider", id_slider);
            $.ajax({
                url: "{{ route('admin.slider.insert_image_ajax') }}",
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
                var id_slider = $(this).data('id_slider');
                var form_data = new FormData();
                if (confirm('Bạn có chắc chắn muốn xóa hình ảnh này không?')) {

                    form_data.append("id_slider", id_slider);
                    $.ajax({
                        url: "{{ route('admin.slider.delete_image_ajax') }}",
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
            Chỉnh sửa slider
        </div>
        <div class="card-body">
            <form action="{{route("admin.slider.update",$slider->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_slider" id="id_slider" value="{{$slider->id}}">

                <div class=" form-group">
                    <label for="name">Tiêu đề</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{$slider->name}}">
                    @error('name')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="intro">Mô tả</label>
                    <textarea class="form-control" id="decs" name="decs" cols="30" rows="5">{{$slider->decs}}</textarea>
                    @error('decs')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Thứ tự</label>
                            <input class="form-control" type="text" name="position" id="position" value={{$slider->position}}>
                            @error('position')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>




                <div class="form-group">
                    <label for="image_path">Chọn hình ảnh</label>
                    <input type="file" class="form-control-file choose_file_image" name="image_path" id="image_path" accept="image/*">
                    @error('image_path')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                    <div class="col-md-6 mt-2">
                        <div class="row" id="show_images">
                            <!-- <img src="{{url("$slider->image_path")}}" alt="" style="max-width:100%; height:130px; object-fit:cover; "> -->
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    @foreach($statuses as $k=>$status)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="{{$k}}" value="{{$k}}" {{$k==$slider->status?"checked":""}}>
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