<?php

namespace App\Http\Controllers;

use App\Comment;
use App\History_edit_comment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCommentController extends Controller
{
    //
    public $comment_statuses = [
        0 => "Chờ duyệt",
        1 => "Công Khai"
    ];
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'comment']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        $list_act = [
            'delete' => 'Xóa tạm thời',
        ];

        if ($request->input('status') == "trash") {
            $comments = Comment::onlyTrashed()->whereNotNull('customer_id')->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
        } else {
            $comments = Comment::whereNotNull('customer_id')->paginate(10);
        }
        $count_comment_active = Comment::count();
        $count_comment_trash = Comment::onlyTrashed()->count();
        $count = [$count_comment_active, $count_comment_trash];
        $statuses = $this->comment_statuses;
        // return dd($users);
        return view('admin.comment.list', compact('comments', 'count', 'list_act', 'statuses'));
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');

        if (!empty($list_check)) {
            $act = $request->input('act');
            if ($act == 'delete') {
                Comment::destroy($list_check);
                return redirect('admin/comment/list')->with('status', 'Bạn đã xóa comment thành công');
            } else if ($act == 'restore') {
                Comment::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect('admin/comment/list')->with('status', 'Bạn đã khôi phục comment thành công');
            } else if ($act == 'forceDelete') {
                Comment::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect('admin/comment/list')->with('status', 'Bạn đã xóa vĩnh viễn comment thành công');
            } else {
                return redirect('admin/comment/list')->with('status', 'Bạn cần chọn 1 thao tác');
            }
        } else {
            return redirect('admin/comment/list')->with('status', 'Bạn cần chọn trang để thực thi');
        }
    }
    function show_reply_ajax(Request $request)
    {
        $comment_id = $request->comment_id;
        $reply_comment = Comment::where('parent_id', $comment_id)->get();
        $output = "";
        if ($reply_comment->count() == 0) {
            $output = "bình luận này hiện tại chưa có câu trả lời.";
        } else {
            foreach ($reply_comment as $item) {
                $output .= "<div class='1_reply_comment' style='position:relative;'> 
                <p style='margin-bottom:3px;'>
                <i style='margin-right:5px;' class='fas fa-user-circle'></i> " .
                    $item->user->name
                    . "<i style='margin-left:10px; margin-right:5px;' class='fas fa-calendar-check'></i>" .
                    $item->created_at
                    . "</p>
             <input type='hidden' name='comment_old' id='comment_old_" . $item->id . "' value='" . $item->comment . "' >       
            <input style='margin-bottom:10px;' class='form-control' type='text' name='reply_comment' id='reply_comment_" . $item->id . "' value='" . $item->comment . "' disabled>  
            <a  class='list_comment_reply' data-id_comment_reply='" . $item->id . "'  data-toggle='modal' data-target='#exampleModalCenter' ><i class='far fa-list-alt' style='position: absolute;
            top: 0px;
            right: 50px;
            hover:cursor;'></i></a>
            <a  class='edit_comment_reply' data-id_comment_reply='" . $item->id . "'><i class='far fa-edit' style='position: absolute;
            top: 0px;
            right: 20px;
            hover:cursor;'></i></a>
            <a  class='delete_comment_reply' data-id_comment_reply='" . $item->id . "'><i class='far fa-times-circle' style='position: absolute;
            top: 0px;
            right: 0px;
            hover:cursor;'></i></a>
            </div>
            ";
            }
        }
        echo $output;
    }
    function list_reply_ajax(Request $request)
    {
        $history_edit_comment = History_edit_comment::where('comment_id', $request->id_reply_comment)->get();
        $output = "";
        if ($history_edit_comment->count() == 0) {
            $output = "Bình luận này chưa từng được chỉnh sửa.";
        } else {
            foreach ($history_edit_comment as $item) {
                $output .= "<div  style='position:relative;'> 
            <p style='margin-bottom:3px; width:100%;'>
            <i style='margin-right:5px;' class='fas fa-user-circle'></i> " .
                    $item->user->name
                    . "<i style='margin-left:10px; margin-right:5px;' class='fas fa-calendar-check'></i>" .
                    $item->created_at
                    . "</p>
            <p style='width:100%'>" . $item->comment_edit . "</p>
        </div>
        ";
            }
        }
        echo $output;
    }
    function update_reply_ajax(Request $request)
    {
        Comment::where('id', $request->id_reply_comment)->update([
            'comment' => $request->reply_comment,

        ]);

        History_edit_comment::create([
            'comment_edit' => $request->reply_comment,
            'user_id' => Auth::id(),
            'comment_id' => $request->id_reply_comment,
        ]);
    }
    function delete_reply_ajax(Request $request)
    {
        $id_reply_comment = $request->id_reply_comment;
        $comment = Comment::find($id_reply_comment);
        $comment->delete();
        $comment->forceDelete();
    }
    function add_reply_ajax(Request $request)
    {

        $reply_comment = Comment::create([
            'comment' => $request->reply_comment,
            'product_id' => $request->product_id,
            'user_id' => $request->user_id,
            'parent_id' => $request->parent_id,

            'status' => 1,
        ]);
        History_edit_comment::create([
            'comment_edit' => $request->reply_comment,
            'user_id' => Auth::id(),
            'comment_id' => $reply_comment->id,
        ]);
    }

    function delete($id)
    {
        $comment = Comment::find($id);
        $comment->delete();
        return redirect('admin/comment/list')->with('status', 'Đã xóa comment thành công');
    }
    function status(Request $request)
    {
    }

    function edit($id)
    {
        $comment = Comment::find($id);
        $statuses = $this->comment_statuses;
        $reply_comment = Comment::where('parent_id', $id)->orderBy('created_at')->get();
        return view('admin.comment.edit', compact('comment', 'statuses', 'reply_comment'));
    }
    function edit_status_ajax(Request $request)
    {
        Comment::where('id', $request->id_comment)->update([
            'status' => $request->status_id,
        ]);
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'status' => 'required|integer',

            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [

                'status' => 'Trạng thái',


            ]
        );

        Comment::where('id', $id)->update([
            'status' => $request->input('status'),
        ]);

        return redirect('admin/comment/list')->with('status', 'Đã cập nhật comment thành công');
    }
}
