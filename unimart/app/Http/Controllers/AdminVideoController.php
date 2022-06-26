<?php

namespace App\Http\Controllers;

use App\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminVideoController extends Controller
{
    //
    public $video_statuses = [
        0 => "Chờ duyệt",
        1 => "Công Khai"
    ];
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'video']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        $list_act = [
            'delete' => 'Xóa tạm thời',
        ];

        if ($request->input('status') == "trash") {
            $videos = Video::onlyTrashed()->paginate(5);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
        } else {
            $videos = Video::paginate(5);
        }
        $count_video_active = Video::count();
        $count_video_trash = Video::onlyTrashed()->count();
        $count = [$count_video_active, $count_video_trash];
        $statuses = $this->video_statuses;
        // return dd($users);
        return view('admin.video.list', compact('videos', 'count', 'list_act', 'statuses'));
    }
    function show_image_ajax(Request $request)
    {
        $data = $request->all();
        $output = '';

        $video = Video::where('id', (int)$data['id_video'])->first();
        // $product_images = $product->product_images;
        // $output = $output . '<form>' . csrf_field();
        if ($request->session()->get('image_video')) {
            $output .= "<img src=" . url($request->session()->get('image_video')) . " alt='' style='max-width:100%; height:130px; object-fit:cover; '>";
            $request->session()->pull('image_video');
        } else {
            $output .= "<img src=" . url($video->image_path) . " alt='' style='max-width:100%; height:130px; object-fit:cover; '>";
        }
        echo $output;
    }
    function insert_image_ajax(Request $request)
    {

        $video = Video::where('id', $request->id_slider)->first();

        $file = $request->file('image_path');
        $thumbnail_name = $file->getClientOriginalName() . " ";
        $path = $file->move('public/uploads/admin/video', $file->getClientOriginalName());
        $thumbnail_path = 'public/uploads/admin/video/' . $thumbnail_name;
        //unlink($product->thumbnail);
        $request->session()->put('image_video', $thumbnail_path);
        // Product::where('id', $request->id_product)->update([
        //     'thumbnail' => $thumbnail_path,

        // ]);
    }
    function demo_video_ajax(Request $request)
    {
        $id_video = $request->id_video;
        $video = Video::find($id_video);
        $output_title = $video->title;
        $output_desc = $video->desc;
        $output_video = '<iframe width="100%" height="500" src="https://www.youtube.com/embed/' . substr($video->link, 17) . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        $output = [
            'output_title' => $output_title,
            'output_desc' => $output_desc,
            'output_video' => $output_video
        ];
        return json_encode($output);
    }
    function add()
    {
        $statuses = $this->video_statuses;
        return view('admin.video.add', compact('statuses'));
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');

        if (!empty($list_check)) {



            $act = $request->input('act');
            if ($act == 'delete') {
                Video::destroy($list_check);
                return redirect('admin/video/list')->with('status', 'Bạn đã xóa video thành công');
            } else if ($act == 'restore') {
                video::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect('admin/video/list')->with('status', 'Bạn đã khôi phục video thành công');
            } else if ($act == 'forceDelete') {
                Video::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect('admin/video/list')->with('status', 'Bạn đã xóa vĩnh viễn video thành công');
            } else {
                return redirect('admin/video/list')->with('status', 'Bạn cần chọn 1 thao tác');
            }
        } else {
            return redirect('admin/video/list')->with('status', 'Bạn cần chọn trang để thực thi');
        }
    }

    function store(Request $request)
    {

        // return $request->input('desc');
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'link' => 'required|string',
                'status' => 'required|integer',
                'desc' => 'required|string',

            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'title' => 'Tiêu đề',
                'status' => 'Trạng thái',
                'decs' => 'Mô tả',
                'link' => 'đường dẫn',
                'slug' => 'slug'

            ]
        );
        // $image_path = "";

        // if ($request->hasFile("image_path")) {
        //     $file = $request->image_path;
        //     $filename = $file->getClientOriginalName() . " ";


        //     $path = $file->move('public/uploads/admin/video', $file->getClientOriginalName());
        //     $image_path = 'public/uploads/admin/video/' . $filename;
        // }
        $video = Video::create([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'desc' => $request->input('desc'),
            'link' => $request->input('link'),
            'status' => $request->input('status'),

        ]);

        return redirect('admin/video/list')->with('status', 'Đã thêm video thành công');
    }
    function delete($id)
    {
        $video = Video::find($id);
        $video->delete();
        return redirect('admin/video/list')->with('status', 'Đã xóa video thành công');
    }

    function edit($id)
    {
        $video = Video::find($id);
        $statuses = $this->video_statuses;
        return view('admin.video.edit', compact('video', 'statuses'));
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'title' => 'required|string|max:255',
                'link' => 'required|string',
                'status' => 'required|integer',
                'desc' => 'required|string',

            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'title' => 'Tiêu đề',
                'status' => 'Trạng thái',
                'decs' => 'Mô tả',
                'link' => 'đường dẫn',
                'slug' => 'slug'

            ]
        );
        // if ($request->hasFile("image_path")) {
        //     $file = $request->image_path;
        //     $filename = $file->getClientOriginalName() . " ";


        //     $path = $file->move('public/uploads/admin/video', $file->getClientOriginalName());
        //     $image_path = 'public/uploads/admin/video/' . $filename;
        //     Video::where('id', $id)->update([
        //         'image_path' => $image_path
        //     ]);
        // }

        Video::where('id', $id)->update([
            'title' => $request->input('title'),
            'slug' => Str::slug($request->input('title')),
            'desc' => $request->input('desc'),
            'link' => $request->input('link'),
            'status' => $request->input('status'),
        ]);

        return redirect('admin/video/list')->with('status', 'Đã cập nhật video thành công');
    }
}
