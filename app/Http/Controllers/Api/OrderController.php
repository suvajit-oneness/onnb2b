<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\OrderInterface;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct(OrderInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }
    /**
     * This method is for show store list
     * @return \Illuminate\Http\JsonResponse
     */

    public function list(Request $request): JsonResponse
    {

        $order = $this->orderRepository->listAll();

        return response()->json(['error'=>false, 'resp'=>'Order data fetched successfully','data'=>$order]);
    }

    /**
     * This method is for create store
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->only([
            'user_id', 'store_name', 'bussiness_name', 'store_OCC_number','contact','whatsapp','email','address','area','state','city','pin','image'
        ]);

        return response()->json(
            [
                'data' => $this->orderRepository->create($data)
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * This method is for show order details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($orderId): JsonResponse
    {


        $order = $this->orderRepository->listByorderId($orderId);

        return response()->json(['error'=>false, 'resp'=>'Order data fetched successfully','data'=>$order]);
    }

    /**
     * This method is for show order details
     * @param  $user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function view($userId): JsonResponse
    {


        $order = $this->orderRepository->listByuserId($userId);

         /*if(!function_exists('in_array_r')) {
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
        $orderProducts = \App\Models\OrderProduct::where('order_id', $ordercat->id)->get();


            foreach($orderProducts as $productKey => $productValue) {
                        $customCats[] = [
                           // 'id' => $productValue->colorDetails->id,
                            'color' => $productValue->colorDetails->name,

                        ];
                    }
                }*/
        return response()->json(['error'=>false, 'resp'=>'Order data fetched successfully','data'=>$order]);


    }
    /**
     * This method is for order update
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function update(Request $request, int $id)
    {


        $response = $this->orderRepository->update($id, $request->all());

        return response()->json(['status' => 200, 'message' => 'Data updated successfully', 'data' => $response]);
    }


    /**
     * This method is for Order delete
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $orderId = $request->route('id');
        $this->orderRepository->delete($orderId);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }


    public function datefilter(Request $request)
    {
        //dd($request->all());
        // $params = $request->except('_token');

        // // $name = $request->route('name');

        // $resp = $this->orderRepository->dateFilter();

        // return response()->json([
        //     'status' => 200,
        //     'data' => $resp
        // ]);
        $start = Carbon::parse($request->start_date);
        $end = Carbon::parse($request->end_date);
        $get_all_order = Order::whereDate('created_at','<=',$end)
        ->whereDate('created_at','>=',$start)
        ->get();
        return $get_all_order;

    }


    public function storefilter(Request $request,$id): JsonResponse
    {
    $params = $request->except('_token');

        // $name = $request->route('name');

        $resp = $this->orderRepository->storeFilter($id);


        /*if(!function_exists('in_array_r')) {
            function in_array_r($needle, $haystack, $strict = false) {
                foreach ($haystack as $item) {
                    if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) return true;
                }
                return false;
            }
        }
        $customCats = [];
        foreach($resp as $productKey => $ordercat) {
            if(in_array_r($ordercat->id, $customCats)) continue;
        $orderProducts = \App\Models\OrderProduct::where('order_id', $ordercat->id)->get();


            foreach($orderProducts as $productKey => $productValue) {
                        $customCats[] = [
                            'id' => $productValue->colorDetails->id,
                            'color' => $productValue->colorDetails->name,

                        ];
                    }
                }*/
        return response()->json(['error'=>false, 'resp'=>'Order data fetched successfully','data'=>$resp]);
    }
    public function orderfilter(Request $request,$id): JsonResponse
    {
        $params = $request->except('_token');

        // $name = $request->route('name');

        $resp = $this->orderRepository->listByorderId($id);




        return response()->json(['error'=>false, 'resp'=>'Order data fetched successfully','data'=>$resp]);
    }

    public function placeorder(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'min:1'],
            'store_id' => ['required', 'integer', 'min:1'],
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
            'user_id' => ['required'],
            'store_id' => ['required'],
            'order_type' => ['required', 'string', 'min:1'],
            'order_lat' => ['required', 'string', 'min:1'],
            'order_lng' => ['required', 'string', 'min:1'],
            'comment' => ['nullable', 'string', 'min:1'],
            // 'email' => ['required', 'email'],
            // 'mobile' => ['required', 'integer','digits:10'],
            // 'fname' => ['required', 'string'],
            // 'lname' => ['required', 'string'],
            // 'billing_country' => ['required', 'string'],
            // 'billing_address' => ['required', 'string'],
            // 'billing_landmark' => ['required', 'string'],
            // 'billing_city' => ['required', 'string'],
            // 'billing_state' => ['required', 'string'],
            // 'billing_pin' => ['required', 'integer','digits:6'],
            // 'shippingSameAsBilling' => ['required', 'integer','digits:1'],
            // 'shipping_country' => ['required', 'string'],
            // 'shipping_address' => ['required', 'string'],
            // 'shipping_landmark' => ['required', 'string'],
            // 'shipping_city' => ['required', 'string'],
            // 'shipping_state' => ['required', 'string'],
            // 'shipping_pin' => ['required', 'integer','digits:6'],
            // 'shipping_method' => ['required', 'string'],
            // 'shipping_charges' => ['required', 'integer'],
            // 'tax_amount' => ['required', 'integer'],
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
                    'data' => $this->orderRepository->placeOrderUpdated($params)
                ],
                Response::HTTP_CREATED
            );
        } else {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }

    }



    public function report($storeId): JsonResponse{

        $totalsales = $this->orderRepository->Totalsales($storeId);
        $latestsales = $this->orderRepository->latestsales($storeId);
        $lastorder = $this->orderRepository->lastOrder($storeId);
        $avgOrder = $this->orderRepository->avgOrder($storeId);
        $lastvisit = $this->orderRepository->lastVisit($storeId);
        // $storesCustom = [];

        $storesCustom[] = [
            'totalSalesHistory'=>$totalsales,
            'last3MonthsSale'=>$latestsales,
            'lastOrderValue'=>$lastorder,
            'averageOrderValue'=>$avgOrder,
            'lastVisit'=>$lastvisit,
        ];
        return response()->json(['error'=>false, 'resp'=>'Order data fetched successfully','data'=>$storesCustom]);
       }

//order details product wise

public function orderproduct($storeId,$productId): JsonResponse{

    $totalamount = $this->orderRepository->Totalamount($storeId,$productId);
    $totalorder = $this->orderRepository->TotalOrder($storeId,$productId);

    // $storesCustom = [];

    $storesCustom[] = [
        'TotalAmount'=>$totalamount,
        'TotalOrder'=>$totalorder,

    ];
    return response()->json(['error'=>false, 'resp'=>'Order data fetched successfully','data'=>$storesCustom]);
   }


}
