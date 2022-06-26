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
                    Danh sách vai trò
                </div>
                <div class="card-body">
                    <form action="{{url("admin/permission/add")}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên vai trò</label>
                            <input class="form-control" type="text" name="name" id="name">
                            @error('name')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="per_parent">Vai trò cha</label>
                            <select class="form-control" id="per_parent" name="per_parent">
                                <option value="{{0}}">Chọn danh mục</option>

                                @foreach($role_cats_select as $item)
                                <option value="{{$item->id}}">{{$item->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        @can('permission-action')
                        <button type="submit" class="btn-add btn-primary">Thêm mới</button>
                        @endcan

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
                                <th scope="col">Vai trò</th>
                                <th scope="col">key_permission</th>
                                <th scope="col">Ngày tạo</th>


                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        @php $t=0; @endphp
                        <tbody>


                            @foreach($role_cats_1 as $k=>$value)
                            <tr>

                                @php $t++; @endphp
                                <td>{{$t}}</td>
                                <td>{{$value['name']}}</td>
                                <td>{{$value['key_permission']}}</td>
                                <td>{{$value['created_at']}}</td>


                                <td>
                                    @can('permission-edit')
                                    <a href="{{route('admin.permission.edit',$k)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                    @endcan

                                    @can('permission-delete')
                                    <a href="{{route('admin.permission.delete',$k)}}" onclick="return confirm('Bạn có chắc chắn muốn xóa vai trò này?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                    @endcan
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