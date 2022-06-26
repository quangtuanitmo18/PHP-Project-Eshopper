@extends('layouts.admin')


@section('css')
<style>
    .modal-ku {
        margin: 25px auto;
        max-width: 1200px !important;
    }

    .modal-content {
        height: 785px;
        overflow-y: auto;
    }
</style>
@endsection


@section('js')


<script>
    $(document).ready(function() {
        $(document).on('click', '.click_demo_video', function() {
            var id_video = $(this).data('id_video');



            var form_data = new FormData();

            form_data.append("id_video", id_video);
            $.ajax({
                url: "{{ route('admin.video.demo_video_ajax') }}",
                type: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: form_data,
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function(data) {
                    $('#video_title').html(data.output_title);
                    $('#video_desc').html(data.output_desc);

                    $('#demo_video').html(data.output_video);
                }
            });
        });



    });
</script>

@endsection


@section('content')

<div id="content" class="container-fluid">
    <div class="card">
        @if(session('status'))
        <div class="alert alert-success">
            {{session('status')}}
        </div>
        @endif
        <div class="card-header font-weight-bold d-flex justify-content-between align-items-center">
            <h5 class="m-0 ">Danh sách video</h5>
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
            <form action="{{url('admin/video/action')}}">

                @can('video-action')
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


                            <th scope="col">Mô tả</th>
                            <th scope="col">video</th>
                            <th scope="col">Trạng thái</th>
                            <th scope="col">Ngày tạo</th>


                            <th scope="col">Tác vụ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if($videos->total()>0)
                        @php
                        $t=0;
                        @endphp

                        @foreach($videos as $video)
                        @php
                        $t++;
                        @endphp
                        <tr>
                            <td>
                                <input type="checkbox" name="list_check[]" value={{$video->id}}>
                            </td>
                            <td scope="row">{{$t}}</td>
                            <td>{{$video->title}}</td>


                            <td>{!! $video->desc !!}</td>
                            <td> <button type="button" class="btn btn-outline-primary btn-sm click_demo_video" data-id_video="{{$video->id}}" data-toggle="modal" data-target="#exampleModalCenter">
                                    demo video
                                </button></td>
                            <?php
                            if ($video->status == 0) {
                                $color_badge = "badge-dark";
                            } else if ($video->status == 1) {
                                $color_badge = "badge-success";
                            }
                            ?>
                            <td><span class="badge {{$color_badge}}">{{$statuses[$video->status]}}</span></td>
                            <td>{{$video->created_at}}</td>

                            <td>
                                @if(request()->input('status')!="trash")

                                @can('video-edit')
                                <a href="{{route('admin.video.edit',$video->id)}}" class="btn btn-success btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Edit"><i class="fa fa-edit"></i></a>
                                @endcan
                                @can('video-delete')
                                <a href="{{route('admin.video.delete',$video->id)}}" onclick="return confirm('Bạn có chắc chắn muốn xóa bản ghi này?')" class="btn btn-danger btn-sm rounded-0 text-white" type="button" data-toggle="tooltip" data-placement="top" title="Delete"><i class="fa fa-trash"></i></a>
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
            {{$videos->links()}}

            <!-- Modal -->
            <div class="  modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class=" modal-ku modal-dialog modal-lg modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="video_title"></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>

                        </div>
                        <div class="modal-header">
                            <p id="video_desc"></p>

                        </div>
                        <div class="modal-body" id="demo_video">

                        </div>
                        <!-- <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="button" class="btn btn-primary">Save changes</button>
                        </div> -->
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

@endsection