@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{asset('admin/role/add/add.css')}}">

@endsection

@section('js')
<script>
    $(function() {
        $('.checkbox_wrapper').on('click', function() {
            var id_checkbox = $(this).data('id');

            $(this).parents('.card').find('.checkbox_childrent' + id_checkbox).prop('checked', $(this).prop('checked'));
        });

        $('.checkall').on('click', function() {
            $(this).parents().find('[type=checkbox]').prop('checked', $(this).prop('checked'));
            $(this).parents().find('[type=checkbox]').prop('checked', $(this).prop('checked'));

        });
    });
</script>
@endsection

@section('content')

<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold " style='background-color:white !important; '>
            Chỉnh sửa quyền
        </div>
        <div class="card-body">
            <form action="{{route("admin.role.update",$role->id)}}" method="post" enctype="multipart/form-data" style="width: 100%;">
                <div class="col-md-12 pl-0">
                    @csrf
                    <div class="form-group">
                        <label>Tên quyền</label>
                        <input type="text" class="form-control" name="name" placeholder="Nhập tên vai trò" value="{{ $role->name }}">
                        @error('name')
                        <small class="text-danger">{{$message}}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Mô tả quyền</label>

                        <input type="text" class="form-control" name="display_name" value="{{ $role->display_name }}">
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
                                    <input data-id="{{$permissionsParentItem->id}}" type="checkbox" value="" class="checkbox_wrapper">
                                    Module {{ $permissionsParentItem->name }}
                                </label>

                            </div>
                            <div class="row">
                                @foreach($permissionsParentItem->permissionChildrent as $permissionsChildrentItem)
                                <div class="card-body text-primary col-md-3">
                                    <p class="card-title">
                                        <label>
                                            <input type="checkbox" name="permission_id[]" class="checkbox_childrent{{$permissionsParentItem->id}}" value="{{ $permissionsChildrentItem->id }}" {{$permissions->contains('id',$permissionsChildrentItem->id)?"checked":""}}>
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
                <button type="submit" class="btn btn-primary">Cập nhật</button>
            </form>
        </div>
    </div>
</div>
@endsection