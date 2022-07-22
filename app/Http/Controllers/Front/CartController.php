<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\CartInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Cart;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function __construct(CartInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function couponCheck(Request $request)
    {
        $couponData = $this->cartRepository->couponCheck($request->code);
        return $couponData;
    }

    public function couponRemove(Request $request)
    {
        $couponData = $this->cartRepository->couponRemove();
        return $couponData;
    }

    public function add(Request $request)
    {
        // dd($request->all());

        $request->validate([
            "product_id" => "required|string|max:255",
            "product_name" => "required|string|max:255",
            "product_style_no" => "required|string|max:255",
            "product_image" => "required|string|max:255",
            "product_slug" => "required|string|max:255",
            "product_variation_id" => "nullable|integer",
            "price" => "required|string",
            "offer_price" => "required|string",
            "qty" => "required|integer",
        ]);

        $params = $request->except('_token');

        $cartStore = $this->cartRepository->addToCart($params);

        if ($cartStore) {
            return redirect()->back()->with('success', 'Product added to cart');
        } else {
            return redirect()->back()->with('failure', 'Something happened');
        }
    }

    public function addBulk(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'min:1'],
            'device_id' => ['nullable'],
            'store_id' => ['nullable'],
            'order_type' => ['nullable'],
            'product_id' => ['required', 'integer'],
            'product_name' => ['required', 'string'],
            'product_style_no' => ['required', 'string'],
            'product_slug' => ['required', 'string'],
            'product_variation_id' => ['nullable', 'integer'],
            'color' => ['required', 'integer'],
            'size' => ['required'],
            'price' => ['required'],
            'qty' => ['required'],
            'master_pack_count' => ['required'],
            // 'price' => ['required', 'integer'],
            // 'offer_price' => ['required', 'integer'],
            // 'qty' => ['required', 'integer'],
        ]);

        if (!$validator->fails()) {
            $updatedQty = $updatedSize = $updatedPrice = '';
            for($i = 0; $i < count($request->qty); $i++) {
                if ($request->qty[$i] != 0) {
                    $updatedQty .= $request->qty[$i].'*';
                    $updatedSize .= $request->size[$i].'*';
                    $updatedPrice .= $request->price[$i].'*';
                }
            }

            // dd($updatedQty);

            if ($updatedQty) {
                $updatedQty = substr($updatedQty, 0, -1);
                $updatedSize = substr($updatedSize, 0, -1);
                $updatedPrice = substr($updatedPrice, 0, -1);
                // dd($updatedQty, $updatedSize, $updatedPrice);

                $params = array(
                    'user_id' => $request->user_id,
                    'device_id' => $request->device_id ?? null,
                    'store_id' => $request->store_id ?? null,
                    'order_type' => $request->order_type ?? null,
                    'product_id' => $request->product_id,
                    'product_name' => $request->product_name,
                    'product_style_no' => $request->product_style_no,
                    'product_slug' => $request->product_slug,
                    'color' => $request->color,
                    'qty' => $updatedQty,
                    'size' => $updatedSize,
                    'price' => $updatedPrice,
                );

                // dd($params);

                $data = $this->cartRepository->bulkAddCart($params);

                if ($data) {
                    // return response()->json(['error' => false, 'resp' => 'Product successfully added to cart'], Response::HTTP_CREATED);
                    return redirect()->back()->with('success', 'Product added to cart');
                } else {
                    // return response()->json(['error' => true, 'resp' => 'Something happened'], Response::HTTP_CREATED);
                    return redirect()->back()->with('failure', 'Something happened');
                }
            } else {
                return redirect()->back()->with('failure', 'Please enter quantity');
            }

        } else {
            // return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
            return redirect()->back()->with('failure', $validator->errors()->first());
        }
    }

    public function addBulkDistributor(Request $request)
    {
        // dd($request->all());

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
            'price' => ['required'],
            'qty' => ['required'],
            // 'price' => ['required', 'integer'],
            // 'offer_price' => ['required', 'integer'],
            // 'qty' => ['required', 'integer'],
        ]);

        if (!$validator->fails()) {
            $updatedQty = $updatedSize = $updatedPrice = '';
            for($i = 0; $i < count($request->qty); $i++) {
                if ($request->qty[$i] != 0) {
                    $updatedQty .= $request->qty[$i].'*';
                    $updatedSize .= $request->size[$i].'*';
                    $updatedPrice .= $request->price[$i].'*';
                }
            }

            if ($updatedQty) {
                $updatedQty = substr($updatedQty, 0, -1);
                $updatedSize = substr($updatedSize, 0, -1);
                $updatedPrice = substr($updatedPrice, 0, -1);
                // dd($updatedQty, $updatedSize, $updatedPrice);

                $params = array(
                    'user_id' => $request->user_id,
                    'device_id' => $request->device_id ?? null,
                    'product_id' => $request->product_id,
                    'product_name' => $request->product_name,
                    'product_style_no' => $request->product_style_no,
                    'product_slug' => $request->product_slug,
                    'color' => $request->color,
                    'qty' => $updatedQty,
                    'size' => $updatedSize,
                    'price' => $updatedPrice,
                );
                $data = $this->cartRepository->bulkAddCartDistributor($params);

                if ($data) {
                    // return response()->json(['error' => false, 'resp' => 'Product successfully added to cart'], Response::HTTP_CREATED);
                    return redirect()->back()->with('success', 'Product added to cart');
                } else {
                    // return response()->json(['error' => true, 'resp' => 'Something happened'], Response::HTTP_CREATED);
                    return redirect()->back()->with('failure', 'Something happened');
                }
            } else {
                return redirect()->back()->with('failure', 'Please enter quantity');
            }
        } else {
            // return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
            return redirect()->back()->with('failure', $validator->errors()->first());
        }
    }

    public function viewByIp(Request $request)
    {
        $data = $this->cartRepository->viewByIp();

        if ($data) {
            return view('front.cart.index', compact('data'));
        } else {
            return view('front.404');
        }
    }

    public function viewByUserId(Request $request)
    {
        $data = $this->cartRepository->viewByUserId();

        if ($data) {
            return view('front.cart.index', compact('data'));
        } else {
            return view('front.404');
        }
    }

    public function delete(Request $request, $id)
    {
        $data = $this->cartRepository->delete($id);

        if ($data) {
            return redirect()->route('front.cart.index')->with('success', 'Product removed from cart');
        } else {
            return redirect()->route('front.cart.index')->with('failure', 'Something happened');
        }
    }

    public function deleteDistributor(Request $request, $id)
    {
        $data = $this->cartRepository->deleteDistributor($id);

        if ($data) {
            return redirect()->route('front.cart.index')->with('success', 'Product removed from cart');
        } else {
            return redirect()->route('front.cart.index')->with('failure', 'Something happened');
        }
    }

    public function qtyUpdate(Request $request, $id, $type)
    {
        $data = $this->cartRepository->qtyUpdate($id, $type);

        if ($data) {
            return redirect()->route('front.cart.index')->with('success', $data);
        } else {
            return redirect()->route('front.cart.index')->with('failure', 'Something happened');
        }
    }
}
