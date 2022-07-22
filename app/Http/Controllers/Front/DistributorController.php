<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\CategoryInterface;
use App\Models\Collection;
use App\Models\Store;
use App\Models\OrderProduct;
use Illuminate\Support\Facades\DB;
// use Illuminate\Http\Response;

class DistributorController extends Controller
{
    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function order(Request $request)
    {
        $category = $this->categoryRepository->getAllCategories();
        $collections = Collection::where('status', 1)->orderBy('position')->get();
        return view('front.order.place', compact('category', 'collections'));
    }

    public function ordersFromStore(Request $request)
    {
        $userName = auth()->guard('web')->user()->name;

        if (isset($request->collection)) {
            if ($request->collection == 'all') {
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

            if(!empty($request->style_no)) {
                $styleNoQuery = " AND p.style_no LIKE '%".$request->style_no."%'";
            } else {
                $styleNoQuery = "";
            }

            $products = DB::select("SELECT op.product_name, op.product_id, p.style_no, SUM(op.qty) AS product_count FROM `order_products` op
            INNER JOIN products p ON p.id = op.product_id
            INNER JOIN orders o ON o.id = op.order_id
            INNER JOIN stores s ON s.id = o.store_id
            WHERE s.bussiness_name LIKE '%".$userName."%' 
            AND (DATE(op.created_at) BETWEEN '".$request->from."' AND '".date('Y-m-d', strtotime($request->to.'+ 1 day'))."') 
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

        $collections = Collection::where('status', 1)->orderBy('position')->get();
        $stores = Store::where('bussiness_name', 'LIKE', '%'.$userName.'%')->get();

        return view('front.distributor.store-orders', compact('collections', 'products', 'stores', 'request'));
    }

    public function productsOrdersList(Request $request)
    {
        $request->validate([
            'productId' => 'required|integer|min:1'
        ]);

        $userName = auth()->guard('web')->user()->name;
        $data = OrderProduct::select('order_products.created_at', 'order_products.qty', 'order_products.color', 'order_products.size', 'order_products.product_name', 'products.style_no')
                ->with('colorDetails')
                ->join('products', 'products.id', 'order_products.product_id')
                ->join('orders', 'orders.id', 'order_products.order_id')
                ->join('stores', 'stores.id', 'orders.store_id')
                ->where('stores.bussiness_name', 'LIKE', '%'.$userName.'%')
                ->where('product_id', $request->productId)
                ->orderBy('order_products.id', 'desc')
                ->get();

        // dd($data);

        if ($data) {
            return response()->json([
                'status' => 200,
                'message' => 'Product orders found',
                'data' => $data,
            ]);
        } else {
            return response()->json([
                'status' => 400,
                'message' => 'No product orders found',
            ]);
        }
    }
}
