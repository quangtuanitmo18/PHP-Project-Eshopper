<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Menu;
use App\Product_cat;
use App\Providers\RouteServiceProvider;
use App\Slider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use App\Customer;
use Exception;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    function auth_register1()
    {
        return view('auth.register1');
    }
    // function login(){
    //     $sliders=Slider::orderBy('position')->where('status',1)->get();
    //     $product_cats=Product_cat::where('parent_id',0)->orderby('position')->get();
    //     $menus=Menu::where('parent_id',0)->get();
    //     return view('auth.login',compact('sliders','product_cats','menus'));
    // }
    function auth_login()
    {
        $menus = Menu::where('parent_id', 0)->get();
        return view('auth.login', compact('menus'));
    }

    public function redirectToProvider($social)
    {
        return Socialite::driver($social)->redirect();
    }

    public function handleProviderCallback($social)
    {

        // Sau khi xác thực Facebook chuyển hướng về đây cùng với một token
        // Các xử lý liên quan đến đăng nhập bằng mạng xã hội cũng đưa vào đây.  
        $user = Socialite::driver($social)->user();
        if ($social == "facebook") {
            try {
                $check = Customer::where('facebook_id', $user->id)->first();
                if ($check) {
                    Auth::login($check);
                } else {
                    $customer = Customer::all();
                    $check_email = true;
                    foreach ($customer as $value) {
                        if ($value->email == $user->email) {
                            $check_email = false;
                        }
                    }

                    if ($check_email) {
                        $data = new Customer;
                        $data->name = $user->name;
                        $data->email = $user->email;
                        $data->password = bcrypt('123456');
                        $data->facebook_id = $user->id;
                        $data->save();
                        Auth::login($data);
                    } else {
                        return view('errors.duplicate_email');
                    }
                }
                return redirect('home');
            } catch (Exception $e) {
                dd($e->getMessage());
            }
        } else if ($social == "google") {

            try {

                $user = Socialite::driver('google')->user();

                $finduser = Customer::where('google_id', $user->id)->first();

                if ($finduser) {

                    Auth::login($finduser);

                    return redirect('/home');
                } else {

                    $customer = Customer::all();
                    $check_email = true;
                    foreach ($customer as $value) {
                        if ($value->email == $user->email) {
                            $check_email = false;
                        }
                    }
                    if ($check_email) {
                        $newUser = Customer::create([
                            'name' => $user->name,
                            'email' => $user->email,
                            'google_id' => $user->id,
                            'password' => encrypt('123456')
                        ]);

                        Auth::login($newUser);

                        return redirect('/home');
                    } else {
                        return view('errors.duplicate_email');
                    }
                }
            } catch (Exception $e) {
                return $e->getMessage();
            }
        }
    }
}
