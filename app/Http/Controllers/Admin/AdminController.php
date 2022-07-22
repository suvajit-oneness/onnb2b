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
use App\Models\OrderDistributor;
use App\Models\OrderProduct;
use App\Models\Store;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct(DistributorInterface $distributorRepository)
    {
        $this->distributorRepository = $distributorRepository;
    }

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

    public function home(Request $request)
    {
        $data = (object)[];
        $data->vp = RetailerListOfOcc::select('vp')->whereRaw('vp IS NOT null')->groupBy('vp')->get();
        $data->rsm = RetailerListOfOcc::select('rsm')->whereRaw('rsm IS NOT null')->groupBy('rsm')->get();
        $data->asm = RetailerListOfOcc::select('asm')->whereRaw('asm IS NOT null')->groupBy('asm')->get();
        $data->ase = RetailerListOfOcc::select('ase')->whereRaw('ase IS NOT null')->groupBy('ase')->get();
        $data->distributor = RetailerListOfOcc::select('distributor_name')->whereRaw('distributor_name IS NOT null')->groupBy('distributor_name')->get();
        $data->store = Store::where('status', 1)->count();

        // $data->primary = OrderDistributor::where('created_at', '>', \Carbon\Carbon::now())->latest('id', 'desc')->sum('final_amount');
        $data->primary = DB::select("SELECT SUM(final_amount) AS final_amount FROM `orders_distributors` WHERE date(created_at) = '".date('Y-m-d')."'");
        // $data->secondary = OrderProduct::where('created_at', '>', \Carbon\Carbon::now())->sum('qty');
        $data->secondary = DB::select("SELECT SUM(qty) AS qty FROM `order_products` WHERE date(created_at) = '".date('Y-m-d')."'");

        return view('admin.home', compact('data'));
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
