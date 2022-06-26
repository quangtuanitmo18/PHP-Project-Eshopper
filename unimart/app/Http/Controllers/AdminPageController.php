<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminPageController extends Controller
{
    //
    public $page_statuses = [
        0 => "Chờ duyệt",
        1 => "Công Khai"
    ];
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'page']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        $keyword = "";
        $list_act = [
            'delete' => 'Xóa tạm thời',
        ];

        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        if ($request->input('status') == "trash") {
            $pages = Page::onlyTrashed()->where('title', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
        } else {
            $pages = Page::where('title', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        }
        $count_page_active = Page::count();
        $count_page_trash = Page::onlyTrashed()->count();
        $count = [$count_page_active, $count_page_trash];
        $page_statuses = $this->page_statuses;

        // return dd($users);
        return view('admin.page.list', compact('pages', 'count', 'list_act', 'page_statuses'));
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if (!empty($list_check)) {



            $act = $request->input('act');
            if ($act == 'delete') {
                Page::destroy($list_check);
                return redirect('admin/page/list')->with('status', 'Bạn đã xóa trang thành công');
            } else if ($act == 'restore') {
                Page::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect('admin/page/list')->with('status', 'Bạn đã khôi phục trang thành công');
            } else if ($act == 'forceDelete') {
                Page::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect('admin/page/list')->with('status', 'Bạn đã xóa vĩnh viễn trang thành công');
            } else {
                return redirect('admin/page/list')->with('status', 'Bạn cần chọn 1 thao tác');
            }
        } else {
            return redirect('admin/page/list')->with('status', 'Bạn cần chọn trang để thực thi');
        }
    }
    function delete($id)
    {
        $page = Page::find($id);
        $page->delete();
        return redirect('admin/page/list')->with('status', 'Bạn đã xóa trang thành công');
    }

    function edit($id)
    {
        $page = Page::find($id);

        $statuses = $this->page_statuses;
        return view('admin.page.edit', compact('page', 'statuses'));
    }

    function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'content' => 'required|string',

            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'title' => 'Mô tả bài viết',
                'content' => "Nội dung bài viết",
            ]
        );
        Page::where('id', $id)->update([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'content' => $request->input('content'),
            'status' => $request->input('status'),

        ]);
        return redirect('admin/page/list')->with('status', 'Đã cập nhật trang thành công');
    }

    function add()
    {
        $statuses = $this->page_statuses;
        return view('admin.page.add', compact('statuses'));
    }

    function store(Request $request)
    {

        $request->validate(
            [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'title' => 'Tiêu đề',
                'content' => "Nội dung bài viết",


            ]
        );

        Page::create([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'user_id' => Auth::id(),
            'content' => $request->input('content'),
            'status' => $request->input('status'),

        ]);
        return redirect('admin/page/list')->with('status', 'Bạn đã thêm trang thành công');
    }
}
