@extends('layouts.admin')

@section('css') 
<link rel="stylesheet" href="{{asset('admin/role/add/add.css')}}">

@endsection

@section('js')
<script src="{{asset('admin/role/add/add.js')}}" ></script>
@endsection

@section('content')

<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold " style='background-color:white !important; '>
            Thêm quyền
        </div>
        <div class="card-body">
            <form action="{{url("admin/role/store")}}" method="post" enctype="multipart/form-data" style="width: 100%;">
                <div class="col-md-12 pl-0">
                    @csrf
                    <div class="form-group">
                        <label>Tên quyền</label>
                        <input type="text"
                               class="form-control"
                               name="name"
                               value="{{ old('name') }}"
                        >
                        @error('name')
                        <small class="text-danger">{{$message}}</small>
                         @enderror
                    </div>

                    <div class="form-group">
                        <label>Mô tả quyền</label>

                        <input type="text"
                            class="form-control"
                            name="display_name" value="{{ old('display_name') }}">
                            @error('display_name')
                            <small class="text-danger">{{$message}}</small>
                             @enderror
                    </div>


                </div>
                <div class="col-md-12 s">
                    <div class="row">
                        <div class="col-md-12 pl-0">
                            <label>
                                <input type="checkbox" class="checkall">
                                CHECK ALL
                            </label>
                        </div>

                        @foreach($permissions_parent as $permissionsParentItem)
                            <div class="card border-primary mb-3 col-md-12 pl-0 pr-0">
                                <div class="card-header" style="background:#36423b; color:white; ">
                                    <label>
                                        <input type="checkbox" value="" class="checkbox_wrapper">
                                        Module {{ $permissionsParentItem->name }}
                                    </label>
                                    
                                </div>
                                <div class="row">
                                    @foreach($permissionsParentItem->permissionChildrent as $permissionsChildrentItem)
                                        <div class="card-body text-primary col-md-3">
                                            <p class="card-title">
                                                <label>
                                                    <input type="checkbox" name="permission_id[]"
                                                           class="checkbox_childrent"
                                                           value="{{ $permissionsChildrentItem->id }}">
                                                    {{ $permissionsChildrentItem->name }}
                                                </label>
                                                
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach


                    </div>


                </div>
                <button type="submit" class="btn btn-primary">Thêm quyền</button>
            </form>
        </div>
    </div>
</div>
@endsection