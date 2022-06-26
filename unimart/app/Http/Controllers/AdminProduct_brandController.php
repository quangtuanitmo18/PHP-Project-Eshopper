<?php

namespace App\Http\Controllers;

use App\Product_brand;
use App\Slider;
use Illuminate\Http\Request;

class AdminProduct_brandController extends Controller
{
    //
    public $brand_statuses = [
        0 => "Chờ duyệt",
        1 => "Công Khai"
    ];
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product_brand']);
            return $next($request);
        });
    }
    function list(Request $request)
    {
        $list_act = [
            'delete' => 'Xóa tạm thời',
        ];

        if ($request->input('status') == "trash") {
            $product_brands = Product_brand::onlyTrashed()->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
        } else {
            $product_brands = Product_brand::paginate(10);
        }
        $count_brand_active = Product_brand::count();
        $count_brand_trash = Product_brand::onlyTrashed()->count();
        $count = [$count_brand_active, $count_brand_trash];
        $statuses = $this->brand_statuses;
        // return dd($users);
        return view('admin.brand.list', compact('product_brands', 'count', 'list_act', 'statuses'));
    }
    function show_image_ajax(Request $request)
    {
        $data = $request->all();
        $output = '';

        $brand = Product_brand::where('id', (int)$data['id_brand'])->first();
        // $product_images = $product->product_images;
        // $output = $output . '<form>' . csrf_field();
        if ($request->session()->get('image_brand')) {
            $output .= "<img src=" . url($request->session()->get('image_brand')) . " alt='' style='max-width:100%; height:130px; object-fit:cover; '>";
            $request->session()->pull('image_brand');
        } else {
            //$output .= "<img src=" . url($brand->image) . " alt='' style='max-width:100%; height:130px; object-fit:cover; '>";
            if ($brand->image != '') {
                $output .= "<img src='" . url($brand->image) . "' alt='' style='max-width:100%; height:130px; object-fit:cover; '>  ";

                $output .= "<a class='delete_image' data-id_brand='" . $brand->id . "' style='color:red;height: 25px;' > <i class='fas fa-times-circle'></i></a>";
            } else {
                $output .= "<img src='' alt='' style='max-width:100%; height:130px; object-fit:cover; '>  ";
            }
        }
        echo $output;
    }
    function delete_image_ajax(Request $request)
    {
        $id_brand = $request->id_brand;
        Product_brand::where('id', $id_brand)->update([
            'image' => '',
        ]);
    }
    function insert_image_ajax(Request $request)
    {

        $brand = Product_brand::where('id', $request->id_brand)->first();

        $file = $request->file('image');
        $thumbnail_name = $file->getClientOriginalName() . " ";
        $path = $file->move('public/uploads/admin/brand', $file->getClientOriginalName());
        $thumbnail_path = 'public/uploads/admin/brand/' . $thumbnail_name;
        //unlink($product->thumbnail);
        $request->session()->put('image_brand', $thumbnail_path);
        // Product::where('id', $request->id_product)->update([
        //     'thumbnail' => $thumbnail_path,

        // ]);
    }
    function add()
    {
        $statuses = $this->brand_statuses;
        return view('admin.brand.add', compact('statuses'));
    }
    function action(Request $request)
    {
        $list_check = $request->input('list_check');

        if (!empty($list_check)) {



            $act = $request->input('act');
            if ($act == 'delete') {
                Product_brand::destroy($list_check);
                return redirect('admin/brand/list')->with('status', 'Bạn đã xóa thương hiệu thành công');
            } else if ($act == 'restore') {
                Slider::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect('admin/brand/list')->with('status', 'Bạn đã khôi phục thương hiệu thành công');
            } else if ($act == 'forceDelete') {
                Slider::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect('admin/brand/list')->with('status', 'Bạn đã xóa vĩnh viễn thương hiệu thành công');
            } else {
                return redirect('admin/brand/list')->with('status', 'Bạn cần chọn 1 thao tác');
            }
        } else {
            return redirect('admin/brand/list')->with('status', 'Bạn cần chọn trang để thực thi');
        }
    }

    function store(Request $request)
    {

        $request->validate(
            [
                'name' => 'required|string|max:255',
                'position' => 'required|integer',
                'status' => 'required|integer',
                'desc' => 'required|string'
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
                'desc' => 'Mô tả'

            ]
        );
        $image_path = "";

        if ($request->hasFile("image")) {
            $file = $request->image;
            $filename = $file->getClientOriginalName() . " ";


            $path = $file->move('public/uploads/admin/brand', $file->getClientOriginalName());
            $image_path = 'public/uploads/admin/brand/' . $filename;
        }
        $product_brand = Product_brand::create([
            'name' => $request->input('name'),
            'slug' => \Str::slug($request->input('name')),
            'position' => $request->input('position'),
            'user_id' => \Auth::id(),
            'status' => $request->input('status'),
            'desc' => $request->input('desc'),
            'image' => $image_path,

        ]);

        return redirect('admin/brand/list')->with('status', 'Đã thêm thương hiệu thành công');
    }
    function delete($id)
    {
        $brand = Product_brand::find($id);
        $brand->delete();
        return redirect('admin/brand/list')->with('status', 'Đã xóa thương hiệu thành công');
    }

    function edit($id)
    {
        $product_brand = Product_brand::find($id);
        $statuses = $this->brand_statuses;
        return view('admin.brand.edit', compact('product_brand', 'statuses'));
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'position' => 'required|integer',
                'status' => 'required|integer',
                'desc' => 'required|string',

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
                'desc' => 'Mô tả',

            ]
        );
        if ($request->hasFile("image")) {
            $file = $request->image;
            $filename = $file->getClientOriginalName() . " ";


            $path = $file->move('public/uploads/admin/brand', $file->getClientOriginalName());
            $image_path = 'public/uploads/admin/brand/' . $filename;
            Product_brand::where('id', $id)->update([
                'image' => $image_path
            ]);
        }

        Product_brand::where('id', $id)->update([
            'name' => $request->input('name'),
            'slug' => \Str::slug($request->input('name')),
            'position' => $request->input('position'),
            'status' => $request->input('status'),
            'desc' => $request->input('desc')
        ]);

        return redirect('admin/brand/list')->with('status', 'Đã cập nhật thương hiệu thành công');
    }
}
