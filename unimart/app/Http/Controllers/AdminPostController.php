<?php

namespace App\Http\Controllers;

use App\Post;
use App\Post_cat;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminPostController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'post']);
            return $next($request);
        });
    }
    public $post_statuses = [

        0 => 'Chờ duyệt',
        1 => 'Công khai',

        // như đã quy ước trong database
    ];
    function list(Request $request)
    {
        $keyword = "";
        $statuses = $this->post_statuses;
        $list_act = [
            'delete' => 'Xoá tạm thời'
        ];
        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        if ($request->input('status') == "public") {
            $posts = Post::where('status', 0)->where('title', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->input('status') == "pending") {
            $posts = Post::where('status', 1)->where('title', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->input('status') == "delete") {
            $posts = Post::onlyTrashed()->where('title', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
        } else if ($request->input('status') == "all_posts") $posts = Post::where('title', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        else $posts = Post::where('title', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);

        $count_post_public = Post::where('status', 0)->count();
        $count_post_pending = Post::where('status', 1)->count();
        $count_post_delete = Post::onlyTrashed()->count();
        $count_post_all = $count_post_public + $count_post_pending + $count_post_delete;
        $count = [$count_post_all, $count_post_public, $count_post_pending, $count_post_delete];
        return view('admin.post.list', compact('posts', 'statuses', 'count', 'list_act'));
    }
    function delete($id)
    {
        $post = Post::find($id);
        $post->delete();
        return redirect('admin/post/list')->with('status', 'Bạn đã xóa bài viết thành công');
    }
    function show_image_ajax(Request $request)
    {
        $data = $request->all();
        $output = '';

        $post = Post::where('id', (int)$data['id_post'])->first();
        // $product_images = $product->product_images;
        // $output = $output . '<form>' . csrf_field();
        if ($request->session()->get('image_post')) {

            $output .= "<img src=" . url($request->session()->get('image_post')) . " alt='' style='max-width:100%; height:130px; object-fit:cover; '>";
            $request->session()->pull('image_post');
        } else {
            //$output .= "<img src=" . url($post->thumbnail) . " alt='' style='max-width:100%; height:130px; object-fit:cover; '>";
            if ($post->thumbnail != '') {
                $output .= "<img src='" . url($post->thumbnail) . "' alt='' style='max-width:100%; height:130px; object-fit:cover; '>  ";

                $output .= "<a class='delete_image' data-id_post='" . $post->id . "' style='color:red;height: 25px;' > <i class='fas fa-times-circle'></i></a>";
            } else {
                $output .= "<img src='' alt='' style='max-width:100%; height:130px; object-fit:cover; '>  ";
            }
        }

        echo $output;
    }
    function insert_image_ajax(Request $request)
    {

        $post = Post::where('id', $request->id_post)->first();

        $file = $request->file('thumbnail');
        $thumbnail_name = $file->getClientOriginalName() . " ";
        $path = $file->move('public/uploads/admin/post', $file->getClientOriginalName());
        $thumbnail_path = 'public/uploads/admin/post/' . $thumbnail_name;
        $request->session()->put('image_post', $thumbnail_path);
        //unlink($product->thumbnail);
        // Post::where('id', $request->id_post)->update([
        //     'thumbnail' => $thumbnail_path,

        // ]);
    }

    function delete_image_ajax(Request $request)
    {
        $id_post = $request->id_post;
        Post::where('id', $id_post)->update([
            'thumbnail' => '',
        ]);
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if (!empty($list_check)) {

            $act = $request->input('act');
            if ($act == 'delete') {
                Post::destroy($list_check);
                return redirect('admin/post/list')->with('status', 'Bạn đã xóa bài viết thành công');
            } else if ($act == 'restore') {
                Post::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect('admin/post/list')->with('status', 'Bạn đã khôi phục bài viết thành công');
            } else if ($act == 'forceDelete') {
                Post::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect('admin/post/list')->with('status', 'Bạn đã xóa vĩnh viễn bài viết thành công');
            } else {
                return redirect('admin/post/list')->with('status', 'Bạn cần chọn 1 thao tác');
            }
        } else {
            return redirect('admin/post/list')->with('status', 'Bạn cần chọn bài viết để thực thi');
        }
    }
    function edit($id)
    {
        $post = Post::find($id);
        $post_cats = Post_cat::all();
        $statuses = $this->post_statuses;

        return view('admin.post.edit', compact('post', 'post_cats', 'statuses'));
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'post_cat_id' => 'required'
            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'title' => 'Mô tả bài viết',
                'content' => "Nội dung bài viết",
                'post_cat_id' => 'Danh mục'
            ]
        );
        if ($request->hasFile("thumbnail")) {
            $file = $request->thumbnail;
            $filename = $file->getClientOriginalName() . " ";


            $path = $file->move('public/uploads/admin/post', $file->getClientOriginalName());
            $thumbnail = 'public/uploads/admin/post/' . $filename;
            Post::where('id', $id)->update([
                'thumbnail' => $thumbnail
            ]);
        }
        if ($request->input('featured')) {
            $post_featured = 1;
        }
        Post::where('id', $id)->update([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'content' => $request->input('content'),
            'post_featured' => $post_featured,
            'post_cat_id' => $request->input('post_cat_id'),
            'status' => $request->input('status'),
        ]);
        return redirect('admin/post/list')->with('status', 'Đã cập nhật bài viết thành công');
    }

    function add()
    {
        $statuses = $this->post_statuses;
        $post_cats = Post_cat::all();
        return view('admin.post.add', compact('statuses', 'post_cats'));
    }

    function store(Request $request)
    {

        $request->validate(
            [
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'status' => 'required|string',
                'post_cat_id' => 'required'
            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'title' => 'Mô tả bài viết',
                'content' => "Nội dung bài viết",
                'post_cat_id' => 'Danh mục',
                'thumbnail' => 'Hình ảnh'

            ]
        );
        $thumbnail = "";
        if ($request->hasFile("thumbnail")) {
            $file = $request->thumbnail;
            $filename = $file->getClientOriginalName() . " ";
            $path = $file->move('public/uploads/admin/post', $file->getClientOriginalName());
            $thumbnail = 'public/uploads/admin/post/' . $filename;
        }
        $post_featured = 0;
        if ($request->input('featured')) {
            $post_featured = 1;
        }
        Post::create([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'user_id' => Auth::id(),
            'post_cat_id' => $request->input('post_cat_id'),
            'post_featured' => $post_featured,
            'content' => $request->input('content'),
            'status' => $request->input('status'),
            'thumbnail' => $thumbnail,

        ]);
        return redirect('admin/post/list')->with('status', 'Bạn đã thêm bài viết thành công');
    }
}
