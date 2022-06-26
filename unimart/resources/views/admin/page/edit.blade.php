@extends('layouts.admin')


@section('content')

<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Chỉnh sửa bài viết
        </div>
        <div class="card-body">
            <form action="{{route("admin.page.update",$page->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="name">Tiêu đề</label>
                            <input class="form-control" type="text" name="title" id="title" value="{{$page->title}}" >
                            @error('title')
                            <small class="text-danger">{{$message}}</small>
                             @enderror
                        </div>                  
                    </div>
                    <div class="col-12">
                        <div class="form-group">
                            <label for="description">Nội dung bài viết</label>
                            <textarea  class="form-control" id="content" name="content" cols="30" rows="5"> {{$page->content}}</textarea>
                            @error('content')
                               <small class="text-danger">{{$message}}</small>
                             @enderror
                        </div>
                    </div>
                </div>

                
                <div class="form-group">
                    <label for="">Trạng thái</label>
                    @foreach($statuses as $k=>$status)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="{{$k}}" value="{{$k}}" {{$k==$page->status?"checked":""}}>
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