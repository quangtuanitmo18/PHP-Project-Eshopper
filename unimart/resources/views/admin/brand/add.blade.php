@extends('layouts.admin')


@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm thương hiệu
        </div>
        <div class="card-body">
            <form action="{{url("admin/brand/store")}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Tên thương hiệu</label>
                    <input class="form-control" type="text" name="name" id="name" value="{{old('name')}}">
                    @error('name')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="intro">Mô tả</label>
                    <textarea class="form-control" id="desc" name="desc" cols="30" rows="5">{{old('desc')}}</textarea>
                    @error('desc')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="name">Thứ tự</label>
                            <input class="form-control" type="text" name="position" id="position" value={{old('position')}}>
                            @error('position')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Chọn hình ảnh</label>
                    <input type="file" class="form-control-file" name="image" id="image">
                    @error('image')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
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