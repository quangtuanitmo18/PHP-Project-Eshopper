<?php

namespace App\Http\Controllers;

use App\District;
use App\Fee_shipping;
use App\Street;
use App\Ward;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;

class Fee_shippingController extends Controller
{
    //
    public $default_fee_shipping = 10;

    function select_delivery(request $request)
    {
        $data = $request->all();
        if ($data['name_id']) {
            $output_city = "";
            $output_district = "";
            if ($data['name_id'] == "city") {
                $output_city .= '<option selected value="' . '' . '">' . '---Tỉnh/Thành Phố----</option>';
                $district = District::where('province_id', $data['id'])->get();
                foreach ($district as $value) {
                    $output_city .= '<option value="' . $value->id . '">' . $value->name . '</option>';
                }

                $output_district .= '<option selected value="' . '' . '">' . '---Quận/Huyện----</option>';
                $ward = Ward::where('district_id', $data['id'])->get();

                $street = Street::where('district_id', $data['id'])->get();
                if (!empty($ward)) {
                    foreach ($ward as $value) {
                        $output_district .= "<option value='" . $value->id . "'" . "old('district')==" . $value->id . "?selected='selected'" . ":''" . ">" . $value->name . "</option>";
                    }
                } else if (!empty($street)) {
                    foreach ($street as $value) {
                        $output_district .= '<option value="' . $value->id . '">' . $value->name . '</option>';
                    }
                }
            } else if ($data['name_id'] == "district") {
                $output_district .= '<option selected value="' . '' . '">' . '---Phường/Xã----</option>';
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

    function charge_shipping(Request $request)
    {

        $data = $request->all();
        $fee_shipping = Fee_shipping::where('province_id', $data['province_id'])->where('district_id', $data['district_id'])->where('ward_id', $data['ward_id'])->first();
        $total = 0;
        $final_total = 0;
        $fee_shipping_value = $this->default_fee_shipping;

        if (!empty($fee_shipping)) {
            $fee_shipping_value = $fee_shipping->fee_shipping;
            $fee_shipping_id = $fee_shipping->id;
            $request->session()->put('fee_shipping_id', $fee_shipping_id);
        }



        foreach (Cart::content() as $row) {
            $total += $row->price * $row->qty;
        }
        $discount = 0;
        if (session()->get('code')) {

            if (session()->get('condition') == 0) {

                $discount = ($total * session()->get('value')) / 100;
            } elseif (session()->get('condition') == 1) {

                $discount = session()->get('value');
            }
        }

        $request->session()->put('fee_shipping_value', $fee_shipping_value);
        $final_total = $total + Cart::tax() - $discount + $fee_shipping_value;

        $output = array(
            'fee_shipping_value' => number_format($fee_shipping_value, 1, ',', ' '),
            'total' => number_format($final_total, 1, ',', ' ')
        );
        $request->session()->put('final_total', $final_total);


        return response()->json($output);
    }
}
