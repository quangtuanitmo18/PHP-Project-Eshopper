@extends('layouts.admin')


@section('content')
<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm slider
        </div>
        <div class="card-body">
            <form action="{{url("admin/slider/store")}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <label for="name">Tiêu đề </label>
                    <input class="form-control" type="text" name="name" id="name" value="{{old('name')}}">
                    @error('name')
                            <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="intro">Mô tả</label>
                    <textarea  class="form-control" id="decs" name="decs" cols="30" rows="5">{{old('decs')}}</textarea>
                    @error('decs')
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
                    <label for="image_path">Chọn hình ảnh</label>
                    <input type="file" class="form-control-file" name="image_path" id="image_path" >
                    @error('image_path')
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