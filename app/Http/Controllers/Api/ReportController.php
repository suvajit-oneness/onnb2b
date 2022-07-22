<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\User;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function vpReport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "required|integer|min:1"
        ]);

        if (!$validator->fails()) {
            $userId = $request->user_id;
            $user = User::findOrFail($userId);

            if ($user->user_type == 1) {
                $stateWiseReport = DB::select('SELECT ro.state AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                LEFT JOIN orders AS o ON s.id = o.store_id 
                WHERE ro.vp LIKE "%'.$user->name.'%" 
                GROUP BY ro.state');

                $RSMwiseReport = \DB::select('SELECT ro.rsm AS name, SUM(o.final_amount) AS value, ro.state AS state FROM `retailer_list_of_occ` AS ro 
                LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                LEFT JOIN orders AS o ON s.id = o.store_id 
                WHERE ro.vp LIKE "%'.$user->name.'%"
                GROUP BY ro.rsm');

                return response()->json(['error' => false, 'message' => 'State wise report', 'stateWiseReport' => $stateWiseReport, 'rsmWiseReport' => $RSMwiseReport]);
            } else {
                return response()->json(['error' => true, 'message' => 'Please provide user id of VP']);
            }
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
    }

    public function vpReportAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "required|integer|min:1",
            //"state" => "required|string|min:1",
            "from" => "nullable|date",
            "to" => "nullable|date",
        ]);

        if (!$validator->fails()) {
            $userId = $request->user_id;
            $state = $request->state;
            $user = User::findOrFail($userId);

            // date from
            if (!empty($request->from)) {
                $from = $request->from;
            } else {
                $from = date('Y-m-01');
            }

            // date to
            if (!empty($request->to)) {
                $to = date('Y-m-d', strtotime($request->to. '+1 day'));
            } else {
                $to = date('Y-m-d', strtotime('+1 day'));
            }

            if ($user->user_type == 1) {
				$vp = $user->name;
                // $regionWiseReport = \DB::select('SELECT ro.area, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                // LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                // LEFT JOIN orders AS o ON s.id = o.store_id 
                // WHERE ro.state = "'.$state.'" 
                // AND (o.created_at BETWEEN "'.$from.'" AND "'.$to.'") 
                // GROUP BY ro.area');

                // $RSMwiseReport = \DB::select('SELECT ro.rsm AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                // LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                // LEFT JOIN orders AS o ON s.id = o.store_id 
                // WHERE ro.state = "'.$state.'" 
                // AND (o.created_at BETWEEN "'.$from.'" AND "'.$to.'") 
                // GROUP BY ro.rsm');

                // $ASMwiseReport = \DB::select('SELECT ro.asm AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                // LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                // LEFT JOIN orders AS o ON s.id = o.store_id 
                // WHERE ro.state = "'.$state.'" 
                // AND (o.created_at BETWEEN "'.$from.'" AND "'.$to.'") 
                // GROUP BY ro.asm');

                // $ASEwiseReport = \DB::select('SELECT ro.ase AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                // LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                // LEFT JOIN orders AS o ON s.id = o.store_id 
                // WHERE ro.state = "'.$state.'" 
                // AND (o.created_at BETWEEN "'.$from.'" AND "'.$to.'") 
                // GROUP BY ro.ase');
                
				if($state!=''){
					$regionWiseReport = \DB::select('SELECT ro.area, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                LEFT JOIN orders AS o ON s.id = o.store_id 
                WHERE ro.state = "'.$state.'" 
                
                GROUP BY ro.area');

                $RSMwiseReport = \DB::select('SELECT ro.rsm AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                LEFT JOIN orders AS o ON s.id = o.store_id 
                WHERE ro.state = "'.$state.'" 
               
                GROUP BY ro.rsm');

                $ASMwiseReport = \DB::select('SELECT ro.asm AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                LEFT JOIN orders AS o ON s.id = o.store_id 
                WHERE ro.state = "'.$state.'" 
                
                GROUP BY ro.asm');

                $ASEwiseReport = \DB::select('SELECT ro.ase AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                LEFT JOIN orders AS o ON s.id = o.store_id 
                WHERE ro.state = "'.$state.'" 
                
                GROUP BY ro.ase');
				}else{
					$regionWiseReport = \DB::select('SELECT ro.area, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                LEFT JOIN orders AS o ON s.id = o.store_id 
                WHERE ro.vp = "'.$vp.'" 
                GROUP BY ro.area');

                $RSMwiseReport = \DB::select('SELECT ro.rsm AS name,ro.state, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                LEFT JOIN orders AS o ON s.id = o.store_id 
				WHERE ro.vp = "'.$vp.'"
                GROUP BY ro.rsm');

                $ASMwiseReport = \DB::select('SELECT ro.asm AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                LEFT JOIN orders AS o ON s.id = o.store_id 
				WHERE ro.vp = "'.$vp.'"
                GROUP BY ro.asm');

                $ASEwiseReport = \DB::select('SELECT ro.ase AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
                LEFT JOIN stores AS s ON ro.retailer = s.store_name 
                LEFT JOIN orders AS o ON s.id = o.store_id 
                GROUP BY ro.ase');
				}
                

                return response()->json(['error' => false, 'message' => 'All report for VP', 'region' => $regionWiseReport, 'rsm' => $RSMwiseReport, 'asm' => $ASMwiseReport, 'ase' => $ASEwiseReport]);
            } else {
                return response()->json(['error' => true, 'message' => 'Please provide use id of VP']);
            }
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
    }

    public function vpReportDetailRsm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //"state" => "required|string|min:1",
            "rsm" => "required|string|min:1",
            "from" => "nullable|date",
            "to" => "nullable|date",
        ]);

        if (!$validator->fails()) {
            $state = $request->state;
            $rsm = $request->rsm;

            // date from
            if (!empty($request->from)) {
                $from = $request->from;
            } else {
                $from = date('Y-m-01');
            }

            // date to
            if (!empty($request->to)) {
                $to = date('Y-m-d', strtotime($request->to. '+1 day'));
            } else {
                $to = date('Y-m-d', strtotime('+1 day'));
            }

            $reqData = ($rsm == "NA") ? null : $rsm;
            $reqQuery = ($rsm == "NA") ? 'WHERE ro.rsm IS null' : 'WHERE ro.rsm LIKE "%'.$reqData.'%"';

			if($state!=''){
				$data = \DB::select('SELECT ro.asm AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.' 
            AND ro.state = "'.$state.'" 
            GROUP BY ro.asm');
			}else{
				$data = \DB::select('SELECT ro.asm AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.' 
            GROUP BY ro.asm');
			}
            

            return response()->json(['error' => false, 'message' => 'RSM wise ASM report', 'data' => $data]);
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
    }

    public function vpReportDetailAsm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //"state" => "required|string|min:1",
            "asm" => "required|string|min:1",
            "from" => "nullable|date",
            "to" => "nullable|date",
        ]);

        if (!$validator->fails()) {
            $state = $request->state;
            $asm = $request->asm;

            // date from
            if (!empty($request->from)) {
                $from = $request->from;
            } else {
                $from = date('Y-m-01');
            }

            // date to
            if (!empty($request->to)) {
                $to = date('Y-m-d', strtotime($request->to. '+1 day'));
            } else {
                $to = date('Y-m-d', strtotime('+1 day'));
            }

            $reqData = ($asm == "NA") ? null : $asm;
            $reqQuery = ($asm == "NA") ? 'WHERE ro.asm IS null' : 'WHERE ro.asm LIKE "%'.$reqData.'%"';

			if($state!=''){
				$data = \DB::select('SELECT ro.ase AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            AND ro.state = "'.$state.'" 
            GROUP BY ro.ase');
			}else{
				$data = \DB::select('SELECT ro.ase AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            GROUP BY ro.ase');
			}
            

            return response()->json(['error' => false, 'message' => 'ASM wise ASE report', 'data' => $data]);
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
    }

    public function vpReportDetailAse(Request $request)
    {
        $validator = Validator::make($request->all(), [
            //"state" => "required|string|min:1",
            "ase" => "required|string|min:1",
            "from" => "nullable|date",
            "to" => "nullable|date",
        ]);

        if (!$validator->fails()) {
            $state = $request->state;
            $ase = $request->ase;

            // date from
            if (!empty($request->from)) {
                $from = $request->from;
            } else {
                $from = date('Y-m-01');
            }

            // date to
            if (!empty($request->to)) {
                $to = date('Y-m-d', strtotime($request->to. '+1 day'));
            } else {
                $to = date('Y-m-d', strtotime('+1 day'));
            }

            $reqData = ($ase == "NA") ? null : $ase;
            $reqQuery = ($ase == "NA") ? 'WHERE ro.ase IS null' : 'WHERE ro.ase LIKE "%'.$reqData.'%"';

			if($state!=''){
				$data = \DB::select('SELECT ro.retailer AS name, SUM(o.final_amount) AS value, s.id AS store_id FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            AND ro.state = "'.$state.'" 
            GROUP BY ro.retailer');
			}else{
				$data = \DB::select('SELECT ro.retailer AS name, SUM(o.final_amount) AS value, s.id AS store_id FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            GROUP BY ro.retailer');
			}
            

            return response()->json(['error' => false, 'message' => 'ASE wise Retailer report', 'data' => $data]);
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }

        /* 
        $validator = Validator::make($request->all(), [
            "state" => "required|string|min:1",
            "ase" => "required|string|min:1",
            "from" => "nullable|date",
            "to" => "nullable|date",
        ]);

        if (!$validator->fails()) {
            $state = $request->state;
            $ase = $request->ase;

            // date from
            if (!empty($request->from)) {
                $from = $request->from;
            } else {
                $from = date('Y-m-01');
            }

            // date to
            if (!empty($request->to)) {
                $to = date('Y-m-d', strtotime($request->to. '+1 day'));
            } else {
                $to = date('Y-m-d', strtotime('+1 day'));
            }

            $reqData = ($ase == "NA") ? null : $ase;
            $reqQuery = ($ase == "NA") ? 'WHERE ro.ase IS null' : 'WHERE ro.ase LIKE "%'.$reqData.'%"';

            $data = \DB::select('SELECT ro.distributor_name AS name, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            INNER JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.'
            AND ro.state = "'.$state.'" 
            GROUP BY ro.distributor_name');

            return response()->json(['error' => false, 'message' => 'ASE wise Distributor report', 'data' => $data]);
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        } */
    }

    public function vpReportDetailDistributor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "state" => "required|string|min:1",
            "distributor" => "required|string|min:1",
            "from" => "nullable|date",
            "to" => "nullable|date",
        ]);

        if (!$validator->fails()) {
            $state = $request->state;
            $distributor = $request->distributor;

            // date from
            if (!empty($request->from)) {
                $from = $request->from;
            } else {
                $from = date('Y-m-01');
            }

            // date to
            if (!empty($request->to)) {
                $to = date('Y-m-d', strtotime($request->to. '+1 day'));
            } else {
                $to = date('Y-m-d', strtotime('+1 day'));
            }

            $reqData = ($distributor == "NA") ? null : $distributor;
            $reqQuery = ($distributor == "NA") ? 'WHERE ro.distributor_name IS null' : 'WHERE ro.distributor_name LIKE "%'.$reqData.'%"';
			
			/*
			$data = \DB::select('SELECT ro.retailer AS name, SUM(o.final_amount) AS value, s.id AS store_id FROM `retailer_list_of_occ` AS ro 
            INNER JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            '.$reqQuery.' 
            AND ro.state = "'.$state.'" 
            GROUP BY ro.retailer');
			*/

            $data = \DB::select('SELECT ro.retailer AS name, SUM(o.final_amount) AS value, s.id AS store_id, SUM(op.qty) as value FROM `retailer_list_of_occ` AS ro 
            INNER JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            LEFT JOIN order_products AS op ON o.id = op.order_id 
            '.$reqQuery.' 
            AND ro.state = "'.$state.'" 
            GROUP BY ro.retailer');

            return response()->json(['error' => false, 'message' => 'Distributor wise Retailer report', 'data' => $data]);
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
    }

    public function vpReportDistributor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "required|integer|min:1",
            "from" => "nullable|date",
            "to" => "nullable|date",
        ]);

        if (!$validator->fails()) {
            $userId = $request->user_id;
            $user = User::findOrFail($userId);
            // $ase = $request->ase;

            // date from
            if (!empty($request->from)) {
                $from = $request->from;
            } else {
                $from = date('Y-m-01');
            }

            // date to
            if (!empty($request->to)) {
                $to = date('Y-m-d', strtotime($request->to. '+1 day'));
            } else {
                $to = date('Y-m-d', strtotime('+1 day'));
            }

            $data = \DB::select('SELECT ro.distributor_name AS name, ro.state AS state, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro 
            LEFT JOIN stores AS s ON ro.retailer = s.store_name 
            LEFT JOIN orders AS o ON s.id = o.store_id 
            WHERE ro.vp LIKE "%'.$user->name.'%" 
            GROUP BY ro.distributor_name');

            return response()->json(['error' => false, 'message' => 'ASE wise Distributor report', 'data' => $data]);
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
    }

    public function getVpCompleteReport(Request $request){
        //return response()->json(['error' => true, 'message' => 'error']);
        $collection_id = $request->collection_id;
        $category_id = $request->category_id;
        $keyword = $request->keyword;
        $start_date = $request->start_date;
        $end_date = $request->end_date;
        $state = $request->state;

        $sql = "select id,name,style_no,collection_id,cat_id from products where status=1 ";

        if($collection_id!=''){
            $where = " and collection_id='$collection_id'";
        }else{
            $where = "";
        }

        if($category_id!=''){
            $where1 = " and cat_id='$category_id'";
        }else{
            $where1 = "";
        }

        if($keyword!=''){
            $where2 = " and (name like '%$keyword%' or style_no like '%$keyword%')";
        }else{
            $where2 = "";
        }

        $products_data = DB::select($sql.$where.$where1.$where2);

        $products = array();

        foreach($products_data as $product){
            $product_id = $product->id;

            $sql1 = "select IFNULL(sum(qty),0) as total_quantity from order_products where product_id=$product_id";

            if($start_date!='' && $end_date!=''){
                if($state!=''){
                    $where3 = " and order_id in
                (select id from orders where created_at>'$start_date' and created_at<'$end_date' and user_id in (select id from users where state='$state'))";
                }else{
                    $where3 = " and order_id in
                (select id from orders where created_at>'$start_date' and created_at<'$end_date' )";
                }
                
            }else{
                if($state!=''){
                    $where3 = " and order_id in
                (select id from orders where user_id in (select id from users where state='$state') )";
                }else{
                    $where3 = "";
                }
            }
            
            $result = DB::select($sql1.$where3);

            $product->total_quantity = $result[0]->total_quantity;

            array_push($products,$product);
        }

        return response()->json(['error' => false, 'products' => $products]);
    }
}
