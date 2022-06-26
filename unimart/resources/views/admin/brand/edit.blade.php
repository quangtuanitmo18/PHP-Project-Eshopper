@extends('layouts.admin')

@section('js')
<script>
    $(document).ready(function() {
        show_gallery();

        function show_gallery() {
            var id_brand = $("#id_brand").val();
            var _token = $('input[name="_token"]').val();
            $.ajax({

                type: "POST",
                url: "{{ route('admin.brand.show_image_ajax') }}",
                data: {
                    id_brand: id_brand,
                    _token: _token
                },
                success: function(data) {
                    $("#show_images").html(data);

                }

            });
        };
        $(document).on('change', '.choose_file_image', function() {
            var id_brand = $("#id_brand").val();

            var slider_images = document.getElementById('image').files[0];

            var form_data = new FormData();

            form_data.append("image", document.getElementById('image').files[0]);
            form_data.append("id_brand", id_brand);
            $.ajax({
                url: "{{ route('admin.brand.insert_image_ajax') }}",
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
                var id_brand = $(this).data('id_brand');
                var form_data = new FormData();
                if (confirm('Bạn có chắc chắn muốn xóa hình ảnh này không?')) {

                    form_data.append("id_brand", id_brand);
                    $.ajax({
                        url: "{{ route('admin.brand.delete_image_ajax') }}",
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
            Chỉnh sửa thương hiệu
        </div>
        <div class="card-body">
            <form action="{{route("admin.brand.update",$product_brand->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_brand" id="id_brand" value="{{$product_brand->id}}">

                <div class=" form-group">
                    <label for="name">Tên thương hiệu</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{$product_brand->name}}">
                    @error('name')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="intro">Mô tả</label>
                    <textarea class="form-control" id="desc" name="desc" cols="30" rows="5">{{$product_brand->desc}}</textarea>
                    @error('desc')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Thứ tự</label>
                            <input class="form-control" type="text" name="position" id="position" value={{$product_brand->position}}>
                            @error('position')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>




                <div class="form-group">
                    <label for="image_path">Chọn hình ảnh</label>
                    <input type="file" class="form-control-file choose_file_image" name="image" id="image" accept="image/*">
                    @error('image')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                    <div class="col-md-6 mt-2">
                        <div class="row" id="show_images">
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label>
                    @foreach($statuses as $k=>$status)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="{{$k}}" value="{{$k}}" {{$k==$product_brand->status?"checked":""}}>
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