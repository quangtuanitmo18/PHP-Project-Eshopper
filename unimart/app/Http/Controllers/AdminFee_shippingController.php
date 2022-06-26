<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Province;
use App\District;
use App\Ward;
use App\Street;
use App\Fee_shipping;

class AdminFee_shippingController extends Controller
{
    //
    function __construct()
    {
        $this->middleware(function ($request, $next) {
            session(['module_active' => 'fee_shipping']);
            return $next($request);
        });
    }
    function list()
    {
        $fee_shipping = Fee_shipping::orderBy('created_at', 'desc')->get();
        $provinces = Province::all();
        return view('admin.Fee_shipping.list', compact('provinces', 'fee_shipping'));
    }
    function add(request $request)
    {
        $data = $request->all();
        $request->validate(
            [
                'city' => 'required',
                'fee_shipping' => 'required|integer'
            ],
            [
                'required' => ":attribute không được để trống",
                'not_in' => ":attribute không được để trống",
            ],
            [
                'city' => 'Tên tỉnh (thành phố)',
                'fee_shipping' => 'Phí vận chuyển'
            ]
        );

        if ($request->district == "0" && $request->ward_street == "0") {
            Fee_shipping::create([
                'province_id' => $data['city'],
                'fee_shipping' => $data['fee_shipping']

            ]);
        } else {
            Fee_shipping::create([


                'province_id' => $data['city'],
                'district_id' => $data['district'],
                'ward_id' => $data['ward_street'],
                'fee_shipping' => $data['fee_shipping']

            ]);
        }
        return  redirect("admin/Fee_shipping/list")->with('status', 'Thêm danh sách thành công');
    }
    function delete($id)
    {
        $fee_shipping = Fee_shipping::find($id);

        $fee_shipping->delete();
        return redirect('admin/Fee_shipping/list')->with('status', 'Đã xóa danh mục thành công');
    }
    function select_delivery(request $request)
    {
        $data = $request->all();
        if ($data['name_id']) {
            $output_city = "";
            $output_district = "";
            if ($data['name_id'] == "city") {
                $output_city .= '<option value="' . '0' . '">' . '---Chọn----</option>';
                $district = District::where('province_id', $data['id'])->get();
                foreach ($district as $value) {
                    $output_city .= '<option value="' . $value->id . '">' . $value->name . '</option>';
                }

                $output_district .= '<option value="' . '0' . '">' . '---Chọn----</option>';
                $ward = Ward::where('district_id', $data['id'])->get();

                $street = Street::where('district_id', $data['id'])->get();
                if (!empty($ward)) {
                    foreach ($ward as $value) {
                        $output_district .= '<option value="' . $value->id . '">' . $value->name . '</option>';
                    }
                } else if (!empty($street)) {
                    foreach ($street as $value) {
                        $output_district .= '<option value="' . $value->id . '">' . $value->name . '</option>';
                    }
                }
            } else if ($data['name_id'] == "district") {
                $output_district .= '<option value="' . '0' . '">' . '---Chọn----</option>';
                $ward = Ward::where('district_id', $data['id'])->get();

                $street = Street::where('district_id', $data['id'])->get();
                if (!empty($ward)) {
                    foreach ($ward as $value) {
                        $output_district .= '<option value="' . $value->id . '">' . $value->name . '</option>';
                    }
                } else if (!empty($street)) {
                    foreach ($street as $value) {
                        $output_district .= '<option value="' . $value->id . '">' . $value->name . '</option>';
                    }
                }
            }
        }
        $output = array(
            'output_city' => $output_city,
            'output_district' => $output_district
        );
        return response()->json($output);
    }
}
