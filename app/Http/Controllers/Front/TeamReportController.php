<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TeamReportController extends Controller
{
    // ASE only
    public function detail(Request $request)
    {
        $collection = Collection::where('status', 1)->orderBy('position')->get();
        $category =Category::where('status', 1)->orderBy('position')->get();
        $loggedInUserId = Auth::guard('web')->user()->id;
        $loggedInUser = Auth::guard('web')->user()->name;
        $loggedInUserType = Auth::guard('web')->user()->user_type;
        $loggedInUserState = Auth::guard('web')->user()->state;

        if ($loggedInUserType == 4) {
            $distributors = DB::select("SELECT distributor_name FROM `retailer_list_of_occ`
            WHERE ase LIKE '%".$loggedInUser."%'
            GROUP BY distributor_name
            ORDER BY distributor_name");

            $retailers = DB::select("SELECT id, store_name FROM `stores`
            WHERE `user_id` = '".$loggedInUserId."'
            ORDER BY store_name");
        }

        return view('front.report.ase.team-report.details', compact('distributors', 'retailers', 'collection', 'category', 'request', 'loggedInUserType'));
    }
}
