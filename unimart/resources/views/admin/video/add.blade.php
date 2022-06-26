@extends('layouts.admin')


@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm slider
        </div>
        <div class="card-body">
            <form action="{{url("admin/video/store")}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Tiêu đề </label>
                    <input class="form-control" type="text" name="title" id="title" value="{{old('title')}}">
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
                <div class="form-group">
                    <label for="name">Đường dẫn</label>
                    <input class="form-control" type="text" name="link" id="link" value="{{old('link')}}">
                    @error('link')
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