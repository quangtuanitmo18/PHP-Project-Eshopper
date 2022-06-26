@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="{{asset('admin/product/rateyo/rateyo.css')}}">
<style>
    a:hover {
        cursor: pointer;
    }

    .modal-ku {
        width: 850px;
    }

    .modal-dialog {
        top: -100px;
        max-width: 100%;
    }
</style>

@endsection

@section('js')
<script src="{{asset('admin/product/rateyo/rateyo.js')}}"></script>
<script>
    $(document).ready(function() {

        $(function() {
            var rating_comment = $("#rating_comment").val();
            $("#rateYo1").rateYo({
                rating: rating_comment,
                starWidth: "20px",
                readOnly: true
            });
        });


        show_reply_comment();

        function show_reply_comment() {
            var comment_id = $("#id_comment").val();
            var _token = $('input[name="_token"]').val();
            $.ajax({

                type: "POST",
                url: "{{ route('admin.comment.show_reply_ajax') }}",
                data: {
                    comment_id: comment_id,
                    _token: _token,


                },
                success: function(data) {
                    $('.reply_comment').html(data);
                    // alert("hien thi reply comment thanh cong");
                    // $('.alert-comment').css('display', 'block');
                    // $('.alert-comment').html('<p>You have successfully added a comment, your comment will be reviewed by the admin before being published</> ')
                    // $('.alert-comment').fadeOut(9000);
                    // $('.comment').val('');
                }

            });
        }

        $(".status_comment").click(function() {
            var status_id = $(this).data('id_status');
            var _token = $('input[name="_token"]').val();
            var status_comment = $('#status_comment').val();
            if (status_id != status_comment) {
                var id_comment = $('#id_comment').val();
                $.ajax({

                    type: "POST",
                    url: "{{ route('admin.comment.edit_status_ajax') }}",
                    data: {
                        status_id: status_id,
                        id_comment: id_comment,
                        _token: _token,
                    },
                    success: function() {
                        alert('Bạn đã cập nhật trạng thái bình luật thành công.')
                        location.reload();
                        if (status_id == 0) {
                            $('.reply_comment_current').prop("disabled", true);
                        } else if (status_id == 1) {
                            $('.reply_comment_current').prop("disabled", false);
                        }
                    }

                });

            } else {
                return;
            }



        });

        $(".add_reply_comment_product").click(function() {
            var reply_comment = $('#reply_comment_current').val();
            var product_id = $('#product_id').val();
            var user_id = $('#user_id').val();
            var parent_id = $('#id_comment').val();
            var _token = $('input[name="_token"]').val();

            $.ajax({

                type: "POST",
                url: "{{ route('admin.comment.add_reply_ajax') }}",
                data: {
                    reply_comment: reply_comment,
                    product_id: product_id,
                    user_id: user_id,
                    parent_id: parent_id,
                    _token: _token,
                },
                success: function() {
                    show_reply_comment();

                    $('.alert_reply_comment').css('display', 'block');
                    $('.alert_reply_comment').html(' Bạn đã trả lời bình luận thành công.')
                    $('.alert_reply_comment').fadeOut(9000);
                    $('#reply_comment_current').val('');
                }

            });
        });
        $(document).on('click', '.delete_comment_reply', function() {

            if (confirm("Bạn có chắc chắn muốn xóa bình luận này không?")) {
                var id_reply_comment = $(this).data('id_comment_reply');
                var _token = $('input[name="_token"]').val();

                $.ajax({

                    type: "POST",
                    url: "{{ route('admin.comment.delete_reply_ajax') }}",
                    data: {
                        id_reply_comment: id_reply_comment,

                        _token: _token,
                    },
                    success: function() {
                        show_reply_comment();
                        $('.alert_reply_comment').css('display', 'block');
                        $('.alert_reply_comment').html(' Bạn đã xóa bình luận thành công.')
                        $('.alert_reply_comment').fadeOut(9000);
                    }

                });
            }

        });
        $(document).on('click', '.list_comment_reply', function() {

            var id_reply_comment = $(this).data('id_comment_reply');
            var _token = $('input[name="_token"]').val();

            $.ajax({

                type: "POST",
                url: "{{ route('admin.comment.list_reply_ajax') }}",
                data: {
                    id_reply_comment: id_reply_comment,

                    _token: _token,
                },
                success: function(data) {
                    $('.history_edit_comment').html(data);
                }

            });


        });
        $(document).on('click', '.edit_comment_reply', function() {

            var id_reply_comment = $(this).data('id_comment_reply');

            $("input#reply_comment_" + id_reply_comment).prop("disabled", false);
            $("input#reply_comment_" + id_reply_comment).focus();
            var _token = $('input[name="_token"]').val();
            $("input#reply_comment_" + id_reply_comment).blur(function() {

                $("input#reply_comment_" + id_reply_comment).prop("disabled", true);

                if ($("input#reply_comment_" + id_reply_comment).val() == $("input#comment_old_" + id_reply_comment).val()) {
                    return;
                } else {
                    var reply_comment = $(this).val();
                    var _token = $('input[name="_token"]').val();
                    var comment_id = $("#id_comment").val();


                    $.ajax({

                        type: "POST",
                        url: "{{ route('admin.comment.update_reply_ajax') }}",
                        data: {
                            id_reply_comment: id_reply_comment,
                            reply_comment: reply_comment,
                            comment_id: comment_id,
                            _token: _token,
                        },
                        success: function() {
                            alert('Bạn đã cập nhật bình luận thành công!');
                            location.reload();
                            show_reply_comment();

                        }

                    });

                }
            });
        });
        // $(document).on('blur', 'input', function() {
        //     alert('ban da click ra ngoai');
        // });

    });
