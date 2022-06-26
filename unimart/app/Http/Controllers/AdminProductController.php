<?php

namespace App\Http\Controllers;

use App\Product;
use App\Product_cat;
use App\Product_image;
use App\Product_tag;
use App\Tag;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Support\Str;
use App\Imports\excel_import_product;
use App\Exports\excel_export_product;
use App\Product_brand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel as FacadesExcel;
use SebastianBergmann\Environment\Console;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class AdminProductController extends Controller
{
    //
    public $product_statuses = [
        0 => 'Còn hàng',
        1 => 'Đang về hàng',
        2 => 'Hết hàng'
    ];
    public $product_browses = [
        0 => "Chờ duyệt",
        1 => "Công khai"
    ];

    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'product']);
            return $next($request);
        });
    }

    function dropzone_show($id)
    {
        $product = Product::where('id', $id)->first();
        return view('admin.product.dropzone_show_detail_images', compact('product'));
    }
    function dropzone_upload(Request $request, $id)
    {

        $product = Product::where('id', $id)->first();
        if (!File::exists("public/uploads/admin/product/" . $product->id)) {
            File::makeDirectory("public/uploads/admin/product/" . $product->id);
        }
        $image = $request->file('file');


        $imageName = time() . '.' . $image->extension();
        $thumbnail_name = $image->getClientOriginalName() . " ";
        $path = $image->move("public/uploads/admin/product/" . $product->id, $image->getClientOriginalName());
        $thumbnail_path = "public/uploads/admin/product/" . $product->id . '/' . $thumbnail_name;
        Product_image::create([
            'image_path' => $thumbnail_path,
            'image_name' => $thumbnail_name,
            'product_id' => $request->id_product,

        ]);
        return response()->json(['success' => $imageName]);
    }

    function dropzone_fetch(Request $request)
    {
        $product = Product::where('id', $request->id_product)->first();
        $images = $product->product_images;
        // $images = \File::allFiles(public_path('uploads/admin/product/' . $product->id));
        $output = '<div class="row">';
        foreach ($images as $image) {
            $output .= '
      <div class="col-md-2" style="margin-bottom:16px;" align="center">
                <img src="' . asset('uploads/admin/product/' . $product->id . '/' . $image->image_name) . '" class="img-thumbnail" width="175" height="175" style="height:175px;" />
                <button type="button" class="btn btn-link remove_image" id="' . $image->image_name . '">Remove</button>
            </div>
      ';
        }
        $output .= '</div>';
        echo $output;
    }

    function dropzone_delete(Request $request)
    {
        $product = Product::where('id', $request->id_product)->first();
        $name_image = $request->get('name');
        if ($request->get('name')) {
            \File::delete('public/uploads/admin/product/' . $product->id . '/' . $name_image);
            $image = Product_image::where('image_name', $name_image)->where('product_id', $product->id)->first();
            $image->delete();
        }
        $file_in_directory = scandir('public/uploads/admin/product/' . $product->id);
        $items_count = count($file_in_directory);

        if ($items_count <= 2) {
            \File::deleteDirectory('public/uploads/admin/product/' . $product->id);
            echo "directory_empty";
        }
    }



    function excel_import(Request $request)
    {
        $path = $request->file('import_excel_product')->getRealPath(); // execel_import chỗ này là tên class
        FacadesExcel::import(new excel_import_product, $path);
        // $thumbnail = "";
        // if ($request->hasFile("thumbnail")) {
        //     $file = $request->thumbnail;
        //     $filename = $file->getClientOriginalName() . " ";


        //     $path = $file->move('public/uploads/admin/product', $file->getClientOriginalName());
        //     $thumbnail = 'public/uploads/admin/product/' . $filename;
        // }

        return back(); // Return lại trang đã gửi yêu cầu
    }
    function excel_export()
    {
        return FacadesExcel::download(new excel_export_product, 'product_excel.xlsx');
    }
    function list(Request $request)
    {
        $keyword = "";
        $statuses = $this->product_statuses;
        $browses = $this->product_browses;

        $list_act = [
            'delete' => 'Xóa tạm thời',
        ];

        if ($request->input('keyword')) {
            $keyword = $request->input('keyword');
        }
        if ($request->input('status') == "trash") {
            $products = Product::onlyTrashed()->where('name', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
            $list_act = [
                'restore' => 'Khôi phục',
                'forceDelete' => 'Xóa vĩnh viễn'
            ];
        } else if ($request->input('status') == "still") {
            $products = Product::where('qty', '>', 0)->where('name', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->input('status') == 'out-of-stock') {
            $products = Product::where('qty', '=', 0)->where('name', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        } else if ($request->input('status') == 'in-stock') {
            $products = Product::where('status', 'LIKE', '%đang về hàng%')->where('name', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        } else {
            $products = Product::where('name', 'LIKE', "%{$keyword}%")->orderBy('created_at', 'desc')->paginate(10);
        }
        $count_product_active = Product::count();
        $count_product_trash = Product::onlyTrashed()->count();

        $count_product_still = Product::where('qty', '>', 0)->count();
        $count_product_OutOfStock = Product::where('qty', '=', 0)->count();
        $count_product_InStock = Product::where('status', 'LIKE', '%đang về hàng%')->count();

        $count = [$count_product_active, $count_product_trash, $count_product_still, $count_product_OutOfStock, $count_product_InStock];


        // return dd($users);

        return view('admin.product.list', compact('products', 'count', 'list_act', 'statuses', 'browses'));
        // $products=Product::all();
        // return view('admin.product.list',compact('products'));
    }

    function show_image_ajax(Request $request)
    {
        $data = $request->all();
        $output_image = '';

        $product = Product::where('id', (int)$data['id_product'])->first();
        // $product_images = $product->product_images;
        // $output = $output . '<form>' . csrf_field();
        $url_image = url($request->session()->get('image_product'));
        if ($request->session()->get('image_product')) {
            $output_image .= "<img src='" . url($request->session()->get('image_product')) . "' alt='' style='max-width:100%; height:130px; object-fit:cover; '>
            ";
        } else {


            if ($product->thumbnail != '') {
                $output_image .= "<img src='" . url($product->thumbnail) . "' alt='' style='max-width:100%; height:130px; object-fit:cover; '>  ";

                $output_image .= "<a class='delete_image' data-id_product='" . $product->id . "' style='color:red;height: 25px;' > <i class='fas fa-times-circle'></i></a>";
            } else {
                $output_image .= "<img src='' alt='' style='max-width:100%; height:130px; object-fit:cover; '>  ";
            }
        }
        $output = array(
            'output_image' => $output_image,
            'link_image' => $url_image,
        );
        return json_encode($output);
    }
    function insert_image_ajax(Request $request)
    {

        $product = Product::where('id', $request->id_product)->first();

        $file = $request->file('thumbnail');
        $thumbnail_name = $file->getClientOriginalName() . " ";
        $path = $file->move("public/uploads/admin/product/" . $product->id, $file->getClientOriginalName());


        $thumbnail_path = "public/uploads/admin/product/" . $product->id . '/' . $thumbnail_name;

        $request->session()->flash('image_product', $thumbnail_path);
        // Product::where('id', $request->id_product)->update([
        //     'thumbnail' => $thumbnail_path,

        // ]);
    }

    function delete_image_ajax(Request $request)
    {
        $id_product = $request->id_product;
        Product::where('id', $id_product)->update([
            'thumbnail' => '',
        ]);
    }

    function add(Request $request)
    {
        $statuses = $this->product_statuses;
        $browses = $this->product_browses;
        $product_cats = Product_cat::all();
        $brands = Product_brand::where('status', 1)->get();

        $tags = Tag::all();

        return view('admin.product.add', compact('statuses', 'product_cats', 'browses', 'tags', 'brands'));
    }

    function store(Request $request)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'content' => 'required|string',
                'price' => 'required|integer',
                'price_cost' => 'required|integer',

                'qty' => 'required|integer',
                'status' => 'required|integer',
                'browse' => 'required|integer',

                'product_cat_id' => 'required',
                'product_brand' => 'required'
            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên sản phẩm',
                'description' => "Mô tả ngắn",
                'content' => 'Nội dung',
                'price' => 'Giá bán',
                'price_cost' => 'Giá nhập',
                'qty' => 'Số lượng',
                'status' => 'Tình trạng',
                'status' => 'Trạng thái',

                'thumbnail' => 'Hình ảnh',
                'product_cat_id' => 'Danh mục',
                'product_brand' => 'Thương hiệu'

            ]
        );
        $thumbnail = "";

        $featured = 0;
        if ($request->input('featured')) {
            $featured = 1;
        }
        $best_seller = 0;
        if ($request->input('best_seller')) {
            $best_seller = 1;
        }
        $product = Product::create([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'description' => $request->input('description'),
            'content' => $request->input('content'),
            'price' => $request->input('price'),
            'price_cost' => $request->input('price_cost'),

            'product_cat_id' => $request->input('product_cat_id'),
            'product_brand_id' => $request->input('product_brand'),
            'user_id' => Auth::id(),
            'qty' => $request->input('qty'),
            'status' => $request->input('status'),
            'browse' => $request->input('browse'),
            'featured' => $featured,
            'best_seller' => $best_seller,
            'thumbnail' => $thumbnail,
        ]);
        if ($request->hasFile("thumbnail")) {
            if (!File::exists("public/uploads/admin/product/" . $product->id)) {
                File::makeDirectory("public/uploads/admin/product/" . $product->id);
            }
            $file = $request->thumbnail;
            $filename = $file->getClientOriginalName() . " ";
            $path = $file->move("public/uploads/admin/product/" . $product->id, $file->getClientOriginalName());
            $thumbnail = "public/uploads/admin/product/" . $product->id . '/' . $filename;
            Product::where('id', $product->id)->update([
                'thumbnail' => $thumbnail,

            ]);
        }
        $tags_id = [];
        if (!empty($request->product_tags)) {
            foreach ($request->input('product_tags') as $item) {
                $tag_instance = Tag::firstOrCreate(['name' => $item]);
                $tags_id[] = $tag_instance->id;
            }
        }
        $product->tags()->attach($tags_id);


        if (!File::exists("public/uploads/admin/product/" . $product->id)) {
            File::makeDirectory("public/uploads/admin/product/" . $product->id);
        }
        if ($request->hasFile("thumbnail_detail")) {
            $file = $request->thumbnail_detail;
            foreach ($file as $file_item) {
                $thumbnail_name = $file_item->getClientOriginalName() . " ";
                $path = $file_item->move('public/uploads/admin/product/' . $product->id, $file_item->getClientOriginalName());
                $thumbnail_path = 'public/uploads/admin/product/' . $product->id . '/' . $thumbnail_name;

                Product_image::create([
                    'image_path' => $thumbnail_path,
                    'image_name' => $thumbnail_name,
                    'product_id' => $product->id

                ]);
            }
        }


        return redirect('admin/product/list')->with('status', 'Đã thêm sản phẩm thành công');
    }

    function edit($id)
    {
        $product = Product::find($id);
        $browses = $this->product_browses;
        $product_cats = Product_cat::all();
        $statuses = $this->product_statuses;
        $product_images = $product->product_images;
        $product_tags = $product->tags;
        $brands = Product_brand::where('status', 1)->get();
        $tags = Tag::all();
        return view('admin.product.edit', compact('product', 'product_cats', 'statuses', 'product_images', 'browses', 'product_tags', 'tags', 'brands'));
    }
    function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'content' => 'required|string',
                'price' => 'required|integer',
                'price_cost' => 'required|integer',

                'qty' => 'required|integer',
                'status' => 'required|integer',
                'browse' => 'required|integer',
                'product_brand' => 'required',
                'product_cat_id' => 'required',
            ],
            [
                'required' => ":attribute không được để trống",
                // 'min' =>':attribute có độ dài ít nhất :min kí tự',
                'max' => ':attribute có độ dài tối đa :max kí tự',
            ],
            [
                'name' => 'Tên người dùng',
                'description' => "Mô tả ngắn",
                'content' => 'Nội dung',
                'price' => 'Giá bán',
                'price_cost' => 'Giá nhập',

                'qty' => 'Số lượng',
                'status' => 'Tình trạng',
                'browse' => 'Trạng thái',
                'thumbnail' => 'Hình ảnh',
                'product_cat_id' => 'Danh mục',
                'product_brand' => 'Thương hiệu'

            ]
        );
        $product = Product::find($id);
        if ($request->hasFile("thumbnail")) {
            if (!File::exists("public/uploads/admin/product/" . $id)) {
                File::makeDirectory("public/uploads/admin/product/" . $id);
            }
            $file = $request->thumbnail;
            $filename = $file->getClientOriginalName() . " ";
            // if (File::exists($product->thumbnail)) {
            //     chmod($product->thumbnail, 777);
            //     fclose($product->thumbnail);
            //     unlink($product->thumbnail);
            // }

            $path = $file->move("public/uploads/admin/product/" . $id, $file->getClientOriginalName());
            $thumbnail = "public/uploads/admin/product/" . $id . '/' . $filename;
            Product::where('id', $id)->update([
                'thumbnail' => $thumbnail
            ]);
        }

        $featured = 0;
        if ($request->input('featured')) {
            $featured = 1;
        }
        $best_seller = 0;
        if ($request->input('best_seller')) {
            $best_seller = 1;
        }

        // if ($product->name != $request->input('name')) {
        //     rename("public/uploads/admin/product/" . $product->name, "public/uploads/admin/product/" . $request->input('name'));
        //     $thumbnail = "public/uploads/admin/product/" . $request->input('name') . '/' . $filename;

        //     Product::where('id', $id)->update([
        //         'thumbnail' => $thumbnail
        //     ]);
        // }
        Product::where('id', $id)->update([
            'name' => $request->input('name'),
            'slug' => Str::slug($request->input('name')),
            'description' => $request->input('description'),
            'content' => $request->input('content'),
            'price' => $request->input('price'),
            'price_cost' => $request->input('price_cost'),

            'product_cat_id' => $request->input('product_cat_id'),
            'product_brand_id' => $request->input('product_brand'),
            'user_id' => Auth::id(),
            'qty' => $request->input('qty'),
            'status' => $request->input('status'),
            'browse' => $request->input('browse'),
            'best_seller' => $best_seller,
        ]);

        $tags_id = [];
        if (!empty($request->product_tags)) {
            foreach ($request->input('product_tags') as $item) {
                $tag_instance = Tag::firstOrCreate(['name' => $item]);
                $tags_id[] = $tag_instance->id;
            }
        }
        $product->tags()->sync($tags_id);

        // if ($request->hasFile("thumbnail_detail")) {
        //     $file = $request->thumbnail_detail;
        //     Product_image::where('product_id', $id)->delete();
        //     foreach ($file as $k => $file_item) {
        //         $thumbnail_name = $file_item->getClientOriginalName() . " ";
        //         $path = $file_item->move('public/uploads/admin/product', $file_item->getClientOriginalName());
        //         $thumbnail_path = 'public/uploads/admin/product/' . $thumbnail_name;

        //         Product_image::create([
        //             'image_path' => $thumbnail_path,
        //             'image_name' => $thumbnail_name,
        //             'product_id' => $id
        //         ]);
        //     }
        // }

        return redirect('admin/product/list')->with('status', 'Đã cập nhật sản phẩm thành công');
    }
    function delete($id)
    {
        $user = Product::find($id);
        $user->delete();
        return redirect('admin/product/list')->with('status', 'Đã xóa sản phẩm thành công');
    }

    function action(Request $request)
    {
        $list_check = $request->input('list_check');
        if (!empty($list_check)) {



            $act = $request->input('act');
            if ($act == 'delete') {
                Product::destroy($list_check);
                return redirect('admin/product/list')->with('status', 'Bạn đã xóa sản phẩm thành công');
            } else if ($act == 'restore') {
                Product::withTrashed()->whereIn('id', $list_check)->restore();
                return redirect('admin/product/list')->with('status', 'Bạn đã khôi phục sản phẩm thành công');
            } else if ($act == 'forceDelete') {
                Product::withTrashed()->whereIn('id', $list_check)->forceDelete();
                return redirect('admin/product/list')->with('status', 'Bạn đã xóa vĩnh viễn sản phẩm thành công');
            } else {
                return redirect('admin/product/list')->with('status', 'Bạn cần chọn 1 thao tác');
            }
        } else {
            return redirect('admin/product/list')->with('status', 'Bạn cần chọn phần tử để thực thi');
        }
    }
}
