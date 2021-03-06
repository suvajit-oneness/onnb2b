<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $loggedInUserId = Auth::guard('web')->user()->id;
        $loggedInUser = Auth::guard('web')->user()->name;
        $loggedInUserType = Auth::guard('web')->user()->user_type;
        $loggedInUserState = Auth::guard('web')->user()->state;

        switch ($loggedInUserType) {
            case 1: $userTypeDetail = "Vice President";break;
            case 2: $userTypeDetail = "Regional sales manager";break;
            case 3: $userTypeDetail = "Area sales manager";break;
            case 4: $userTypeDetail = "Area sales executive";break;
            case 5: $userTypeDetail = "Distributor";break;
            case 6: $userTypeDetail = "Retailer";break;
            default: $userTypeDetail = "";break;
        }

        // for 1: VP
        if ($loggedInUserType == 1) {
            // date from
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

            $vp_states = DB::select('SELECT state FROM `retailer_list_of_occ` WHERE vp LIKE "%'.$loggedInUser.'%" GROUP BY state');

            $stateWiseReport = DB::select('SELECT ro.state AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            AND (o.created_at BETWEEN "'.$from.'" AND "'.$to.'") 
            WHERE ro.vp LIKE "%'.$loggedInUser.'%"
            GROUP BY ro.state');

            // (count($vp_states) != 0) ? $vp_states = $vp_states : $vp_states = $loggedInUserState;

            if (!empty(request()->input('state'))) {
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

                $loggedInUserState = request()->input('state');

                return view('front.dashboard.vp', compact('userTypeDetail', 'vp_states', 'stateWiseReport', 'regionWiseReport', 'RSMwiseReport', 'loggedInUserState'));
                // return view('front.dashboard.index', compact('userTypeDetail', 'vp_states', 'stateWiseReport', 'regionWiseReport', 'RSMwiseReport', 'loggedInUserState'));
            } else {
                // 1 default view - state only
                // if($loggedInUserType == 1) {
                    $regionWiseReport = DB::select('SELECT ro.area, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                    LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                    LEFT JOIN orders AS o ON s.id = o.store_id 
                    WHERE ro.state = "'.$loggedInUserState.'" 
                    GROUP BY ro.area');

                    $RSMwiseReport = \DB::select('SELECT ro.rsm AS name, ro.state, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                    LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                    LEFT JOIN orders AS o ON s.id = o.store_id 
                    GROUP BY ro.rsm');

                    return view('front.dashboard.vp', compact('userTypeDetail', 'vp_states', 'stateWiseReport', 'regionWiseReport', 'RSMwiseReport', 'loggedInUserState'));
                    // return view('front.dashboard.index', compact('userTypeDetail', 'vp_states', 'stateWiseReport', 'regionWiseReport', 'RSMwiseReport', 'loggedInUserState'));
                // } else {
                //     return redirect()->route('front.dashboard.index', ['state' => $loggedInUserState]);
                // }
            }
        }
        // only for 2: RSM
        elseif ($loggedInUserType == 2) {
            $reqData = ($loggedInUser == "NA") ? null : $loggedInUser;
            $reqQuery = ($loggedInUser == "NA") ? 'WHERE ro.rsm IS null' : 'WHERE ro.rsm LIKE "%'.$reqData.'%"';

            $asmReport = \DB::select('SELECT ro.asm AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            GROUP BY ro.asm ORDER BY ro.asm');

            $aseReport = \DB::select('SELECT ro.ase AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            GROUP BY ro.ase ORDER BY ro.ase');

            $distributorReport = \DB::select('SELECT ro.distributor_name AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            GROUP BY ro.distributor_name ORDER BY ro.distributor_name');

            $retailerReport = \DB::select('SELECT s.id AS store_id, ro.retailer AS name, SUM(o.final_amount) AS value, ro.distributor_name AS distributorName FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            GROUP BY ro.retailer ORDER BY ro.retailer');

            return view('front.dashboard.rsm', compact('userTypeDetail', 'asmReport', 'aseReport', 'distributorReport', 'retailerReport', 'loggedInUserState'));
        }
        // only for 3: ASM
        elseif ($loggedInUserType == 3) {
            $reqData = ($loggedInUser == "NA") ? null : $loggedInUser;
            $reqQuery = ($loggedInUser == "NA") ? 'WHERE ro.asm IS null' : 'WHERE ro.asm LIKE "%'.$reqData.'%"';

            $aseReport = \DB::select('SELECT ro.ase AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            GROUP BY ro.ase ORDER BY ro.ase');

            $distributorReport = \DB::select('SELECT ro.distributor_name AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            GROUP BY ro.distributor_name ORDER BY ro.distributor_name');

            $retailerReport = \DB::select('SELECT s.id AS store_id, ro.retailer AS name, SUM(o.final_amount) AS value, ro.distributor_name AS distributorName FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            GROUP BY ro.retailer ORDER BY ro.retailer');

            return view('front.dashboard.asm', compact('userTypeDetail', 'aseReport', 'distributorReport', 'retailerReport', 'loggedInUserState'));
        }
        // only for 4: ASE
        elseif ($loggedInUserType == 4) {
            // $reqData = ($loggedInUser == "NA") ? null : $loggedInUser;
            // $reqQuery = ($loggedInUser == "NA") ? 'WHERE ro.ase IS null' : 'WHERE ro.ase LIKE "%'.$reqData.'%"';

            // AND (o.created_at BETWEEN "'.$from.'" AND "'.$to.'") 

            // $aseToDistributorData = \DB::select('SELECT ro.distributor_name AS name FROM `retailer_list_of_occ` AS ro 
            // WHERE ro.ase LIKE "%'.$loggedInUser.'%" 
            // GROUP BY ro.distributor_name');

            $distributors = DB::select("SELECT distributor_name FROM `retailer_list_of_occ`
            WHERE ase LIKE '%".$loggedInUser."%'
            GROUP BY distributor_name
            ORDER BY distributor_name");

            $retailers = DB::select("SELECT id, store_name FROM `stores`
            WHERE `user_id` = '".$loggedInUserId."'
            AND status = 1
            ORDER BY store_name");

            // $retailers = DB::select("SELECT id, retailer FROM `retailer_list_of_occ`
            // WHERE ase LIKE '%".$loggedInUser."%'
            // GROUP BY retailer
            // ORDER BY retailer");

            return view('front.dashboard.ase', compact('userTypeDetail', 'loggedInUserState', 'distributors', 'retailers'));

            /* $reqData = ($loggedInUser == "NA") ? null : $loggedInUser;
            $reqQuery = ($loggedInUser == "NA") ? 'WHERE ro.ase IS null' : 'WHERE ro.ase LIKE "%'.$reqData.'%"';

            $distributorReport = \DB::select('SELECT ro.distributor_name AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            AND ro.state = "'.$loggedInUserState.'" 
            GROUP BY ro.distributor_name ORDER BY ro.distributor_name');

            $retailerReport = \DB::select('SELECT s.id AS store_id, ro.retailer AS name, SUM(o.final_amount) AS value, ro.distributor_name AS distributorName FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            AND ro.state = "'.$loggedInUserState.'" 
            GROUP BY ro.retailer ORDER BY ro.retailer');

            return view('front.dashboard.ase', compact('userTypeDetail', 'distributorReport', 'retailerReport', 'loggedInUserState')); */
        }
        // only for 5: Distributor
        elseif ($loggedInUserType == 5) {
            return view('front.dashboard.distributor', compact('userTypeDetail'));
        }
        // only for 6: Retailer
        else {
            return view('front.dashboard.retailer', compact('userTypeDetail'));
        }
    }
}
