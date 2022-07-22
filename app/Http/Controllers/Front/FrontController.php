<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\StoreInterface;
use Illuminate\Http\Request;
use App\Models\SubscriptionMail;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Product;
use App\Models\Gallery;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function __construct(StoreInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function index(Request $request)
    {
        $category = Category::latest('id')->get();
        $collections = Collection::latest('id')->get();
        $products = Product::where('is_trending', 1)->latest('view_count', 'id')->get();
        // $products = Product::latest('view_count', 'id')->limit(16)->get();
        $galleries = Gallery::latest('id')->get();
        return view('front.welcome', compact('category', 'collections', 'products', 'galleries'));
    }

    public function mailSubscribe(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];

        $validator = Validator::make($request->all(), $rules);

        if (!$validator->fails()) {
            $mailExists = SubscriptionMail::where('email', $request->email)->first();
            if (empty($mailExists)) {
                $mail = new SubscriptionMail();
                $mail->email = $request->email;
                $mail->save();

                return response()->json(['resp' => 200, 'message' => 'Mail subscribed successfully']);
            } else {
                $mailExists->count += 1;
                $mailExists->save();

                return response()->json(['resp' => 200, 'message' => 'Thank you for showing your interest']);
            }
        } else {
            return response()->json(['resp' => 400, 'message' => $validator->errors()->first()]);
        }
    }

    public function offerIndex(Request $request)
    {
        $data = DB::table('offers')->get();
        return view('front.offer.index', compact('data'));
    }

    public function catalougeDownloadIndex(Request $request)
    {
        $data = DB::table('product_catalogues')->get();
        return view('front.category.download', compact('data'));
    }

    public function directoryIndex(Request $request)
    {
        $loggedInUserId = Auth::guard('web')->user()->id;
        $loggedInUser = Auth::guard('web')->user()->name;
        $loggedInUserType = Auth::guard('web')->user()->user_type;
        $loggedInUserState = Auth::guard('web')->user()->state;

        // dd($loggedInUser);

        if ($loggedInUserType == 4) {
            $data = DB::select('SELECT * FROM `retailer_list_of_occ`  
            WHERE ase LIKE "%'.$loggedInUser.'%"
            GROUP BY distributor_name');
        } elseif ($loggedInUserType == 3) {
            $data = DB::select('SELECT * FROM `retailer_list_of_occ`  
            WHERE asm LIKE "%'.$loggedInUser.'%"
            GROUP BY distributor_name');
        } elseif ($loggedInUserType == 2) {
            $data = DB::select('SELECT * FROM `retailer_list_of_occ`  
            WHERE rsm LIKE "%'.$loggedInUser.'%"
            GROUP BY distributor_name');
        } elseif ($loggedInUserType == 1) {
            $data = DB::select('SELECT * FROM `retailer_list_of_occ`  
            WHERE vp LIKE "%'.$loggedInUser.'%"
            GROUP BY distributor_name');
        }
          $item=$data[0];
         //dd($data);
        // $data = $this->storeRepository->listAll();

        if ($data) {
            return view('front.directory.index', compact('data','item'));
        } else {
            return view('front.404');
        }
    }

    public function momStore(Request $request)
    {
        // dd($request->all());
        $rules = [
            'comment' => 'required',
            //'distributor_name' => 'required|string',
            'user_id' => 'required|integer'
        ];

        $validator = Validator::make($request->all(), $rules);

        if (!$validator->fails()) {
            $data = DB::insert('insert into directory_mom (user_id, distributor_name, comment) values (?, ?, ?)', [$request->user_id, $request->distributor_name, $request->comment]);

            if ($data) {
                return redirect()->back()->with('success', 'MOM added successfully');
            } else {
                return redirect()->back()->with('failure', 'Something happened. Try again');
            }

            // $mailExists = SubscriptionMail::where('email', $request->email)->first();
            // if (empty($mailExists)) {
            //     $mail = new SubscriptionMail();
            //     $mail->email = $request->email;
            //     $mail->save();

            //     return response()->json(['resp' => 200, 'message' => 'Mail subscribed successfully']);
            // } else {
            //     $mailExists->count += 1;
            //     $mailExists->save();

            //     return response()->json(['resp' => 200, 'message' => 'Thank you for showing your interest']);
            // }
        } else {
            return redirect()->back()->with('failure', $validator->errors()->first());
            // return response()->json(['resp' => 400, 'message' => $validator->errors()->first()]);
        }
    }
}
