<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\OrderProduct;

class ApiController extends Controller
{
    // distributor store orders
    public function distributorStoreReport(Request $request) {
        $validator = Validator::make($request->all(), [
            'distributor_id' => ['required'],
            'date_from' => ['nullable'],
            'date_to' => ['nullable'],
            'collection' => ['nullable'],
            'orderBy' => ['nullable'],
            'style_no' => ['nullable'],
        ]);

        if (!$validator->fails()) {
            $userName = User::findOrFail($request->distributor_id);
            $userName = $userName->name;

            if (isset($request->collection) || isset($request->date_from) || isset($request->date_to) || isset($request->orderBy) || isset($request->style_no)) {
                if ($request->collection == 'all' || !isset($request->collection)) {
                    $collectionQuery = "";
                } else {
                    $collectionQuery = " AND p.collection_id = ".$request->collection;
                }

                if ($request->orderBy == 'date_asc') {
                    $orderByQuery = "op.id ASC";
                } elseif ($request->orderBy == 'qty_asc') {
                    $orderByQuery = "product_count ASC";
                } elseif ($request->orderBy == 'qty_desc') {
                    $orderByQuery = "product_count DESC";
                } else {
                    $orderByQuery = "op.id DESC";
                }
                //dd($request->style_no);
                if (!isset($request->style_no)) {
                    // dd('here 1');
                    $styleNoQuery = "";
                } else {
                  //  dd('here 2');
                    $styleNoQuery = " AND p.style_no LIKE '%".$request->style_no."%'";
                }

                $products = DB::select("SELECT op.product_name, op.product_id, p.style_no, SUM(op.qty) AS product_count FROM `order_products` op
                INNER JOIN products p ON p.id = op.product_id
                INNER JOIN orders o ON o.id = op.order_id
                INNER JOIN stores s ON s.id = o.store_id
                WHERE s.bussiness_name LIKE '%".$userName."%'
                AND (DATE(op.created_at) BETWEEN '".$request->date_from."' AND '".date('Y-m-d', strtotime($request->date_to.'+ 1 day'))."')
                ".$collectionQuery."
                ".$styleNoQuery."
                GROUP BY op.product_id
                ORDER BY ".$orderByQuery);
            } else {
                $products = DB::select("SELECT op.product_name, op.product_id, p.style_no, SUM(op.qty) AS product_count FROM `order_products` op
                INNER JOIN products p ON p.id = op.product_id
                INNER JOIN orders o ON o.id = op.order_id
                INNER JOIN stores s ON s.id = o.store_id
                WHERE s.bussiness_name LIKE '%".$userName."%'
                AND (DATE(op.created_at) BETWEEN '".date('Y-m-01')."' AND '".date('Y-m-d', strtotime('+1 day'))."')
                GROUP BY op.product_id
                ORDER BY op.id DESC");
            }

            $resp = $orderDetails = [];
            foreach ($products as $product) {
                // $orderDetails = OrderProduct::select('order_products.created_at', 'order_products.qty', 'order_products.color', 'order_products.size', 'order_products.product_name', 'products.style_no')
                $orderDetails = OrderProduct::select('order_products.created_at', 'order_products.qty', 'order_products.color', 'order_products.size')
                ->with('colorDetails')
                ->join('products', 'products.id', 'order_products.product_id')
                ->join('orders', 'orders.id', 'order_products.order_id')
                ->join('stores', 'stores.id', 'orders.store_id')
                ->where('stores.bussiness_name', 'LIKE', '%'.$userName.'%')
                ->where('product_id', $product->product_id)
                ->orderBy('order_products.id', 'desc')
                ->get();

                $resp[] = [
                    'product_id' => $product->product_id,
                    'product_name' => $product->product_name,
                    'style_no' => $product->style_no,
                    'order_qty' => $product->product_count,
                    'order_details' => $orderDetails,
                ];
            }

            return response()->json([
                'error' => false,
                'message' => 'Product orders with quanity',
                'data' => $resp,
            ]);

        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
    }

    //ase team report
    public function detail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ase_id' => ['required'],
            'date_from' => ['nullable'],
            'date_to' => ['nullable'],
            'collection' => ['nullable'],
            'category' => ['nullable'],
            'orderBy' => ['nullable'],
            'style_no' => ['nullable'],
        ]);

        if (!$validator->fails()) {
            $retailers = DB::select("SELECT id, store_name FROM `stores`
            WHERE `user_id` = '".$request->ase_id."'
            ORDER BY store_name");

            $resp = [];

            foreach($retailers as $retailer) {
                if ( !empty($request->date_from) || !empty($request->date_to) ) {
                    // date from
                    if (!empty($request->date_from)) {
                        $from = $request->date_from;
                    } else {
                        $from = date('Y-m-01');
                    }

                    // date to
                    if (!empty($request->date_to)) {
                        $to = date('Y-m-d', strtotime($request->date_to. '+1 day'));
                    } else {
                        $to = date('Y-m-d', strtotime('+1 day'));
                    }

                    // collection
                    if ($request->collection == 'all' || !isset($request->collection)) {
                        $collectionQuery = "";
                    } else {
                        $collectionQuery = " AND p.collection_id = ".$request->collection;
                    }

                    // category
                    if ($request->category == 'all' || !isset($request->category)) {
                        $categoryQuery = "";
                    } else {
                        $categoryQuery = " AND p.cat_id = ".$request->category;
                    }

                    // style no
                    if (!isset($request->style_no)) {
                        $styleNoQuery = "";
                    } else {
                        $styleNoQuery = " AND p.style_no LIKE '%".$request->style_no."%'";
                    }

                    // order by
                    if ($request->orderBy == 'date_asc') {
                        $orderByQuery = "op.id ASC";
                    } elseif ($request->orderBy == 'qty_asc') {
                        $orderByQuery = "qty ASC";
                    } elseif ($request->orderBy == 'qty_desc') {
                        $orderByQuery = "qty DESC";
                    } else {
                        $orderByQuery = "op.id DESC";
                    }

                    $report = DB::select("SELECT IFNULL(SUM(op.qty), 0) AS qty FROM `orders` AS o
                    INNER JOIN order_products AS op ON op.order_id = o.id
                    INNER JOIN products p ON p.id = op.product_id
                    WHERE o.store_id = '".$retailer->id."'
                    ".$collectionQuery."
                    ".$categoryQuery."
                    ".$styleNoQuery."
                    AND (date(o.created_at) BETWEEN '".$from."' AND '".$to."')
                    ORDER BY ".$orderByQuery);
                } else {
                    $report = DB::select("SELECT IFNULL(SUM(op.qty), 0) AS qty FROM `orders` AS o INNER JOIN order_products AS op ON op.order_id = o.id WHERE o.store_id = '".$retailer->id."' AND (date(o.created_at) BETWEEN '".date('Y-m-01')."' AND '".date('Y-m-d', strtotime('+1 day'))."')");
                }

                $resp[] = [
                    'store_id' => $retailer->id,
                    'store_name' => $retailer->store_name,
                    'quantity' => $report[0]->qty
                ];
            }

            return response()->json(['error' => false, 'message' => 'ASE report - Team wise', 'data' => $resp]);
        } else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
    }

    // store create image API
	public function storeCreateImage(Request $request) {
		$validator = Validator::make($request->all(), [
            'file' => ['required', 'image', 'max:1000000'],
        ]);

        if (!$validator->fails()) {
			// if($request->file) {
				$imageName = mt_rand().'.'.$request->file->extension();
				$uploadPath = 'public/uploads/store';
				$request->file->move($uploadPath, $imageName);
				$total_path = $uploadPath.'/'.$imageName;
			// }

			return response()->json(['error' => false, 'message' => 'Image added', 'data' => $total_path]);
		} else {
            return response()->json(['error' => true, 'message' => $validator->errors()->first()]);
        }
	}
}
