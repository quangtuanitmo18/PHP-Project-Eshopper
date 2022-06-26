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
                    Danh mục sản phẩm
                </div>
                <div class="card-body">
                    <form action="{{url("admin/product/cat/add")}}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="name">Tên danh mục</label>
                            <input class="form-control" type="text" name="name" id="name">
                            @error('name')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="slug">Slug</label>
                            <input class="form-control" type="text" name="slug" id="slug">
                        </div>

                        <div class="form-group">
                            <label for="name">Thứ tự</label>
                            <input class="form-control" type="text" name="position" id="position" value={{old('position')}}>
                            @error('position')
                            <small class="text-danger">{{$message}}</small>
                            @enderror
                        </div>


                        <div class="form-group">
                            <label for="">Danh mục cha</label>
                            <select class="form-control" id="" name="parent_cat">
                                <option value="{{0}}">Chọn danh mục</option>

                                @foreach($menu_1 as $k=>$value)
                                <option value="{{$k}}">{{$value['name']}}</option>
                                @endforeach
                            </select>
                        </div>


                        @can('category-product-add')
                        <button type="submit" class="btn-add btn-primary">Thêm mới</button><br>
                        @endcan


                    </form>

                    <form action="{{route('admin.product.cat.excel_import')}}" method="post" enctype="multipart/form-data" class="mt-3">
                        @csrf
                        <input type="file" accept=".xlsx" name="D" id="import_excel">
                        <input type="submit" value="import_csv" name="import_CSV" class="btn btn-warning mt-1">

                    </form>

                    <form action="{{route('admin.product.cat.excel_export')}}" method="post" class="mt-3">
                        @csrf
                        <input type="submit" value="export_csv" name="export_CSV" class="btn btn-success">

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
                                <th scope="col">Danh mục</th>
                                <th scope="col">Slug</th>
                                <th scope="col">Thứ tự</th>
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

                                <td scope="col">{{Str::slug($value['name'])}}</td>
                                <td scope="col">{{$value['position']}}</td>

                                <td scope="col">
                                    <a href="{{route('admin.product.cat.edit',$k)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                    <a href="{{route('admin.product.cat.delete',$k)}}" onclick="return confirm('Bạn có chắc chắn muốn xóa bản ghi này?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                </td>

                            </tr>
                            @endforeach

                        </tbody>
                    </table>
                    {{-- {{$product_cats_table->links()}} --}}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection