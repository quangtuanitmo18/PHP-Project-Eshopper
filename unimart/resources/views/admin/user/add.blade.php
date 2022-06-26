@extends('layouts.admin')

@section('css') 
<link rel="stylesheet" href="{{asset('vendors/select2/select2.min.css')}}">
<link rel="stylesheet" href="{{asset('admin/user/add.css')}}">

@endsection

@section('js')
<script src="{{asset('vendors/select2/select2.min.js')}}"></script>
<script src="{{asset('admin/user/add.js')}}" ></script>
@endsection

@section('content')

<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Thêm người dùng
        </div>
        <div class="card-body">
            <form action="{{url("admin/user/store")}}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="name">Họ và tên</label>
                   
                    <input class="form-control" type="text" name="name" id="name" value="{{old('name')}}">
                    @error('name')
                    <small class="text-danger">{{$message}}</small>
                     @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input class="form-control" type="text" name="email" id="email" value="{{old('email')}}">
                    @error('email')
                    <small class="text-danger">{{$message}}</small>
                     @enderror
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input class="form-control" type="password" name="password" id="password">
                    @error('password')
                    <small class="text-danger">{{$message}}</small>
                     @enderror
                </div>
                <div class="form-group">
                    <label for="password-confirm">Xác Nhận Mật khẩu</label>
                    <input class="form-control" type="password" name="password_confirm" id="password-confirm">
                    @error('password-confirm')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="">Nhóm quyền</label>
                    <select class="form-control select2_init" id="role-id" name="role_id[]" multiple>
                        @foreach($roles as $role)
                            <option value="{{$role->id}}">{{$role->name}}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" name="btn_add" value="thêm mới" class="btn btn-primary">Thêm mới</button>
            </form>
        </div>
    </div>
</div>
@endsection