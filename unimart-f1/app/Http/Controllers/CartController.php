<?php

namespace App\Http\Controllers;

use App\Menu;
use Illuminate\Http\Request;
use App\Product;
use App\Product_cat;
use App\Slider;
use App\Event;
use App\Province;
use Gloudemans\Shoppingcart\Facades\Cart;

class CartController extends Controller
{
    //
    public $payment = [
        0 => 'Payment on delivery', //
        1 => 'Payment by PayPal',
        2 => 'Payment by VNpay',

    ];
    function show()
    {
        $sliders = Slider::orderBy('position')->where('status', 1)->get();
        $product_cats = Product_cat::where('parent_id', 0)->orderby('position')->get();
        $menus = Menu::where('parent_id', 0)->get();
        $events = Event::where('status', 1)->get();
        return view('cart.show', compact('sliders', 'product_cats', 'menus', 'events'));
    }
    function add(Request $request, $id)
    {
        // Cart::destroy();
        $product = product::find($id);
        $qty = $request->input('product_qty');

        Cart::add([
            'id' => $id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $product->price,
            'options' => ['thumbnail' => $product->thumbnail, 'max_qty' => $product->qty, 'product_cat' => $product->product_cat->name, 'product_brand' => $product->product_brand->name]
        ]);
        return redirect('cart/show');
    }
    function cart_quick_view(Request $request)
    {

        $id = $request->input('id_product');
        $qty = $request->num_product;

        $product = Product::find($id);
        Cart::add([
            'id' => $id,
            'name' => $product->name,
            'qty' => $qty,
            'price' => $product->price,
            'options' => ['thumbnail' => $product->thumbnail, 'max_qty' => $product->qty, 'product_cat' => $product->product_cat->name, 'product_brand' => $product->product_brand->name]
        ]);
    }
    function add_ajax(Request $request)
    {


        $id = $request->input('cart_product_id');
        $product = product::find($id);
        //dd($product);
        Cart::add([
            'id' => $id,
            'name' => $product->name,
            'qty' => 1,
            'price' => $product->price,
            'options' => ['thumbnail' => $product->thumbnail, 'max_qty' => $product->qty, 'product_cat' => $product->product_cat->name, 'product_brand' => $product->product_brand->name]
        ]);
        //return redirect('cart/show');

    }

    function change_number_product_ajax(Request $request)
    {
        $id = $request->input('id');
        //$product=Cart::where('rowId',$id)->first();
        $cart = Cart::get($id);
        $number_product = $request->input('number_product');
        Cart::update($id, $number_product);

        $sub_total = $cart->price * $number_product;
        $total = 0;

        $discount = 0;
        foreach (Cart::content() as $row) {
            $total += $row->price * $row->qty;
        }
        $total_after_discount = $total;
        if ($request->session()->has('id')) {
            if ($request->session()->get('condition') == 0) {
                $discount = $total_after_discount * ($request->session()->get('value')) / 100;
                $total_after_discount = $total_after_discount - $discount;
            } else if ($request->session()->get('condition') == 1) {
                $discount = $request->session()->get('value');
                $total_after_discount = $total_after_discount - $discount;
            }
        }

        $data = array(
            'sub_total' => number_format($sub_total, 1, ',', ''),
            'discount' => number_format($discount, 1, ',', ''),
            'total' => number_format($total, 1, ',', ''),
            'total_after_discount' => number_format($total_after_discount, 1, ',', ''),
            'id' => $id
        );
        return response()->json($data);
    }
    function remove($rowId)
    {
        Cart::remove($rowId);
        return redirect('cart/show')->with('status', 'Bạn đã xóa sản phẩm khỏi giỏ hàng thành công!');
    }
    function destroy()
    {
        Cart::destroy();
        session()->pull('event_id');
        session()->pull('id');
        session()->pull('user_id');

        session()->pull('code');
        session()->pull('qty');
        session()->pull('value');
        session()->pull('condition');
        return redirect('cart/show')->with('status', 'Bạn đã xóa toàn bộ giỏ hàng thành công!');
    }
    function update(Request $request)
    {

        // dd($request->all());
        $data = $request->get('qty');
        foreach ($data as $k => $v) {
            Cart::update($k, $v);
        }
        return  redirect('cart/show')->with('status', 'Bạn đã cập nhật giỏ hàng thành công!');
    }
    function checkout()
    {
        if (Cart::count() < 1 && !session()->get('success_checkout')) {
            return  redirect('cart/show')->with('status_danger', 'Bạn cần có ít nhất 1 sản phẩm trong giỏ hàng.');
        }
        session()->pull('success_checkout');
        $provinces = Province::all();
        $payment = $this->payment;
        $sliders = Slider::orderBy('position')->where('status', 1)->get();
        $product_cats = Product_cat::where('parent_id', 0)->orderby('position')->get();
        $menus = Menu::where('parent_id', 0)->get();
        return view('cart.checkout', compact('sliders', 'product_cats', 'menus', 'payment', 'provinces'));
    }
}
