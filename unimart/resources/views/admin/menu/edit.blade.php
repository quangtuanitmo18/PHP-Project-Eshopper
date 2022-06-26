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
                    <form action="{{route("admin.menu.update",$menu->id)}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên menu</label>
                            <input class="form-control" type="text" name="name" id="name" value="{{$menu->name}}">
                            @error('name')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Slug</label>
                            <input class="form-control" type="text" name="slug" id="slug" value="{{$menu->slug}}">
                            @error('slug')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="name">Url menu</label>
                            <input class="form-control" type="text" name="url_menu" id="url_menu" value="{{$menu->url}}">
                            @error('url_menu')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="">Vai trò cha</label>
                            <select class="form-control" id="" name="menu_parent">
                                <option value="{{0}}" {{$menu->parent_id==0?"selected='selected'":""}}>Chọn vai trò</option>

                                @foreach($menu_1 as $k=>$value)
                                <option value="{{$k}}" {{$menu->parent_id==$k?"selected='selected'":""}}>{{$value['name']}}</option>
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
                                <th scope="col">Tên menu</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Ngày tạo</th>

                                <th scope="col">Tác vụ</th>
                            </tr>
                        </thead>
                        <tbody>

                            @php $t=0; @endphp
                            @foreach($menu_2 as $k=>$value)
                            <tr>

                                @php $t++; @endphp
                                <td scope="col">{{$t}}</td>
                                <td scope="col">{{$value['name']}}</td>
                                <td scope="col">{{$value['slug']}}</td>
                                <td scope="col">{{$value['created_at']}}</td>


                                <td scope="col">
                                    <a href="{{route('admin.menu.edit',$k)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a href="{{route('admin.menu.delete',$k)}}" onclick="return confirm('Bạn có chắc chắn muốn xóa menu này?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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