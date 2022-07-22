<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CartInterface;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function __construct(CartInterface $CartRepository)
    {
        $this->CartRepository = $CartRepository;
    }



    /**
     * This method is for show cart list
     * @return \Illuminate\Http\JsonResponse
     */

    public function list(Request $request): JsonResponse
    {

        $cart = $this->CartRepository->listAll();

        return response()->json(['error'=>false, 'resp'=>'Cart data fetched successfully','data'=>$cart]);
    }
    /**
     * This method is for show cart details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($userId): JsonResponse
    {

        $cart = $this->CartRepository->listById($userId);

        return response()->json(['error'=>false, 'resp'=>'Cart data fetched successfully','data'=>$cart]);
    }

    public function showByUser($userId): JsonResponse
    {

        // $cart = $this->CartRepository->listById($userId);
        $cart = Cart::where('user_id', $userId)->with('colorDetails', 'sizeDetails')->get();

        return response()->json(['error'=>false, 'resp'=>'Cart data fetched successfully', 'data'=>$cart]);
    }

    public function couponCheck(Request $request)
    {
        $couponData = $this->CartRepository->couponCheck($request->code);
        return $couponData;
    }

    public function couponRemove(Request $request)
    {
        $couponData = $this->CartRepository->couponRemove();
        return $couponData;
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
            //'product_image' => ['required', 'string'],
            //'product_slug' => ['required', 'string'],
            //'product_variation_id' => ['required', 'integer'],
            'price' => ['required', 'integer'],
            'offer_price' => ['required', 'integer'],
            'qty' => ['required', 'integer'],

        ]);

        if (!$validator->fails()) {
            // return response()->json(['status' => 200, 'message' => 'okay']);
            $data = $this->CartRepository->addToCart($request->all());
        } else {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }
        $params = $request->except('_token');
        return response()->json(
            [
                'data' => $this->CartRepository->addToCart($params)
            ],
            Response::HTTP_CREATED
        );
    }

    public function bulkAddcart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'min:1'],
            'device_id' => ['nullable'],
            'product_id' => ['required', 'integer'],
            'product_name' => ['required', 'string'],
            'product_style_no' => ['required', 'string'],
            //'product_slug' => ['required', 'string'],
            //'product_variation_id' => ['nullable', 'integer'],
            'color' => ['required'],
            'size' => ['required'],
            'price' => ['required'],
            'qty' => ['required'],
            // 'price' => ['required', 'integer'],
            // 'offer_price' => ['required', 'integer'],
            // 'qty' => ['required', 'integer'],
        ]);

        if (!$validator->fails()) {
            // $data = $this->CartRepository->bulkAddCart($request->all());
            $params = $request->except('_token');
            $data = $this->CartRepository->bulkAddCart($params);

            if ($data) {
                return response()->json(['error' => false, 'resp' => 'Product successfully added to cart'], Response::HTTP_CREATED);
            } else {
                return response()->json(['error' => true, 'resp' => 'Something happened'], Response::HTTP_CREATED);
            }
        } else {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }
    }

    public function simpleBulkAddcart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'min:1'],
            'device_id' => ['nullable'],
            'product_id' => ['required', 'integer'],
            'product_name' => ['required', 'string'],
            'product_style_no' => ['required', 'string'],
            'product_slug' => ['required', 'string'],
            'product_variation_id' => ['required', 'integer'],
            'color' => ['required', 'integer'],
            'size' => ['required'],
            'price' => ['required'],
            'qty' => ['required'],
            // 'price' => ['required', 'integer'],
            // 'offer_price' => ['required', 'integer'],
            // 'qty' => ['required', 'integer'],
        ]);

        if (!$validator->fails()) {
            // $data = $this->CartRepository->bulkAddCart($request->all());
            $params = $request->except('_token');
            $data = $this->CartRepository->simpleBulkAddcart($params);

            if ($data) {
                return response()->json(['error' => false, 'resp' => 'Product successfully added to cart', 'data' => $data], Response::HTTP_CREATED);
            } else {
                return response()->json(['error' => true, 'resp' => 'Something happened'], Response::HTTP_CREATED);
            }
        } else {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }
    }

    public function viewByIp(Request $request)
    {
        $data = $this->CartRepository->viewByIp();

        if ($data) {
            return view('front.cart.index', compact('data'));
        } else {
            return view('front.404');
        }
    }

    public function delete(Request $request, $id)
    {
        $data = $this->CartRepository->delete($id);

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
        $newDetails=Cart::findOrFail($id);

    // Check if the user entered an ADD quantity
    if ($addQty = $request->get('add_quantity')) {
        $newDetails->quantity += $addQty;
    } elseif ($newQty = $request->get('quantity')) {
        $newDetails->quantity = $newQty;
    }
    $newDetails->save();
}


}
