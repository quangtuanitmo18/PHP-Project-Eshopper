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
            <h5 class="m-0 ">Danh sách slider</h5>
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
            <form action="{{url('admin/slider/action')}}">
                @can('slider-action')
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
                            <th scope="col">Tiêu đề</th>
                            <th scope="col">Slider</th>

                            <th scope="col">Thứ tự</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Ngày tạo</th>
                            <th scope="col">Người tạo</th>

                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($sliders->total()>0)
                        @php
                        $t=0;
                        @endphp
                        @foreach($sliders as $slider)
                        @php
                        $t++;
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" name="list_check[]" value={{$slider->id}}>
                            </td>
                            <td scope="row">{{$t}}</td>
                            <td>{{$slider->name}}</td>
                            <td><img src="{{url("$slider->image_path")}}" alt="" class="img-fluid" style="max-width:150px; max-height:auto"></td>
                            <td>{{$slider->position}}</td>

                            <?php
                            if ($slider->status == 0) {
                                $color_badge = "badge-dark";
                            } else if ($slider->status == 1) {
                                $color_badge = "badge-success";
                            }
                            ?>
                            <td><span class="badge {{$color_badge}}">{{$statuses[$slider->status]}}</span></td>
                            <td>{{$slider->created_at}}</td>
                            <td>{{$slider->user->name}}</td>

                            <td>
                                @if(request()->input('status')!="trash")

                                @can('slider-edit')
                                <a href="{{route('admin.slider.edit',$slider->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                @endcan

                                @can('slider-delete')
                                <a href="{{route('admin.slider.delete',$slider->id)}}" onclick="return confirm('Bạn có chắc chắn muốn xóa bản ghi này?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
            {{$sliders->links()}}

        </div>
    </div>
</div>

@endsection