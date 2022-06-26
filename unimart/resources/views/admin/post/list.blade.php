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
            <h5 class="m-0 ">Danh sách bài viết</h5>
            <div class="form-search form-inline">
                <form action="#">
                    <input type="" class="form-control form-search" name="keyword" id="keyword" placeholder="Tìm kiếm">
                </form>
            </div>
        </div>
        <div class="card-body">
            <div class="analytic">
                <a href="{{request()->fullUrlwithQuery(['status'=>'public']) }}" class="text-primary">{{$statuses[0]}}<span class="text-muted">({{$count[1]}})</span></a>
                <a href="{{request()->fullUrlwithQuery(['status'=>'pending']) }}" class="text-primary">{{$statuses[1]}}<span class="text-muted">({{$count[2]}})</span></a>
                <a href="{{request()->fullUrlwithQuery(['status'=>'delete']) }}" class="text-primary">Vô hiệu hóa<span class="text-muted">({{$count[3]}})</span></a>
            </div>
            <form action="{{url("admin/post/action")}}">
                @can('post-action')
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
                            <th scope="col">
                                <input name="checkall" type="checkbox">
                            </th>
                            <th scope="col">#</th>
                            <th scope="col">Ảnh</th>
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Danh mục</th>
                            <th scope="col">Trạng thái</th>

                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($posts->total()>0)
                        @php
                        $t=0;
                        @endphp
                        @foreach($posts as $post)
                        @php
                        $t++;
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" name="list_check[]" value="{{$post->id}}">
                            </td>
                            <td scope="row">{{$t}}</td>
                            <td><img src="{{url("$post->thumbnail")}}" alt="" class="img-fluid" style="max-width:100px; max-height:auto"></td>
                            <td><a href="">{{$post->title}}</a></td>
                            <td>{{$post->post_cat->name}}</td>
                            <?php
                            if ($post->status == 0) {
                                $color_badge = "badge-dark";
                            } else if ($post->status == 1) {
                                $color_badge = "badge-success";
                            }
                            ?>
                            <td><span class="badge {{$color_badge}}">{{$statuses[$post->status]}}</span></td>
                            <td>{{$post->created_at}}</td>
                            <td>
                                @if(request()->input('status')!="delete")
                                @can('post-edit')
                                <a href="{{route('admin.post.edit',$post->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                @endcan
                                @can('post-delete')
                                <a href="{{route('admin.post.delete',$post->id)}}" class="btn btn-danger btn-sm rounded-0 text-white" onclick="return confirm('bạn có chắc chắn muốn xóa bài viêt này không?')" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
                                @endcan

                                @endif
                            </td>

                        </tr>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="7" class="bg-white">
                                không tìm thấy bản ghi
                            </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </form>
            {{$posts->links()}}
        </div>
    </div>
</div>

@endsection