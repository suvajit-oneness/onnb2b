<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CartInterface;
use App\Interfaces\OrderInterface;
use App\Interfaces\UserInterface;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Models\CartDistributor;
use App\Models\Target;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\User;
use Illuminate\Support\Facades\Validator;
class DistributorController extends Controller
{
    public function __construct(CartInterface $CartRepository,OrderInterface $orderRepository,UserInterface $userRepository)
    {
        $this->CartRepository = $CartRepository;
         $this->orderRepository = $orderRepository;
          $this->userRepository = $userRepository;
    }

    /**
     * This method is for show cart list
     * @return \Illuminate\Http\JsonResponse
     */

    public function list(Request $request): JsonResponse
    {
		//$ase = $_GET['ase'];
		//$cart = DB::select("select * from distributors where business_name in (select distinct distributor_name from retailer_list_of_occ
		//where ase='$ase')");
        $cart = $this->CartRepository->listDistributororderAll();

        return response()->json(['error'=>false, 'resp'=>'Distributor Cart data fetched successfully','data'=>$cart]);
    }
    /**
     * This method is for show cart details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id): JsonResponse
    {

        $cart = $this->CartRepository->listDistributorById($id);

        return response()->json(['error'=>false, 'resp'=>' Distributor Cart data fetched successfully','data'=>$cart]);
    }



    public function showByUser($userId): JsonResponse
    {

        // $cart = $this->CartRepository->listById($userId);
        $cart = CartDistributor::where('user_id', $userId)->with('colorDetails', 'sizeDetails')->get();

        return response()->json(['error'=>false, 'resp'=>'Distributor Cart data fetched successfully', 'data'=>$cart]);
    }

    /**
     * This method is for add cart details
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function addcart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'min:1'],
            'product_id' => ['required', 'integer'],
            'product_name' => ['required', 'string'],
            'product_style_no' => ['required', 'string'],
            'product_image' => ['required', 'string'],
            'product_slug' => ['required', 'string'],
            'product_variation_id' => ['required', 'integer'],
            'price' => ['required', 'integer'],
            'offer_price' => ['required', 'integer'],
            'qty' => ['required', 'integer'],

        ]);

        if (!$validator->fails()) {
            // return response()->json(['status' => 200, 'message' => 'okay']);
            $data = $this->CartRepository->DistributoraddToCart($request->all());
        } else {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }
        $params = $request->except('_token');
        return response()->json(
            [
                'data' => $this->CartRepository->DistributoraddToCart($params)
            ],
            Response::HTTP_CREATED
        );
    }

    public function DistributorbulkAddcart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'min:1'],
            'device_id' => ['nullable'],
            'product_id' => ['required', 'integer'],
            'product_name' => ['required', 'string'],
            'product_style_no' => ['required', 'string'],
            'product_slug' => ['required', 'string'],
            'product_variation_id' => ['nullable', 'integer'],
            'color' => ['required', 'integer'],
            'size' => ['required'],
            'price' => ['nullable'],
            'qty' => ['required'],
            // 'price' => ['required', 'integer'],
            // 'offer_price' => ['required', 'integer'],
            // 'qty' => ['required', 'integer'],
        ]);

        if (!$validator->fails()) {
            // $data = $this->CartRepository->bulkAddCart($request->all());
            $params = $request->except('_token');
            $data = $this->CartRepository->bulkAddCartDistributor($params);

            if ($data) {
                return response()->json(['error' => false, 'resp' => 'Product successfully added to cart'], Response::HTTP_CREATED);
            } else {
                return response()->json(['error' => true, 'resp' => 'Something happened'], Response::HTTP_CREATED);
            }
        } else {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }
    }

    /**
     * This method is for update quantity
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function qtyUpdate(Request $request, $id)
   {

        // $newDetails = $request->except('_token');


        // $response = $this->cartRepository->qtyUpdate($id,$newDetails);

        // //$response = $this->storeRepository->update($id, $request->all());

        // return response()->json(['status' => 200, 'message' => 'Quantity updated successfully', 'data' => $response]);
        $newDetails=CartDistributor::findOrFail($id);

    // Check if the user entered an ADD quantity
    if ($addQty = $request->get('add_quantity')) {
        $newDetails->quantity += $addQty;
    } elseif ($newQty = $request->get('quantity')) {
        $newDetails->quantity = $newQty;
    }
    $newDetails->save();
}



   public function cartdelete(Request $request, $id)
    {
        $data = $this->CartRepository->deleteDistributor($id);

        if ($data) {
            return response()->json(['error' => false, 'resp' => 'Product removed from cart']);
            // return response()->json(null, Response::HTTP_NO_CONTENT);
        } else {
            return response()->json(['error' => true, 'resp' => 'Something happened']);
            # code...
        }

        // return response()->json(null, Response::HTTP_NO_CONTENT);

       // $this->userRepository->useractivitydelete($activityId);

        // if ($data) {
        //     return redirect()->route('front.cart.index')->with('success', 'Product removed from cart');
        // } else {
        //     return redirect()->route('front.cart.index')->with('failure', 'Something happened');
        // }
    }

//place order


    public function placeorder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'min:1'],

            'email' => ['required', 'email'],
            'mobile' => ['required', 'integer','digits:10'],
            'fname' => ['required', 'string'],
            'lname' => ['required', 'string'],
            'billing_country' => ['required', 'string'],
            'billing_address' => ['required', 'string'],
            'billing_landmark' => ['required', 'string'],
            'billing_city' => ['required', 'string'],
            'billing_state' => ['required', 'string'],
            'billing_pin' => ['required', 'integer','digits:6'],
            'shippingSameAsBilling' => ['required', 'integer','digits:1'],
            'shipping_country' => ['required', 'string'],
            'shipping_address' => ['required', 'string'],
            'shipping_landmark' => ['required', 'string'],
            'shipping_city' => ['required', 'string'],
            'shipping_state' => ['required', 'string'],
            'shipping_pin' => ['required', 'integer','digits:6'],
            'shipping_method' => ['required', 'string'],
            //'amount' => ['required', 'integer'],
            'shipping_charges' => ['required', 'integer'],
            'tax_amount' => ['required', 'integer'],
        ]);

        if (!$validator->fails()) {
            // return response()->json(['status' => 200, 'message' => 'okay']);
            $data = $this->orderRepository->placeOrder($request->all());
        } else {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }
        $params = $request->except('_token');
        return response()->json(
            [
                'data' => $this->orderRepository->placeOrder($params)
            ],
            Response::HTTP_CREATED
        );
    }

    public function placeOrderUpdate(Request $request): JsonResponse
    {
       $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'min:1'],
            'store_id' => ['nullable'],
            'order_type' => ['nullable'],
            'order_lat' => ['required', 'string', 'min:1'],
            'order_lng' => ['required', 'string', 'min:1'],
            'comment' => ['nullable', 'string', 'min:1'],
        ]);

        if (!$validator->fails()) {
            $params = $request->except('_token');
            // echo "<pre>";
            // print_r($params);
            // die();
            return response()->json(
                [
                    'error' => false,
                    'resp' => 'Order placed successfully',
                    'data' => $this->orderRepository->OrderUpdatedDistributor($params)
                ],
                Response::HTTP_CREATED
            );
        } else {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }

    }



    //target by user id
    public function target($userId): JsonResponse
    {

        // $cart = $this->CartRepository->listById($userId);
        $cart = Target::where('user_id', $userId)->where('user_type', 5)->get();

        return response()->json(['error'=>false, 'resp'=>'Distributor Target data fetched successfully', 'data'=>$cart]);
    }


    public function salesreport(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "user_id" => "required|integer|min:1"
        ]);

        if (!$validator->fails()) {
            $userId = $request->user_id;
            $user = User::findOrFail($userId);


            if ($user->user_type == 5) {
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

           $data = DB::select('SELECT SUM(final_amount) AS value FROM orders_distributors
                WHERE (created_at BETWEEN "'.$from.'" AND "'.$to.'")
                AND user_id = "'.$userId.'"');


            return response()->json(['error' => false, 'message' => 'State wise report', 'stateWiseReport' => $data]);

            } else {
                return response()->json(['error' => true, 'message' => 'Please provide user id of Distributor']);
            }
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
    }

    /**
     * This method is for show order details
     * @param  $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function orderview($userId): JsonResponse
    {


       $order = $this->orderRepository->listByuserIdForDistributor($userId);
        if(!function_exists('in_array_r')) {
            function in_array_r($needle, $haystack, $strict = false) {
                foreach ($haystack as $item) {
                    if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) return true;
                }
                return false;
            }
        }
        $customCats = [];
        foreach($order as $productKey => $ordercat) {
            if(in_array_r($ordercat->id, $customCats)) continue;
        $orderProducts = \App\Models\OrderProductDistributor::where('order_id', $ordercat->id)->get();


            foreach($orderProducts as $productKey => $productValue) {
                        $customCats[] = [
                            'id' => $productValue->colorDetails->id,
                            'color' => $productValue->colorDetails->name,

                        ];
                    }
                }
        return response()->json(['error'=>false, 'resp'=>'Order data fetched successfully','data'=>$order,'color'=> $customCats]);


    }


    //edit profile


     /**
     * This method is for show user profile details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function editprofile($id): JsonResponse
    {
        $user = $this->userRepository->userdetailsById($id);
        return response()->json(['error'=>false, 'resp'=>'User data fetched successfully','data'=>$user[0]]);

    }

    /**
     * This method is to update user profile details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateprofile(Request $request,$id): JsonResponse
    {
        $validator = Validator::make($request->all(), [

            'fname' => ['nullable', 'string'],
            'lname' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'mobile' => ['nullable', 'numeric'],
            'address' => ['nullable', 'string'],
            'landmark' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'pin' => ['nullable','numeric','digits:6'],
            'aadhar_no' => ['nullable'],
            'pan_no' => ['nullable'],
            'gender' => ['nullable'],
            'whatsapp_no' => ['nullable','numeric'],
            'image' => ['nullable'],
            'anniversary_date' => ['nullable','date'],
            'social_id' => ['nullable'],
            'dob' => ['nullable','date'],


        ]);
        // $newDetails = $request->only([
        //     'name', 'fname','lname', 'email', 'password','type','mobile','address','landmark','state','city','pin','aadhar_no','pan_no','gender','employee_id','whatsapp_no','otp','image','dob','anniversary_date','social_id'
        // ]);

       if (!$validator->fails()) {

           $data = $this->userRepository->updateuserprofile($id, $request->all());
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
    }



     public function updatePassword(Request $request)
    {

            $request->validate([
                "old_password" => "required|string|max:255",
                "new_password" => "required|string|max:255",
                "confirm_password" => "required|string|max:255|same:new_password",
            ]);

        $params = $request->except('_token');
        $storeData = $this->userRepository->updatePassword($params);

        if ($storeData) {
           return response()->json(['error'=>false, 'resp'=>'Password data changed successfully','data'=>$storeData]);
        } else {
            return redirect()->json(['error'=>true, 'resp'=>'Something happened']);
        }
    }





//ase wise distributor
public function vpReportDistributor(Request $request)
{

        $validator = Validator::make($request->all(), [
        "state" => "required|string|min:1",
          "area" => "required|string|min:1",
        "ase" => "required|string|min:1",

    ]);

    if (!$validator->fails()) {
        $state = $request->state;
         $area = $request->area;
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

        $data = \DB::select('SELECT u.id AS ase_userid ,ui.id AS distributor_userid,ro.distributor_name AS distributorname, ro.state AS state,ro.area AS area  , SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
        INNER JOIN stores AS s ON ro.retailer = s.store_name
        LEFT JOIN orders AS o ON s.id = o.store_id
        LEFT JOIN users AS u ON o.user_id = u.id
        LEFT JOIN users AS ui ON ro.distributor_name = ui.name
        WHERE ro.ase LIKE "%'.$ase.'%"
        GROUP BY ro.distributor_name');

        return response()->json(['error' => false, 'message' => 'ASE wise Distributor report', 'data' => $data]);
     }else {
        return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
    }
}


    //asm wise distributor
    public function asmDistributor(Request $request)
    {

            $validator = Validator::make($request->all(), [
            "state" => "required|string|min:1",
            "asm" => "required|string|min:1",

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

            $data = \DB::select('SELECT ro.distributor_name AS name,u.id AS distributor_userid, ro.state AS state, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
            LEFT JOIN stores AS s ON ro.retailer = s.store_name
            LEFT JOIN orders AS o ON s.id = o.store_id
            LEFT JOIN users AS u ON ro.distributor_name = u.name
            WHERE ro.asm LIKE "%'.$asm.'%"
            GROUP BY ro.distributor_name');

            return response()->json(['error' => false, 'message' => 'ASM wise Distributor report', 'data' => $data]);
         }else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
    }


    //rsm wise distributor
    public function rsmDistributor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "state" => "required|string|min:1",
            "rsm" => "required|string|min:1",

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

            $data = \DB::select('SELECT ro.distributor_name AS name,u.id AS distributor_userid, ro.state AS state, SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
            LEFT JOIN stores AS s ON ro.retailer = s.store_name
            LEFT JOIN orders AS o ON s.id = o.store_id
             LEFT JOIN users AS u ON ro.distributor_name = u.name
            WHERE ro.rsm LIKE "%'.$rsm.'%"
            GROUP BY ro.distributor_name');

            return response()->json(['error' => false, 'message' => 'RSM wise Distributor report', 'data' => $data]);
         }else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
    }


     /**
     * This method is for create mom
     *
     */
    public function momStore(Request $request): JsonResponse
    {
        //dd($request->all);
        $data = $request->only([
            'user_id', 'comment', 'distributor_name'
        ]);
        //dd($data);
        $data = DB::insert('insert into directory_mom (user_id, distributor_name, comment) values (?, ?, ?)', [$request->user_id, $request->distributor_name, $request->comment]);
         //dd($stores);
        return response()->json(['error'=>false, 'resp'=>'MOM added successfully','data'=>$data]);
    }
//ase wise retailer
public function vpReportRetailer(Request $request)
{

        $validator = Validator::make($request->all(), [
        "state" => "required|string|min:1",
        "area" => "required|string|min:1",
        "ase" => "required|string|min:1",

    ]);

    if (!$validator->fails()) {
        $state = $request->state;
         $area = $request->area;

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

        $data = \DB::select('SELECT u.id AS ase_userid, ro.retailer AS retailername, ro.state AS state  ,ro.area AS area , SUM(o.final_amount) AS value FROM `retailer_list_of_occ` AS ro
        INNER JOIN stores AS s ON ro.retailer = s.store_name
        LEFT JOIN orders AS o ON s.id = o.store_id
        LEFT JOIN users AS u ON o.user_id = u.id

        WHERE ro.ase LIKE "%'.$ase.'%"
        GROUP BY ro.retailer');

        return response()->json(['error' => false, 'message' => 'ASE wise Retailer report', 'data' => $data]);
     }else {
        return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
    }
}


//sales report ase wise

public function aseSalesreport(Request $request){

    $validator = Validator::make($request->all(), [
        "ase" => "required|string|min:1",
    ]);
    if (!$validator->fails()) {
        $ase = $request->ase;

        if ( request()->input('from') || request()->input('to') ) {
            // date from
            if (!empty(request()->input('from'))) {
                $from = request()->input('from');
            } else {
                $from = $first_day_this_month = date('Y-m-01');
            }

            // date to
            if (!empty(request()->input('to'))) {
                $to = date('Y-m-d', strtotime(request()->input('to'). '+1 day'));
            } else {
                $to = $current_day_this_month = date('Y-m-d', strtotime('+1 day'));
            }

            $from = $request->from;
            $to = date('Y-m-d', strtotime($request->to. '+1 day'));
            $ase_name = DB::select("select name from users where name LIKE '%".$ase."%'");

            $primaryreport = DB::select("SELECT distributor_name FROM `retailer_list_of_occ` AS ro
            WHERE ase LIKE '%".$ase_name[0]->name."%'
            GROUP BY distributor_name
            ORDER BY distributor_name ");
            //dd($primaryreport);
            $respArrd = [];
            foreach ($primaryreport as $key => $item) {
                $report1 = \DB::select("SELECT od.user_id AS id,SUM(od.final_amount) AS amount, SUM(opd.qty) AS qty FROM `orders_distributors` AS od
                INNER JOIN order_products_distributors AS opd
                ON od.id = opd.order_id

                WHERE od.distributor_name = '".$item->distributor_name."'  AND (od.created_at BETWEEN '".$from."' AND '".$to."') ");

               //dd($report1);
                $respArrd[] = [
                    'distributor_id'=> $report1[0]->id ?? 0,
                    'distributor_name' => $item->distributor_name,
                    // 'amount' => $report1[0]->amount ?? 0,
					'amount' => 0,
                    'qty' => $report1[0]->qty ?? 0,
                ];
            }

            $from = $request->from;
            $to = date('Y-m-d', strtotime($request->to. '+1 day'));

            $ase_id = DB::select("select id from users where name LIKE '%".$ase."%'");

            $secondaryreport = DB::select("SELECT s.store_name AS name, s.id FROM `stores` AS s
            WHERE s.user_id = '".$ase_id[0]->id."' ");
            //dd($secondaryreport);
            $respArr = [];

            foreach ($secondaryreport as $key => $value) {
                $report = \DB::select("SELECT SUM(o.final_amount) AS amount, SUM(op.qty) AS qty FROM `orders` AS o
                INNER JOIN order_products AS op
                ON o.id = op.order_id
                WHERE o.store_id = '".$value->id."' AND (o.created_at BETWEEN '".$from."' AND '".$to."') ");
               //dd($value);
                $respArr[] = [
                    'retailer_id' => $value->id,
                    'store_name' => $value->name,
                    'amount' => $report[0]->amount ?? 0,
                    'qty' => $report[0]->qty ?? 0,
                ];

            }
        } else {
            $ase_name = DB::select("select name from users where name LIKE '%".$ase."%'");
            $primaryreport = DB::select("SELECT u.id AS user_id,distributor_name FROM `retailer_list_of_occ` AS ro
            INNER JOIN users AS u
                ON u.name = ro.distributor_name
            WHERE ase LIKE '%".$ase_name[0]->name."%'
            GROUP BY distributor_name
            ORDER BY distributor_name ");
            $respArrd = [];
            foreach ($primaryreport as $key => $item) {
                $report1 = \DB::select("SELECT u.id AS user_id, SUM(od.final_amount) AS amount, SUM(opd.qty) AS qty FROM `orders_distributors` AS od
                INNER JOIN order_products_distributors AS opd
                ON od.id = opd.order_id
                INNER JOIN users AS u ON od.user_id = u.id
                WHERE od.distributor_name = '".$item->distributor_name."' AND DATE(od.created_at) = CURDATE() ");

                $respArrd[] = [
                    'distributor_id'=> $item->user_id,
                    'distributor_name' => $item->distributor_name,
                    'amount' => $report1[0]->amount ?? 0,
                    'qty' => $report1[0]->qty ?? 0,
                ];
            }
            $ase_id = DB::select("select id from users where name LIKE '%".$ase."%'");

            $secondaryreport = DB::select("SELECT s.store_name AS name, s.id FROM `stores` AS s
            WHERE s.user_id = '".$ase_id[0]->id."' ");
            $respArr = [];

            foreach ($secondaryreport as $key => $value) {
                $report = \DB::select("SELECT SUM(o.final_amount) AS amount, SUM(op.qty) AS qty FROM `orders` AS o
                INNER JOIN order_products AS op
                ON o.id = op.order_id
                WHERE o.store_id = '".$value->id."' AND DATE(o.created_at) = CURDATE() ");

                $respArr[] = [
                    'retailer_id' => $value->id,
                    'store_name' => $value->name,
                    'amount' => $report[0]->amount ?? 0,
                    'qty' => $report[0]->qty ?? 0,
                ];

            }
        }
        return response()->json(['error' => false, 'message' => 'ASE wise Primary Sales report', 'Primary Sales|Distributor wise Daily Report' => $respArrd,'Secondary Sales|Retailer wise Daily Report' => $respArr]);
    }else {
        return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
    }
}



//distributor order details date wise
public function distributorOrderReport(Request $request)
{
    $validator = Validator::make($request->all(), [
        "ase" => "required|string|min:1",
    ]);
    if (!$validator->fails()) {
        $ase = $request->ase;

        if ( request()->input('from') || request()->input('to') ) {
            // date from
            if (!empty(request()->input('from'))) {
                $from = request()->input('from');
            } else {
                $from = $first_day_this_month = date('Y-m-01');
            }

            // date to
            if (!empty(request()->input('to'))) {
                $to = date('Y-m-d', strtotime(request()->input('to'). '+1 day'));
            } else {
                $to = $current_day_this_month = date('Y-m-d', strtotime('+1 day'));
            }

            $from = $request->from;
            $to = date('Y-m-d', strtotime($request->to. '+1 day'));
           // $ase_name = DB::select("select name from users where name LIKE '%".$ase."%'");
            $primaryreport = DB::select("SELECT distributor_name FROM `retailer_list_of_occ`
            WHERE ase LIKE '%".$ase."%'
            GROUP BY distributor_name
            ORDER BY distributor_name ");
            //dd($primaryreport);
            $respArrd = [];
            $orders=[];
            $products=[];
            foreach ($primaryreport as $key => $item) {
                $report1 = \DB::select("SELECT SUM(od.final_amount) AS amount, SUM(opd.qty) AS qty FROM `orders_distributors` AS od
                INNER JOIN order_products_distributors AS opd
                ON od.id = opd.order_id
                WHERE od.distributor_name = '".$item->distributor_name."' AND (od.created_at BETWEEN '".$from."' AND '".$to."') ");
                $orders=
                \DB::select("SELECT * FROM `orders_distributors` AS od
                WHERE od.distributor_name = '".$item->distributor_name."' AND (od.created_at BETWEEN '".$from."' AND '".$to."') ");

                foreach ($orders as $key => $data) {
                $products=
                \DB::select("SELECT * FROM `order_products_distributors` AS opd
                WHERE opd.order_id = '".$data->id."' AND (opd.created_at BETWEEN '".$from."' AND '".$to."') ");
                }
                $respArrd[] = [
                    'distributor_name' => $item->distributor_name,
                    'orders' => $orders,
                     'products'=> $products,


                ];
            }
            $ase_id = DB::select("select id from users where name LIKE '%".$ase."%'");

            $secondaryreport = DB::select("SELECT s.store_name AS name, s.id FROM `stores` AS s
            WHERE s.user_id = '".$ase_id[0]->id."' ");
            $respArr = [];
            $orders=[];
            $products=[];
            foreach ($secondaryreport as $key => $value) {
                $report = \DB::select("SELECT SUM(o.final_amount) AS amount, SUM(op.qty) AS qty FROM `orders` AS o
                INNER JOIN order_products AS op
                ON o.id = op.order_id
                WHERE o.store_id = '".$value->id."' AND (o.created_at BETWEEN '".$from."' AND '".$to."') ");

                $orders=
                \DB::select("SELECT * FROM `orders` AS o
                WHERE o.store_id = '".$value->id."' AND (o.created_at BETWEEN '".$from."' AND '".$to."') ");
              //dd($orders);
                foreach ($orders as $key => $data) {
                $products=
                \DB::select("SELECT * FROM `order_products` AS op
                WHERE op.order_id = '".$data->id."' AND (op.created_at BETWEEN '".$from."' AND '".$to."') ");
                }

               //dd($value);
                $respArr[] = [
                    'store_id' => $value->id,
                    'store_name' => $value->name,
                    'orders' => $orders,
                    'products'=> $products,
                ];

            }
            }



         else {
            $ase_name = DB::select("select name from users where name LIKE '%".$ase."%'");
            $primaryreport = DB::select("SELECT distributor_name FROM `retailer_list_of_occ`
            WHERE ase LIKE '%".$ase_name[0]->name."%'
            GROUP BY distributor_name
            ORDER BY distributor_name ");
            $respArrd = [];
            $orders=[];
            $products=[];
            foreach ($primaryreport as $key => $item) {
                $report1 = \DB::select("SELECT SUM(od.final_amount) AS amount, SUM(opd.qty) AS qty FROM `orders_distributors` AS od
                INNER JOIN order_products_distributors AS opd
                ON od.id = opd.order_id
                WHERE od.distributor_name = '".$item->distributor_name."' AND DATE(od.created_at) = CURDATE() ");
                $orders=
                \DB::select("SELECT * FROM `orders_distributors` AS od
                WHERE od.distributor_name = '".$item->distributor_name."' AND DATE(od.created_at) = CURDATE() ");
                 foreach ($orders as $key => $data) {
                    $products=
                    \DB::select("SELECT * FROM `order_products_distributors` AS opd
                    WHERE opd.order_id = '".$data->id."' AND DATE(od.created_at) = CURDATE() ");
                    }
                    $respArrd[] = [
                        'distributor_name' => $item->distributor_name,
                        'orders' => $orders,
                         'products'=> $products,


                    ];

            }

            $ase_id = DB::select("select id from users where name LIKE '%".$ase."%'");

            $secondaryreport = DB::select("SELECT s.store_name AS name, s.id FROM `stores` AS s
            WHERE s.user_id = '".$ase_id[0]->id."' ");
            $respArr = [];
            $orders=[];
            $products=[];
            foreach ($secondaryreport as $key => $value) {
                $report = \DB::select("SELECT SUM(o.final_amount) AS amount, SUM(op.qty) AS qty FROM `orders` AS o
                INNER JOIN order_products AS op
                ON o.id = op.order_id
                WHERE o.store_id = '".$value->id."' AND DATE(o.created_at) = CURDATE() ");
                    $orders=
                    \DB::select("SELECT * FROM `orders` AS o
                    WHERE o.id = '".$value->id."' AND DATE(o.created_at) = CURDATE() ");

                    foreach ($orders as $key => $data) {
                    $products=
                    \DB::select("SELECT * FROM `order_products` AS op
                    WHERE op.order_id = '".$data->id."' AND DATE(op.created_at) = CURDATE() ");
             }
                $respArr[] = [
                    'store_id' => $value->id,
                    'store_name' => $value->name,
                    'orders' => $orders,
                    'products'=> $products,
                ];


            }

        }

        return response()->json(['error' => false, 'message' => ' Distributor Order report', 'Primary Order|Distributor wise Daily Report' => $respArrd,'Secondary Order|Retailer wise Order Report' => $respArr]);
    }else {
        return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
    }
}


}
