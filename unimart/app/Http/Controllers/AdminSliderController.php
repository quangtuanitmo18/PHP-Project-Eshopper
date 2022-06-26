<?php

namespace App\Http\Controllers;

use App\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminSliderController extends Controller
{
    //
    public $slider_statuses = [
        0 => "Chờ duyệt",
        1 => "Công Khai"
    ];
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'slider']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        $list_act = [
            'delete' => 'Xóa tạm thời',
        ];

        if ($request->input('status') == "trash") {
            $sliders = Slider::onlyTrashed()->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
        } else {
            $sliders = Slider::paginate(10);
        }
        $count_slider_active = Slider::count();
        $count_slider_trash = Slider::onlyTrashed()->count();
        $count = [$count_slider_active, $count_slider_trash];
        $statuses = $this->slider_statuses;
        // return dd($users);
        return view('admin.slider.list', compact('sliders', 'count', 'list_act', 'statuses'));
    }
    function show_image_ajax(Request $request)
    {
        $data = $request->all();
        $output = '';

        $slider = Slider::where('id', (int)$data['id_slider'])->first();
        // $product_images = $product->product_images;
        // $output = $output . '<form>' . csrf_field();
        if ($request->session()->get('image_slider')) {
            $output .= "<img src=" . url($request->session()->get('image_slider')) . " alt='' style='max-width:100%; height:130px; object-fit:cover; '>";
            $request->session()->pull('image_slider');
        } else {
            // $output .= "<img src=" . url($slider->image_path) . " alt='' style='max-width:100%; height:130px; object-fit:cover; '>";
            if ($slider->image_path != '') {
                $output .= "<img src='" . url($slider->image_path) . "' alt='' style='max-width:100%; height:130px; object-fit:cover; '>  ";

                $output .= "<a class='delete_image' data-id_slider='" . $slider->id . "' style='color:red;height: 25px;' > <i class='fas fa-times-circle'></i></a>";
            } else {
                $output .= "<img src='' alt='' style='max-width:100%; height:130px; object-fit:cover; '>  ";
            }
        }
        echo $output;
    }
    function insert_image_ajax(Request $request)
    {

        $slider = Slider::where('id', $request->id_slider)->first();

        $file = $request->file('image_path');
        $thumbnail_name = $file->getClientOriginalName() . " ";
        $path = $file->move('public/uploads/admin/slider', $file->getClientOriginalName());
        $thumbnail_path = 'public/uploads/admin/slider/' . $thumbnail_name;
        //unlink($product->thumbnail);
        $request->session()->put('image_slider', $thumbnail_path);
        // Product::where('id', $request->id_product)->update([
        //     'thumbnail' => $thumbnail_path,

        // ]);
    }
    function delete_image_ajax(Request $request)
    {
        $id_slider = $request->id_slider;
        Slider::where('id', $id_slider)->update([
            'image_path' => '',
        ]);
    }
    function add()
    {
        $statuses = $this->slider_statuses;
        return view('admin.slider.add', compact('statuses'));
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');

        if (!empty($list_check)) {



            $act = $request->input('act');
            if ($act == 'delete') {
                Slider::destroy($list_check);
                return redirect('admin/slider/list')->with('status', 'Bạn đã xóa slider thành công');
            } else if ($act == 'restore') {
                Slider::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect('admin/slider/list')->with('status', 'Bạn đã khôi phục slider thành công');
            } else if ($act == 'forceDelete') {
                Slider::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect('admin/slider/list')->with('status', 'Bạn đã xóa vĩnh viễn slider thành công');
            } else {
                return redirect('admin/slider/list')->with('status', 'Bạn cần chọn 1 thao tác');
            }
        } else {
            return redirect('admin/slider/list')->with('status', 'Bạn cần chọn trang để thực thi');
        }
    }

    function store(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'position' => 'required|integer',
                'status' => 'required|integer',
                'decs' => 'required|string'
            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tiêu đề',
                'position' => 'Vị trí',
                'status' => 'Trạng thái',
                'decs' => 'Mô tả'

            ]
        );
        $image_path = "";

        if ($request->hasFile("image_path")) {
            $file = $request->image_path;
            $filename = $file->getClientOriginalName() . " ";


            $path = $file->move('public/uploads/admin/slider', $file->getClientOriginalName());
            $image_path = 'public/uploads/admin/slider/' . $filename;
        }
        $slider = Slider::create([
            'name' => $request->input('name'),
            'position' => $request->input('position'),
            'user_id' => Auth::id(),
            'status' => $request->input('status'),
            'decs' => $request->input('decs'),
            'image_path' => $image_path,

        ]);

        return redirect('admin/slider/list')->with('status', 'Đã thêm slider thành công');
    }
    function delete($id)
    {
        $slider = Slider::find($id);
        $slider->delete();
        return redirect('admin/slider/list')->with('status', 'Đã xóa slider thành công');
    }

    function edit($id)
    {
        $slider = Slider::find($id);
        $statuses = $this->slider_statuses;
        return view('admin.slider.edit', compact('slider', 'statuses'));
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'position' => 'required|integer',
                'status' => 'required|integer',
                'decs' => 'required|string',

            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tiêu đề',
                'position' => 'Vị trí',
                'status' => 'Trạng thái',
                'decs' => 'Mô tả',

            ]
        );
        if ($request->hasFile("image_path")) {
            $file = $request->image_path;
            $filename = $file->getClientOriginalName() . " ";


            $path = $file->move('public/uploads/admin/slider', $file->getClientOriginalName());
            $image_path = 'public/uploads/admin/slider/' . $filename;
            Slider::where('id', $id)->update([
                'image_path' => $image_path
            ]);
        }

        Slider::where('id', $id)->update([
            'name' => $request->input('name'),
            'position' => $request->input('position'),
            'status' => $request->input('status'),
            'decs' => $request->input('decs')
        ]);

        return redirect('admin/slider/list')->with('status', 'Đã cập nhật slider thành công');
    }
}
