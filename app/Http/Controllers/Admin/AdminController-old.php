<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\UserInterface;
use App\Interfaces\DistributorInterface;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Models\Collection;
use App\Models\SubCategory;
use App\Models\Product;
use App\Activity;
use App\Models\Order;
use App\Models\RetailerListOfOcc;
use App\User;
use App\Models\DirectoryMom;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class AdminController extends Controller
{
    public function __construct(DistributorInterface $distributorRepository)
    {
        $this->distributorRepository = $distributorRepository;
    }
    /**
     * This method is for admin login
     *
     */
     public function check(Request $request)
    {
        $request->validate([
            'email' => 'required | string | email | exists:admins',
            'password' => 'required | string'
        ]);

        $adminCreds = $request->only('email', 'password');

        if ( Auth::guard('admin')->attempt($adminCreds) ) {
            return redirect()->route('admin.home');
        } else {
            return redirect()->route('admin.login')->withInputs($request->all())->with('failure', 'Invalid credentials. Try again');
        }
    }
    /**
     * This method is for admin dashboard
     *
     */
    public function home(Request $request)
    {
        // $data = $userRepository->listAll();
        // dd($data->count());
        $data = (object)[];
        $data->users = User::count();
        $data->collection = Collection::count();
        $distributor = RetailerListOfOcc::select('distributor_name')->orderBy('distributor_name')->count();
        $data->retailer = Store::count();
        $data->products = Product::latest('id')->get();
        $data->orders = Order::latest('id')->limit(5)->get();
        $data->state = RetailerListOfOcc::select('state')->groupBy('state')->count();
        $data->store = Store::count();
        $activity=Activity::paginate(8);
        $store=Store::paginate(5);
        $loggedInUser = DB::table('retailer_list_of_occ')->select('vp')
        ->groupBy('vp')
        ->get();
        //$loggedInUser=$request->vp;

        //dd($loggedInUser);


        $vp_states = DB::select('SELECT state FROM `retailer_list_of_occ`  GROUP BY state ORDER BY state');
        if (!empty(request()->input('from'))) {
            $from = request()->input('from');
        } else {
            $from = $first_day_this_month = date('Y-m-01');
        }

        // date to
        if (!empty(request()->input('to'))) {
            $to = date('Y-m-d', strtotime(request()->input('to'). '+1 days'));
        } else {
            $to = $current_day_this_month = date('Y-m-d', strtotime('+1 day'));
        }



             $stateWiseReport = DB::select('SELECT ro.state AS name , SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
            LEFT JOIN stores AS s ON ro.retailer = s.store_name
            LEFT JOIN orders AS o ON s.id = o.store_id
            GROUP BY ro.state ORDER BY ro.state' );

                // $from = request()->input('from');
                // $to = date('Y-m-d', strtotime(request()->input('to'). '+1 days'));

                $regionWiseReport = \DB::select('SELECT ro.area, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
                LEFT JOIN stores AS s ON ro.retailer = s.store_name
                LEFT JOIN orders AS o ON s.id = o.store_id
                WHERE ro.state = "'.request()->input('state').'"
                AND (o.created_at BETWEEN "'.$from.'" AND "'.$to.'")
                GROUP BY ro.area');

                $RSMwiseReport = \DB::select('SELECT ro.rsm AS name, ro.state, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
                LEFT JOIN stores AS s ON ro.retailer = s.store_name
                LEFT JOIN orders AS o ON s.id = o.store_id
                WHERE ro.state = "'.request()->input('state').'"
                AND (o.created_at BETWEEN "'.$from.'" AND "'.$to.'")
                GROUP BY ro.rsm');






        return view('admin.home', compact('data','store','vp_states','stateWiseReport', 'regionWiseReport', 'RSMwiseReport', 'loggedInUser','activity','distributor'));

    }

     public function directory(Request $request)
    {
        if (!empty($request->term)) {

             $data = $this->distributorRepository->getSearchDirectorymom($request->term);


         } else {
         $data =  DirectoryMom::latest('id','desc')->paginate(5);
         }


        return view('admin.directorymom.index', compact('data'));
    }
}
