@extends('layouts.admin')

@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm bài viết
        </div>
        <div class="card-body">
            <form action="{{url("admin/post/store")}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Tiêu đề bài viết</label>
                    <input class="form-control" type="text" name="title" id="title" value="{{old('title')}}">
                    @error('title')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="content">Nội dung bài viết</label>
                    <textarea name="content" class="form-control" id="content" cols="30" rows="5">{{old('content')}}</textarea>
                    @error('content')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>


                <div class="form-group">
                    <label for="">Danh mục</label>
                    <select class="form-control" id="" name="post_cat_id">
                        <option value="">Chọn danh mục</option>
                        @php
                        $cats_tmp=array();
                        showCategories($post_cats,$cats_tmp);

                        @endphp
                        @foreach($cats_tmp as $k=>$value)
                        <option value="{{$k}}" {{old('post_cat_id')==$k?"selected='selected'":""}}>{{$value['name']}}</option>
                        @endforeach
                    </select>
                    @error('post_cat_id')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="thumbnail">Chọn hình ảnh</label>
                    <input type="file" class="form-control-file" name="thumbnail" id="thumbnail">
                    @error('thumbnail')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="">Bài viết</label><br>

                    <div class="form-check">

                        <input class="form-check-input" type="radio" name="featured" id="featured">
                        <label class="form-check-label" for="featured">Nổi bật</label><br>

                    </div>
                </div>
                <div class="form-group">
                    <label for="">Trạng thái</label>
                    @foreach($statuses as $k=>$status)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="{{$k}}" value="{{$k}}" {{old('status')==$k?"checked":""}}>
                        <label class="form-check-label" for="{{$k}}">
                            {{$status}}
                        </label>
                    </div>
                    @endforeach

                </div>





                <button type="submit" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection