@extends('layouts.admin')

@section('content')

<div id="content" class="container-fluid">
    <div class="card">
        @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
        @endif
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách quyền</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="text" class="form-control form-search" name="keyword" id="keyword" placeholder="Tìm kiếm" value={{request()->input('keyword')}}>
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{request()->fullUrlwithQuery(['status'=>'active']) }}" class="text-primary">Kích hoạt<span class="text-muted">({{$count[0]}})</span></a>
                <a href="{{request()->fullUrlwithQuery(['status'=>'trash'])}}" class="text-primary">Vô hiệu hóa<span class="text-muted">({{$count[1]}})</span></a>

            </div>
            <form action="{{url('admin/role/action')}}">
                @can('role-action')
                <div class="form-action form-inline py-3">
                    <select class="form-control mr-1" id="" name="act">
                        <option>Chọn</option>
                        @foreach($list_act as $k=>$value)
                        <option value="{{$k}}">{{$value}}</option>
                        @endforeach
                    </select>
                    <input type="submit" name="btn-search" value="Áp dụng" class="btn btn-primary">
                </div>
                @endcan
                <table class="table table-striped table-checkall" id="myTable">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" name="checkall">
                            </th>
                            <th scope="col">#</th>
                            <th scope="col">Tên quyền</th>
                            <th scope="col">Mô tả quyền</th>

                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($roles->total()>0)
                        @php
                        $t=0;
                        @endphp

                        @foreach($roles as $role)
                        @php
                        $t++;
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" name="list_check[]" value={{$role->id}}>
                            </td>
                            <th scope="row">{{$t}}</th>
                            <td>{{$role->name}}</td>
                            <td>{{$role->display_name}}</td>
                            <td>{{$role->created_at}}</td>
                            <td>
                                @if(request()->input('status')!="trash")

                                @can('role-edit')
                                <a href="{{route('admin.role.edit',$role->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                @endcan

                                @can('role-delete')
                                <a href="{{route('admin.role.delete',$role->id)}}" onclick="return confirm('Bạn có chắc chắn muốn xóa bản ghi này?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                @endcan
                                @endif
                            </td>
                        </tr>
                        @endforeach
                        @else
                        <tr id="no_result">
                            <td colspan="7" class="bg-white">
                                không tìm thấy bản ghi
                            </td>
                        </tr>

                        @endif


                    </tbody>
                </table>
            </form>
            {{$roles->links()}}

        </div>
    </div>
</div>


@endsection


{{-- // $(document).ready(function(){
    // //     $("#click_me").click(function(){
    // //         alert("oke");
    // //     });
    //    $(document).on('keyup','#keyword',function(){
    //         var keyword=$(this)->value;
    //         alert("đá");
    //         // $.ajax({
    //         //     type:"GET",
    //         //     url:"/admin/user/search",
    //         //     data: {
    //         //         keyword: keyword
    //         //     },
    //         //     dataType:"json",
    //         //     success: function(response){
    //         //         $('#listUser').html(response);
    //         //     }

    //         // });
    //    });
    // }); --}}