</script>


@endsection

@section('content')

<div id="content" class="container-fluid">
    <div class="card">
        <div class="card-header font-weight-bold">
            Chỉnh sửa comment
        </div>
        <div class="card-body">
            <form action="{{route("admin.comment.update",$comment->id)}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id_comment" id="id_comment" value="{{$comment->id}}">
                <input type="hidden" name='product_id' id='product_id' value="{{$comment->product_id}}">
                <input type="hidden" name='user_id' id='user_id' value="{{Auth()->user()->id}}">
                <input type="hidden" name='status_comment' id="status_comment" value="{{$comment->status}}">
                <input type="hidden" name='rating_comment' id="rating_comment" value="{{$comment->rating}}">
                <div class="form-group">

                    <div style='position:relative'>
                        <i class="far fa-user"></i>
                        {{$comment->customer->name}}
                        <i style="margin-left:10px;" class="fas fa-calendar-check"></i>
                        {{$comment->created_at}}
                        <span style='position:absolute; top:0px; right:60px;' id="rateYo1">

                        </span>
                        <span style='position:absolute; top:0px; right:0px;'>({{$comment->rating}}/5)</span>

                    </div>



                    <input class="form-control" type="text" name="comment" id="comment" value="{{$comment->comment}}" disabled>


                </div>
                <div class="form-group">

                    <label for="">Trả lời bình luận</label><br>
                    <div class="alert alert-success alert_reply_comment" style="display:none">

                    </div>
                    <div class="reply_comment">

                    </div>

                    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                        <div class="modal-dialog  modal-ku modal-dialog-centered " role="document">
                            <div class="modal-content ">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="exampleModalLongTitle">Lịch sử chỉnh sửa bình luận</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body history_edit_comment">


                                </div>
                                <!-- <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                    <button type="button" class="btn btn-primary">Save changes</button>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="">Trạng thái</label> <span style="color:red;"> (*bạn cần để chế độ công khai trước khi trả lời comment)</span>
                    @foreach($statuses as $k=>$status)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" class="status" name="status" id="{{$k}}" value="{{$k}}" {{$k==$comment->status?"checked":""}}>
                        <label class="form-check-label status_comment" for="{{$k}}" data-id_status="{{$k}}">
                            {{$status}}
                        </label>
                    </div>
                    @endforeach
                    @error('status')
                    <small class="text-danger">{{$message}}</small>
                    @enderror
                </div>

                <div class="form-group">
                    <i style="margin-top:20px;" class="fas fa-user-circle"></i>
                    {{Auth()->user()->name}}
                    <input class="form-control reply_comment_current" type="text" name="reply_comment_current" id="reply_comment_current" value="{{old('comment')}}" {{$comment->status==0?"disabled":""}}>
                </div>

                <button style="margin-bottom:10px; " type="button" class="btn btn-outline-primary btn-sm add_reply_comment_product">Thêm bình luận</button><br>




                <!-- <button type="submit" name="btn-update" class="btn btn-primary">Cập nhật</button> -->
            </form>
        </div>
    </div>
</div>
@endsection