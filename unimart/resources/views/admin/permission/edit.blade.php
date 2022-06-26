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
                    Danh mục vai trò
                </div>
                <div class="card-body">
                    <form action="{{route("admin.permission.update",$permission->id)}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên vai trò</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{$permission->name}}">
                            @error('name')
                            <small class="text-danger">{{$message}}</small>
                             @enderror
                        </div>
                        
                        <div class="form-group">
                            <label for="">Vai trò cha</label>
                            <select class="form-control" id="" name="per_parent">
                                <option value="{{0}}" {{$permission->parent_id==0?"selected='selected'":""}}>Chọn vai trò</option>
                               
                                @foreach($permission_cats_1 as $k=>$value)
                                    <option value="{{$k}}" {{$permission->parent_id==$k?"selected='selected'":""}}>{{$value['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                       


                        <button type="submit" class="btn-add btn-primary">Cập nhật</button>
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
                                <th scope="col">Tên vai trò</th>
                                <th scope="col">key_permission</th>
                                <th scope="col">Ngày tạo</th>

                                <th scope="col">Tác vụ</th>                                
                            </tr>
                        </thead>
                        <tbody>
                            
                            
                            @php $t=0;  @endphp
                            @foreach($permission_cats_2 as $k=>$value)
                            <tr>
                                
                                @php $t++;  @endphp
                                <td scope="col" >{{$t}}</td>
                                <td scope="col" >{{$value['name']}}</td>
                                <td scope="col" >{{$value['key_permission']}}</td>
                                <td scope="col" >{{$value['created_at']}}</td>

                               
                                <td scope="col" >
                                    <a href="{{route('admin.permission.edit',$k)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a href="{{route('admin.permission.delete',$k)}}" onclick="return confirm('Bạn có chắc chắn muốn xóa vai trò này?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                </td>

                            </tr>
                            @endforeach
                                 
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection