@extends('layouts.admin')

@section('content')




<div id="content" class="container-fluid">
    @if(session('status'))
            <div class="alert alert-success">
                      {{session('status')}}
             </div>
         @endif
    <div class="row">
        
        <div class="col-4">
            <div class="card">
                
                <div class="card-header font-weight-bold">
                    Danh sách Menu
                </div>
                <div class="card-body">
                    <form action="{{url("admin/event/add")}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên event</label>
                            <input class="form-control" type="text" name="name" id="name">
                            @error('name')
                            <small class="text-danger">{{$message}}</small>
                             @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Slug</label>
                            <input class="form-control" type="text" name="slug" id="slug">
                            @error('slug')
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


                        <button type="submit" class="btn-add btn-primary">Thêm mới</button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-8">
            <div class="card">
                <div class="card-header font-weight-bold">
                    Danh sách
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Tên sự kiện</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Trạng thái</th>

                                <th scope="col">Ngày tạo</th>


                                <th scope="col">Tác vụ</th>                                
                            </tr>
                        </thead>
                        @php $t=0;  @endphp
                        <tbody>
                         
                            
                            @foreach($events as $k=>$value)
                            <tr>
                                
                                @php $t++;  @endphp
                                <td scope="col" >{{$t}}</td>
                                <td scope="col" >{{$value['name']}}</td>
                                <td scope="col" >{{$value['slug']}}</td>
                                <?php
                                if($value->status==0) {
                                    $color_badge="badge-dark";
                                }
                                else if($value->status==1){
                                    $color_badge="badge-success";
                                }
                                ?>
                                <td><span class="badge {{$color_badge}}">{{$statuses[$value->status]}}</span></td>
                                <td scope="col" >{{$value['created_at']}}</td>


                                <td scope="col" >
                                    <a href="{{route('admin.event.edit',$value->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a href="{{route('admin.event.delete',$value->id)}}" onclick="return confirm('Bạn có chắc chắn muốn xóa sự kiện này?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                </td>

                            </tr>
                            @endforeach
                                 
                        </tbody>
                    </table>
                    {{-- {{$role_cats_2->links()}} --}}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection