<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\UserInterface;
use App\Interfaces\OrderInterface;
use Illuminate\Http\Request;
use App\User;
use App\Models\Cart;
use App\Activity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\UserRepository;
use Illuminate\Support\Carbon;
class UserController extends Controller
{
    // private UserRepository $userRepository;

    public function __construct(UserInterface $userRepository, OrderInterface $orderRepository)
    {
        $this->userRepository = $userRepository;
        $this->orderRepository = $orderRepository;
    }

    public function login(Request $request)
    {
        // $recommendedProducts = $this->userRepository->recommendedProducts();
        return view('front.auth.login');
    }

    public function register(Request $request)
    {
        $recommendedProducts = $this->userRepository->recommendedProducts();
        return view('front.auth.register', compact('recommendedProducts'));
    }

    public function create(Request $request)
    {
        $request->validate([
            'fname' => 'required|string',
            'lname' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'mobile' => 'required|integer|digits:10|unique:users,mobile',
            'password' => 'required|string|min:2|max:100',
        ]);

        $storeData = $this->userRepository->create($request->except('_token'));

        if ($storeData) {
            // $credentials = $request->only('email', 'password');

            // if (Auth::attempt($credentials)) {
            //     // return redirect()->intended('home');
            //     return redirect()->url('home');
            // }

            return redirect()->route('front.user.register')->with('success', 'Account created successfully');
        } else {
            return redirect()->route('front.user.register')->withInput($request->all())->with('failure', 'Something happened');
        }
    }

    public function check(Request $request)
    {
        // dd("check");
        $request->validate([
            // 'email' => 'required|email|exists:users,email',
            'mobile' => 'required|integer|digits:10|exists:users,mobile',
            'password' => 'required|string|min:2|max:100',
        ], [
            'mobile.digits' => 'Please enter a valid 10 digit mobile number'
        ]);

        // $credentials = $request->only('email', 'password');
        $credentials = $request->only('mobile', 'password');

        if (Auth::guard('web')->attempt($credentials)) {
            $store = new Activity;
            $store->user_id = Auth::guard('web')->user()->id;
            $store->user_type = Auth::guard('web')->user()->user_type;
            $store->date = date('Y-m-d H:i:s');

            // $store->slug = null;
            $store->time = Carbon::now();
            $store->type = 'login';
            $ip = $_SERVER['REMOTE_ADDR'];
            $store->location = $ip;
            $store->save();
            //return $store;
            // return redirect()->intended('home');
            // return redirect()->intended('front.dashboard.index');
			return redirect()->intended('dashboard');

            // return redirect()->url('home');
        } else {
            return redirect()->back()->with(['message' => 'Wrong password!']);
        }

    }

