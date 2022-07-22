<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;

class SaleController extends Controller
{


    public function index(Request $request)
    {
       
        $loggedInUser = DB::table('retailer_list_of_occ')->select('vp')
        ->groupBy('vp')
        ->get();
        //$loggedInUser=$request->vp;
        
        //dd($loggedInUser);
       // if (!empty(request()->input('vp'))) {

        $vp_states = DB::select('SELECT state FROM `retailer_list_of_occ`  GROUP BY state');

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

        // 2 after state selection
        
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

            $regionWisereport = \DB::select('SELECT ro.area, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
            INNER JOIN stores AS s ON ro.retailer = s.store_name
            LEFT JOIN orders AS o ON s.id = o.store_id
            WHERE ro.vp = "'.request()->input('vp').'" AND ro.state = "'.request()->input('state').'"
            AND (o.created_at BETWEEN "'.$from.'" AND "'.$to.'")
            GROUP BY ro.area');

            $RSMwiseReport = \DB::select('SELECT ro.rsm AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
            INNER JOIN stores AS s ON ro.retailer = s.store_name
            LEFT JOIN orders AS o ON s.id = o.store_id
            WHERE ro.vp = "'.request()->input('vp').'" AND ro.state = "'.request()->input('state').'"
            AND (o.created_at BETWEEN "'.$from.'" AND "'.$to.'")
            GROUP BY ro.rsm');

            $ASMwiseReport = \DB::select('SELECT ro.asm AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
            INNER JOIN stores AS s ON ro.retailer = s.store_name
            LEFT JOIN orders AS o ON s.id = o.store_id
            WHERE ro.vp = "'.request()->input('vp').'" AND ro.state = "'.request()->input('state').'"
            AND (o.created_at BETWEEN "'.$from.'" AND "'.$to.'")
            GROUP BY ro.asm');

            $ASEwiseReport = \DB::select('SELECT ro.ase AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
            INNER JOIN stores AS s ON ro.retailer = s.store_name
            LEFT JOIN orders AS o ON s.id = o.store_id
            WHERE ro.vp = "'.request()->input('vp').'" AND ro.state = "'.request()->input('state').'"
            AND (o.created_at BETWEEN "'.$from.'" AND "'.$to.'")
            GROUP BY ro.ase');

            $data = \DB::select('SELECT ro.distributor_name AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            INNER JOIN users AS s ON ro.distributor_name = s.name
            LEFT JOIN orders_distributors AS o ON s.id = o.user_id
            WHERE ro.vp = "'.request()->input('vp').'" AND ro.state = "'.request()->input('state').'"
            GROUP BY ro.distributor_name');

            return view('admin.report.index', compact('vp_states', 'regionWisereport', 'RSMwiseReport', 'ASMwiseReport', 'ASEwiseReport','loggedInUser','data'));
        
        
        

    }

    public function detail(Request $request)
    {
        if (isset($request->rsm)) {
            $reqData = ($request->rsm == "NA") ? null : $request->rsm;
            $reqQuery = ($request->rsm == "NA") ? 'WHERE ro.rsm IS null' : 'WHERE ro.rsm LIKE "%'.$reqData.'%"';

            $data = \DB::select('SELECT ro.asm AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
            INNER JOIN stores AS s ON ro.retailer = s.store_name
            LEFT JOIN orders AS o ON s.id = o.store_id
            '.$reqQuery.'
            AND ro.state = "'.request()->input('state').'"
            GROUP BY ro.asm');
        } elseif (isset($request->asm)) {
            $reqData = ($request->asm == "NA") ? null : $request->asm;
            $reqQuery = ($request->asm == "NA") ? 'WHERE ro.asm IS null' : 'WHERE ro.asm LIKE "%'.$reqData.'%"';

            $data = \DB::select('SELECT ro.ase AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
            INNER JOIN stores AS s ON ro.retailer = s.store_name
            LEFT JOIN orders AS o ON s.id = o.store_id
            '.$reqQuery.'
            AND ro.state = "'.request()->input('state').'"
            GROUP BY ro.ase');
        } elseif (isset($request->ase)) {
            $reqData = ($request->ase == "NA") ? null : $request->ase;
            $reqQuery = ($request->ase == "NA") ? 'WHERE ro.ase IS null' : 'WHERE ro.ase LIKE "%'.$reqData.'%"';

            $data = \DB::select('SELECT ro.distributor_name AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
            INNER JOIN stores AS s ON ro.retailer = s.store_name
            LEFT JOIN orders AS o ON s.id = o.store_id
            '.$reqQuery.'
            AND ro.state = "'.request()->input('state').'"
            GROUP BY ro.distributor_name');
        } elseif (isset($request->distributor)) {
            $reqData = ($request->distributor == "NA") ? null : $request->distributor;
            $reqQuery = ($request->distributor == "NA") ? 'WHERE ro.distributor_name IS null' : 'WHERE ro.distributor_name LIKE "%'.$reqData.'%"';

            $data = \DB::select('SELECT ro.retailer AS name, SUM(o.final_amount) AS value, s.id AS store_id FROM `retailer_list_of_occ` AS ro
            INNER JOIN stores AS s ON ro.retailer = s.store_name
            LEFT JOIN orders AS o ON s.id = o.store_id
            '.$reqQuery.'
            AND ro.state = "'.request()->input('state').'"
            GROUP BY ro.retailer');
        } else {
            return view('front.404');
        }

        return view('admin.report.detail', compact('data'));
    }
}