    public function loginOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|integer|digits:10'
        ], [
            'mobile.*' => 'Please enter a valid 10 digit mobile number'
        ]);

        $response = $this->userRepository->otpGenerate($request->mobile);

        if ($response == "User does not exist") {
            return redirect()->back()->withInput($request->all())->with('failure', $response);
        } else {
            return redirect()->route('front.user.login.mobile', ['mobile' => $request->mobile])->withInput($request->all())->with('success', $response);
        }
    }

    public function loginOtpMobile(Type $var = null)
    {
        return view('front.auth.login-otp');
    }

    public function loginMobileOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|integer|digits:10',
            'otp' => 'required',
        ]);

        $response = $this->userRepository->otpcheck($request->mobile, $request->otp);

        if ($response == false) {
            return redirect()->back()->withInput($request->all())->with('failure', 'You have entered wrong otp. Please try again with the correct one.');
        } else {
            if (Auth::loginUsingId($response->id)) {
                Cart::where('user_id', $response->id)->delete();
                return redirect()->route('front.dashboard.index');
                // return redirect()->intended('front.dashboard.index');
            } else {
                return redirect()->route('front.user.login')->withInput($request->all());
            }
        }
    }

    public function order(Request $request)
    {
        if (count($request->all()) == 0) {
            $data = $this->userRepository->orderDetails();
        } elseif (isset($request->store)) {
            $data = $this->userRepository->orderByStore($request->store);
        }

        // if (auth()->guard('web')->user()->user_type == 6) {
        //     $store_id = DB::select('SELECT id FROM stores WHERE store_name = "'.Auth::guard('web')->user()->name.'"');

        //     if (count($store_id) > 0) {
        //         return redirect()->route('front.user.order', ['store' => $store_id[0]->id]);
        //     } else {
        //         return view('front.404');
        //     }
        // } else {
            return view('front.profile.order', compact('data'));
        // }
    }

    public function coupon(Request $request)
    {
        $data = $this->userRepository->couponList();
        return view('front.profile.coupon', compact('data'));
    }

    public function address(Request $request)
    {
        $data = $this->userRepository->addressById(Auth::guard('web')->user()->id);
        if ($data) {
            return view('front.profile.address', compact('data'));
        } else {
            return view('front.404');
        }
    }

    public function addressCreate(Request $request)
    {
        $request->validate([
            "user_id" => "required|integer",
            "address" => "required|string|max:255",
            "landmark" => "required|string|max:255",
            "lat" => "required",
            "lng" => "required",
            "type" => "required|integer",
            "state" => "required|string",
            "city" => "required|string",
            "country" => "required|string",
            "pin" => "required|integer|digits:6",
            "type" => "required|integer",
        ], [
            "lat.*" => "Please enter Location",
            "lng.*" => "Please enter Location"
        ]);

        $params = $request->except('_token');
        $storeData = $this->userRepository->addressCreate($params);

        if ($storeData) {
            return redirect()->route('front.user.address');
        } else {
            return redirect()->route('front.user.address.add')->withInput($request->all());
        }
    }

    public function updateProfile(Request $request)
    {
        $request->validate([
            "name" => "string|max:255",
            "fname" => "string|max:255",
            "lname" => "string|max:255",
            "email" => "required|email",
            "mobile" => "required|integer|digits:10",
            "whatsapp_no" => "nullable|integer|digits:10",
            "address" => "nullable|string|max:255",
            "landmark" => "nullable|string|max:255",
            "state" => "nullable|string|max:255",
            "city" => "nullable|string|max:255",
            "pin" => "nullable|integer",
            "adhar_no" => "nullable|integer|digits:12",
            "pan_no" => "nullable|string|max:10|min:10",
            "dob" => "date|nullable",
            "anniversary_date" => "date|nullable",
            "gender" => "nullable|string",
            "social_id" => "nullable|string"
        ], [
            'adhar_no.*' => 'Please enter valid Aadhar number',
            'pan_no.*' => 'Please enter valid PAN',
        ]);

        $params = $request->except('_token');
        $storeData = $this->userRepository->updateProfile($params);

        if ($storeData) {
            return redirect()->route('front.user.manage')->with('success', 'Profile updated successfully');
        } else {
            return redirect()->route('front.user.manage')->withInput($request->all());
        }
    }

    public function updatePassword(Request $request)
    {
        if (Auth::guard('web')->user()->password == "") {
            $request->validate([
                "new_password" => "required|string|max:255|same:confirm_password",
                "confirm_password" => "required|string|max:255",
            ]);
        } else {
            $request->validate([
                "old_password" => "required|string|max:255",
                "new_password" => "required|string|max:255",
                "confirm_password" => "required|string|max:255|same:new_password",
            ]);
        }
        $params = $request->except('_token');
        $storeData = $this->userRepository->updatePassword($params);

        if ($storeData) {
            return redirect()->route('front.user.profile')->with('success', 'Password updated successfully');
        } else {
            return redirect()->route('front.user.profile')->withInput($request->all())->with('failure', 'Something happened');
        }
    }

    public function wishlist(Request $request)
    {
        $data = $this->userRepository->wishlist();
        if ($data) {
            return view('front.profile.wishlist', compact('data'));
        } else {
            return view('front.404');
        }
    }

    public function invoice(Request $request, $id)
    {
        $data = $this->orderRepository->listById($id);
        return view('front.profile.invoice', compact('data'));
    }

    public function invoiceDistributor(Request $request, $id)
    {
        $data = $this->orderRepository->listByIdForDistributor($id);
        return view('front.profile.invoice', compact('data'));
    }
}
