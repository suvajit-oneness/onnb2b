<?php

namespace App\Repositories;

use App\Interfaces\OrderInterface;
use App\Models\Order;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\OrderProduct;
use App\Models\CartDistributor;
use App\Models\OrderDistributor;
use App\Models\OrderProductDistributor;
use App\StoreVisit;
class OrderRepository implements OrderInterface
{
    use UploadAble;

    public function __construct() {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }
    public function listAll()
    {
        return Order::orderBy('id', 'desc')->latest('id')->get();
    }
//for admin
    public function listAllOrder($cat=5)
    {
        return Order::orderBy('id', 'desc')->latest('id')->paginate($cat);
    }
    public function listbyDistributor()
    {
        return OrderDistributor::orderBy('id', 'desc')->latest('id')->get();
    }
    //admin
    public function listbyDistributorOrder($cat=5)
    {
        return OrderDistributor::orderBy('id', 'desc')->latest('id')->paginate($cat);
    }
    public function listById($id)
    {
        return Order::findOrFail($id);
    }
    public function listByIdForDistributor($id)
    {
        return OrderDistributor::findOrFail($id);
    }
     public function listByuserIdForDistributor($userid)
    {
        return OrderDistributor::where('user_id',$userid)->with('users','orderProducts')->get();
    }
    public function listByuserId($userId)
    {
        return Order::orderBy('id', 'desc')->where('user_id',$userId)->with('users','stores','orderProducts','colorDetails')->get();
    }
    public function listByStatus($status)
    {
        return Order::latest('id')->where('status', $status)->get();
    }
    public function searchOrder(string $term)
    {
        return Order::where([['fname', 'LIKE', '%' . $term . '%']])->get();
    }
    public function create(array $data)
    {
        DB::beginTransaction();

        try {
            $collectedData = collect($data);
            $newEntry = new Order;
            $newEntry->ip = $collectedData['ip'];

            $newEntry->user_id = $collectedData['user_id'];
            $newEntry->fname = $collectedData['fname'];
            $newEntry->lname = $collectedData['lname'];
            $newEntry->email = $collectedData['email'];
            $newEntry->mobile = $collectedData['mobile'];
            $newEntry->alt_mobile = $collectedData['alt_mobile'];

            $newEntry->billing_address_id = $collectedData['billing_address_id'];
            $newEntry->billing_address = $collectedData['billing_address'];
            $newEntry->billing_landmark = $collectedData['billing_landmark'];
            $newEntry->billing_country = $collectedData['billing_country'];
            $newEntry->billing_state = $collectedData['billing_state'];
            $newEntry->billing_city = $collectedData['billing_city'];
            $newEntry->billing_pin = $collectedData['billing_pin'];

            $newEntry->shipping_address_id = $collectedData['shipping_address_id'];
            $newEntry->shipping_address = $collectedData['shipping_address'];
            $newEntry->shipping_landmark = $collectedData['shipping_landmark'];
            $newEntry->shipping_country = $collectedData['shipping_country'];
            $newEntry->shipping_state = $collectedData['shipping_state'];
            $newEntry->shipping_city = $collectedData['shipping_city'];
            $newEntry->shipping_pin = $collectedData['shipping_pin'];

            $newEntry->amount = $collectedData['amount'];
            $newEntry->tax_amount = $collectedData['tax_amount'];
            $newEntry->discount_amount = $collectedData['discount_amount'];
            $newEntry->coupon_code_id = $collectedData['coupon_code_id'];
            $newEntry->final_amount = $collectedData['final_amount'];
            $newEntry->gst_no = $collectedData['gst_no'];

            DB::commit();
            return $newEntry;
        } catch (\Throwable $th) {
            // throw $th;
            DB::rollback();
        }
    }
    public function update($id, array $newDetails)
    {
        DB::beginTransaction();

        try {
            $updatedEntry = Order::findOrFail($id);
            $collectedData = collect($newDetails);

            $updatedEntry->user_id = $collectedData['user_id'];
            $updatedEntry->fname = $collectedData['fname'];
            $updatedEntry->lname = $collectedData['lname'];
            $updatedEntry->email = $collectedData['email'];
            $updatedEntry->mobile = $collectedData['mobile'];
            $updatedEntry->alt_mobile = $collectedData['alt_mobile'];

            $updatedEntry->billing_address_id = $collectedData['billing_address_id'];
            $updatedEntry->billing_address = $collectedData['billing_address'];
            $updatedEntry->billing_landmark = $collectedData['billing_landmark'];
            $updatedEntry->billing_country = $collectedData['billing_country'];
            $updatedEntry->billing_state = $collectedData['billing_state'];
            $updatedEntry->billing_city = $collectedData['billing_city'];
            $updatedEntry->billing_pin = $collectedData['billing_pin'];

            $updatedEntry->shipping_address_id = $collectedData['shipping_address_id'];
            $updatedEntry->shipping_address = $collectedData['shipping_address'];
            $updatedEntry->shipping_landmark = $collectedData['shipping_landmark'];
            $updatedEntry->shipping_country = $collectedData['shipping_country'];
            $updatedEntry->shipping_state = $collectedData['shipping_state'];
            $updatedEntry->shipping_city = $collectedData['shipping_city'];
            $updatedEntry->shipping_pin = $collectedData['shipping_pin'];

            $updatedEntry->amount = $collectedData['amount'];
            $updatedEntry->tax_amount = $collectedData['tax_amount'];
            $updatedEntry->discount_amount = $collectedData['discount_amount'];
            $updatedEntry->coupon_code_id = $collectedData['coupon_code_id'];
            $updatedEntry->final_amount = $collectedData['final_amount'];
            $updatedEntry->gst_no = $collectedData['gst_no'];

            DB::commit();
            return $updatedEntry;
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollback();
        }
    }
    public function toggle($id, $status)
    {
        $updatedEntry = Order::findOrFail($id);
        $updatedEntry->status = $status;
        $updatedEntry->save();

        return $updatedEntry;
    }
   /**
    * this method is use for update distributor order status
    */

    public function distributorordertoggle($id, $status)
    {
        $updatedEntry = OrderDistributor::findOrFail($id);
        $updatedEntry->status = $status;
        $updatedEntry->save();

        return $updatedEntry;
    }
    // public function delete($id)
    // {
    //     Order::destroy($id);
    // }

    public function listByorderId($id)
    {
       return Order::orderBy('id', 'desc')->where('id',$id)->with('orderProducts','users','colorDetails','stores')->get();
    }

    public function storeFilter($id)
    {
       return Order::orderBy('id', 'desc')->where('store_id',$id)->with('orderProducts','users','colorDetails','stores')->get();
    }

    public function placeOrder(array $data){
        $collectedData = collect($data);

        DB::beginTransaction();

        try {
            // 1 order
            $order_no = "ONN".mt_rand();
            $newEntry = new Order;
            $newEntry->order_no = $order_no;
            $newEntry->user_id = $collectedData['user_id'];
            $newEntry->ip = $collectedData['ip'];
            $newEntry->email = $collectedData['email'];
            $newEntry->mobile = $collectedData['mobile'];
            $newEntry->fname = $collectedData['fname'];
            $newEntry->lname = $collectedData['lname'];
            $newEntry->billing_country = $collectedData['billing_country'];
            $newEntry->billing_address = $collectedData['billing_address'];
            $newEntry->billing_landmark = $collectedData['billing_landmark'];
            $newEntry->billing_city = $collectedData['billing_city'];
            $newEntry->billing_state = $collectedData['billing_state'];
            $newEntry->billing_pin = $collectedData['billing_pin'];

            // shipping & billing address check
            $shippingSameAsBilling = $collectedData['shippingSameAsBilling'] ?? 0;
            $newEntry->shippingSameAsBilling = $shippingSameAsBilling;

            // dd($shippingSameAsBilling);

            if ($shippingSameAsBilling == 0) {
                $newEntry->shipping_country = $collectedData['shipping_country'];
                $newEntry->shipping_address = $collectedData['shipping_address'];
                $newEntry->shipping_landmark = $collectedData['shipping_landmark'];
                $newEntry->shipping_city = $collectedData['shipping_city'];
                $newEntry->shipping_state = $collectedData['shipping_state'];
                $newEntry->shipping_pin = $collectedData['shipping_pin'];
            } else {
                $newEntry->shipping_country = $collectedData['billing_country'];
                $newEntry->shipping_address = $collectedData['billing_address'];
                $newEntry->shipping_landmark = $collectedData['billing_landmark'];
                $newEntry->shipping_city = $collectedData['billing_city'];
                $newEntry->shipping_state = $collectedData['billing_state'];
                $newEntry->shipping_pin = $collectedData['billing_pin'];
            }

            $newEntry->shipping_method = $collectedData['shipping_method'];

            // fetch cart details
            $cartData = Cart::where('user_id', $newEntry->user_id)->get();
            $subtotal = 0;
            foreach($cartData as $cartValue) {
                $subtotal += $cartValue->offer_price * $cartValue->qty;
            }
            $newEntry->amount = $subtotal;
            $newEntry->shipping_charges = $collectedData['shipping_charges'];
            $newEntry->tax_amount = $collectedData['tax_amount'];
            // $newEntry->discount_amount = 0;
            // $newEntry->coupon_code_id = 0;
            $total = (int) $subtotal +$newEntry->shipping_charges + $newEntry->tax_amount ;
            $newEntry->final_amount = $total;
            // $coupon_code_id = $cartData[0]->coupon_code_id ?? 0;
            // $newEntry->coupon_code_id = $coupon_code_id;
            $newEntry->save();

            // 2 insert cart data into order products
            $orderProducts = [];
            foreach($cartData as $cartValue) {
                $orderProducts[] = [
                    'order_id' => $newEntry->id,
                    'product_id' => $cartValue->product_id,
                    'product_name' => $cartValue->product_name,
                    'product_image' => $cartValue->product_image,
                    'product_slug' => $cartValue->product_slug,
                    'product_variation_id' => $cartValue->product_variation_id,
                    'price' => $cartValue->price,
                    'offer_price' => $cartValue->offer_price,
                    'qty' => $cartValue->qty,
                ];
            }
            $orderProductsNewEntry = OrderProduct::insert($orderProducts);

            DB::commit();
            return $order_no;
        } catch (\Throwable $th) {
            throw $th;
            // dd($th);
            DB::rollback();
            return false;
        }
    }

    public function placeOrderUpdated(array $data){
        $collectedData = collect($data);
        DB::beginTransaction();

        try {
            // 1 order
            $order_no = "ONN".mt_rand();
            $newEntry = new Order;
            $newEntry->order_no = $order_no;
            $newEntry->user_id = $collectedData['user_id'];
            $newEntry->ip = $this->ip;
            $newEntry->store_id = $collectedData['store_id'];
            $newEntry->order_type = $collectedData['order_type'] ?? null;
            $newEntry->order_lat = $collectedData['order_lat'] ?? null;
            $newEntry->order_lng = $collectedData['order_lng'] ?? null;
            $newEntry->email = $collectedData['email'] ?? null;
            $newEntry->mobile = $collectedData['mobile'] ?? null;
            $newEntry->fname = $collectedData['fname'] ?? null;
            $newEntry->lname = $collectedData['lname'] ?? null;
            $newEntry->billing_country = $collectedData['billing_country'] ?? null;
            $newEntry->billing_address = $collectedData['billing_address'] ?? null;
            $newEntry->billing_landmark = $collectedData['billing_landmark'] ?? null;
            $newEntry->billing_city = $collectedData['billing_city'] ?? null;
            $newEntry->billing_state = $collectedData['billing_state'] ?? null;
            $newEntry->billing_pin = $collectedData['billing_pin'] ?? null;

            // shipping & billing address check
            $shippingSameAsBilling = $collectedData['shippingSameAsBilling'] ?? 0;
            $newEntry->shippingSameAsBilling = $shippingSameAsBilling;
            if ($shippingSameAsBilling == 0) {
                $newEntry->shipping_country = $collectedData['shipping_country'] ?? null;
                $newEntry->shipping_address = $collectedData['shipping_address'] ?? null;
                $newEntry->shipping_landmark = $collectedData['shipping_landmark'] ?? null;
                $newEntry->shipping_city = $collectedData['shipping_city'] ?? null;
                $newEntry->shipping_state = $collectedData['shipping_state'] ?? null;
                $newEntry->shipping_pin = $collectedData['shipping_pin'] ?? null;
            } else {
                $newEntry->shipping_country = $collectedData['billing_country'] ?? null;
                $newEntry->shipping_address = $collectedData['billing_address'] ?? null;
                $newEntry->shipping_landmark = $collectedData['billing_landmark'] ?? null;
                $newEntry->shipping_city = $collectedData['billing_city'] ?? null;
                $newEntry->shipping_state = $collectedData['billing_state'] ?? null;
                $newEntry->shipping_pin = $collectedData['billing_pin'] ?? null;
            }

            $newEntry->shipping_method = $collectedData['shipping_method'] ?? null;

            // fetch cart details
            $cartData = Cart::where('user_id', $newEntry->user_id)->get();
            $subtotal = 0;
            foreach($cartData as $cartValue) {
                $subtotal += $cartValue->offer_price * $cartValue->qty;
                $store_id = $cartValue->store_id;
                $order_type = $cartValue->order_type;
            }
            $newEntry->amount = $subtotal;
            $newEntry->shipping_charges = $collectedData['shipping_charges'] ?? null;
            $newEntry->tax_amount = $collectedData['tax_amount'] ?? null;
            $newEntry->comment = $collectedData['comment'] ?? null;
            $total = (int) $subtotal +$newEntry->shipping_charges + $newEntry->tax_amount ;
            $newEntry->final_amount = $total;
            $newEntry->save();
            // 2 insert cart data into order products
            $orderProducts = [];
            foreach($cartData as $cartValue) {
                $orderProducts[] = [
                    'order_id' => $newEntry->id,
                    'product_id' => $cartValue->product_id,
                    'product_name' => $cartValue->product_name,
                    'product_image' => $cartValue->product_image,
                    'product_slug' => $cartValue->product_slug,
                    'product_variation_id' => $cartValue->product_variation_id,
                    'product_style_no' => $cartValue->product_style_no,
                    'price' => $cartValue->price,
                    'offer_price' => $cartValue->offer_price,
                    'color' => $cartValue->color,
                    'size' => $cartValue->size,
                    'qty' => $cartValue->qty,
                ];
            }
            $orderProductsNewEntry = OrderProduct::insert($orderProducts);

            Cart::where('user_id', $newEntry->user_id)->delete();

            DB::commit();
            return $order_no;
        } catch (\Throwable $th) {
            throw $th;
            // dd($th);
            DB::rollback();
            return $th;
        }
    }

    public function placeOrderUpdatedDistributor(array $data){
        $collectedData = collect($data);
        DB::beginTransaction();

        try {
            // 1 order
            $order_no = "ONN".mt_rand();
            $newEntry = new OrderDistributor;
            $newEntry->order_no = $order_no;
            $newEntry->user_id = $collectedData['user_id'];
            $newEntry->distributor_name = auth()->guard('web')->user()->name;
            $newEntry->ip = $this->ip;
            $newEntry->order_lat = $collectedData['order_lat'] ?? null;
            $newEntry->order_lng = $collectedData['order_lng'] ?? null;
            $newEntry->email = $collectedData['email'] ?? null;
            $newEntry->mobile = $collectedData['mobile'] ?? null;
            $newEntry->fname = $collectedData['fname'] ?? null;
            $newEntry->lname = $collectedData['lname'] ?? null;
            $newEntry->billing_country = $collectedData['billing_country'] ?? null;
            $newEntry->billing_address = $collectedData['billing_address'] ?? null;
            $newEntry->billing_landmark = $collectedData['billing_landmark'] ?? null;
            $newEntry->billing_city = $collectedData['billing_city'] ?? null;
            $newEntry->billing_state = $collectedData['billing_state'] ?? null;
            $newEntry->billing_pin = $collectedData['billing_pin'] ?? null;

            // shipping & billing address check
            $shippingSameAsBilling = $collectedData['shippingSameAsBilling'] ?? 0;
            $newEntry->shippingSameAsBilling = $shippingSameAsBilling;
            if ($shippingSameAsBilling == 0) {
                $newEntry->shipping_country = $collectedData['shipping_country'] ?? null;
                $newEntry->shipping_address = $collectedData['shipping_address'] ?? null;
                $newEntry->shipping_landmark = $collectedData['shipping_landmark'] ?? null;
                $newEntry->shipping_city = $collectedData['shipping_city'] ?? null;
                $newEntry->shipping_state = $collectedData['shipping_state'] ?? null;
                $newEntry->shipping_pin = $collectedData['shipping_pin'] ?? null;
            } else {
                $newEntry->shipping_country = $collectedData['billing_country'] ?? null;
                $newEntry->shipping_address = $collectedData['billing_address'] ?? null;
                $newEntry->shipping_landmark = $collectedData['billing_landmark'] ?? null;
                $newEntry->shipping_city = $collectedData['billing_city'] ?? null;
                $newEntry->shipping_state = $collectedData['billing_state'] ?? null;
                $newEntry->shipping_pin = $collectedData['billing_pin'] ?? null;
            }
            $newEntry->shipping_method = $collectedData['shipping_method'] ?? null;

            // fetch cart details
            $cartData = CartDistributor::where('user_id', $newEntry->user_id)->get();
            $subtotal = 0;
            foreach($cartData as $cartValue) {
                $subtotal += $cartValue->offer_price * $cartValue->qty;
            }
            $newEntry->amount = $subtotal;
            $newEntry->shipping_charges = $collectedData['shipping_charges'] ?? null;
            $newEntry->tax_amount = $collectedData['tax_amount'] ?? null;
            $newEntry->comment = $collectedData['comment'] ?? null;
            $total = (int) $subtotal +$newEntry->shipping_charges + $newEntry->tax_amount ;
            $newEntry->final_amount = $total;
            $newEntry->save();

            // 2 insert cart data into order products
            $orderProducts = [];
            foreach($cartData as $cartValue) {
                $orderProducts[] = [
                    'order_id' => $newEntry->id,
                    'product_id' => $cartValue->product_id,
                    'product_name' => $cartValue->product_name,
                    'product_style_no' => $cartValue->product_style_no,
                    'product_image' => $cartValue->product_image,
                    'product_slug' => $cartValue->product_slug,
                    'product_variation_id' => $cartValue->product_variation_id,
                    'price' => $cartValue->price,
                    'offer_price' => $cartValue->offer_price,
                    'color' => $cartValue->color,
                    'size' => $cartValue->size,
                    'qty' => $cartValue->qty,
                ];
            }

            $orderProductsNewEntry = OrderProductDistributor::insert($orderProducts);

            // empty cart data
            CartDistributor::where('user_id', $newEntry->user_id)->delete();

            DB::commit();
            return $order_no;
        } catch (\Throwable $th) {
            throw $th;
            // dd($th);
            DB::rollback();
            return $th;
        }
    }

    public function Totalsales($storeId){
        return Order::where('store_id', $storeId)->sum('final_amount');
    }

    public function latestsales($storeId){
        return Order::where('store_id',$storeId)->where('created_at', '>', \Carbon\Carbon::now()->startOfMonth()->subMonth(3))->latest('id', 'desc')->sum('final_amount');
    }

    public function lastOrder($storeId){
        return Order::select('final_amount')->where('store_id',$storeId)->latest('id','desc')->first();
    }

    public function avgOrder($storeId){
        $resp = Order::where('store_id',$storeId)->avg('final_amount');
        if ($resp) {
            return (int)$resp;
        } else {
            return null;
        }
    }

    public function lastVisit($storeId){
        $data = StoreVisit::where('store_id',$storeId)->where('created_at', '>', \Carbon\Carbon::now()->startOfDay())->latest('id', 'desc')->get();

        if ($data) {
            return $data;
        } else {
            return null;
        }
    }
    public function Totalamount($storeId,$productId){
        return OrderProduct::where('store_id', $storeId)->whereIn('product_id', $productId)->with('orders')->sum('final_amount');
    }

    public function TotalOrder($storeId,$productId){
        $purchases = OrderProduct::where('store_id', $storeId)->whereIn('product_id', $productId)
    ->withCount('orders')->get();
    }


    /**
     * order show by store
     */


    public function orderByStore($fname)
    {
        $data = OrderDistributor::where('fname', $fname)->latest('id')->get();
        return $data;
    }


   //filter order in admin panel
   public function searchOrderlist(string $term)
    {
        return Order::where([['order_no', 'LIKE', '%' . $term . '%']])
        ->orWhere('fname', 'LIKE', '%' . $term . '%')
        ->orWhere('lname', 'LIKE', '%' . $term . '%')
        ->orWhere('email', 'LIKE', '%' . $term . '%')
        ->orWhere('order_type', 'LIKE', '%' . $term . '%')
        ->orWhere('created_at', 'LIKE', '%' . $term . '%')
        ->orWhere('final_amount', 'LIKE', '%' . $term . '%')
        ->paginate(5);
    }


/**
 * place order for distributor api
 */

    public function OrderUpdatedDistributor(array $data){
        //dd($data);
        $collectedData = collect($data);
        $user_id = $collectedData['user_id'];
        $result = DB::select("select * from users where id='".$user_id."'");
        $item=$result[0];
        DB::beginTransaction();

        try {
            // 1 order
            $order_no = "ONN".mt_rand();
            $newEntry = new OrderDistributor;
            $newEntry->order_no = $order_no;
            $newEntry->user_id = $user_id;
            $newEntry->distributor_name =  $item->name ?? null;
            $newEntry->ip = $this->ip;
            $newEntry->order_lat = $collectedData['order_lat'] ?? null;
            $newEntry->order_lng = $collectedData['order_lng'] ?? null;
            $newEntry->email = $collectedData['email'] ?? null;
            $newEntry->mobile = $collectedData['mobile'] ?? null;
            $newEntry->fname = $collectedData['fname'] ?? null;
            $newEntry->lname = $collectedData['lname'] ?? null;
            $newEntry->billing_country = $collectedData['billing_country'] ?? null;
            $newEntry->billing_address = $collectedData['billing_address'] ?? null;
            $newEntry->billing_landmark = $collectedData['billing_landmark'] ?? null;
            $newEntry->billing_city = $collectedData['billing_city'] ?? null;
            $newEntry->billing_state = $collectedData['billing_state'] ?? null;
            $newEntry->billing_pin = $collectedData['billing_pin'] ?? null;

            // shipping & billing address check
            $shippingSameAsBilling = $collectedData['shippingSameAsBilling'] ?? 0;
            $newEntry->shippingSameAsBilling = $shippingSameAsBilling;
            if ($shippingSameAsBilling == 0) {
                $newEntry->shipping_country = $collectedData['shipping_country'] ?? null;
                $newEntry->shipping_address = $collectedData['shipping_address'] ?? null;
                $newEntry->shipping_landmark = $collectedData['shipping_landmark'] ?? null;
                $newEntry->shipping_city = $collectedData['shipping_city'] ?? null;
                $newEntry->shipping_state = $collectedData['shipping_state'] ?? null;
                $newEntry->shipping_pin = $collectedData['shipping_pin'] ?? null;
            } else {
                $newEntry->shipping_country = $collectedData['billing_country'] ?? null;
                $newEntry->shipping_address = $collectedData['billing_address'] ?? null;
                $newEntry->shipping_landmark = $collectedData['billing_landmark'] ?? null;
                $newEntry->shipping_city = $collectedData['billing_city'] ?? null;
                $newEntry->shipping_state = $collectedData['billing_state'] ?? null;
                $newEntry->shipping_pin = $collectedData['billing_pin'] ?? null;
            }
            $newEntry->shipping_method = $collectedData['shipping_method'] ?? null;

            // fetch cart details
            $cartData = CartDistributor::where('user_id', $newEntry->user_id)->get();
            $subtotal = 0;
            foreach($cartData as $cartValue) {
                $subtotal += $cartValue->offer_price * $cartValue->qty;
            }
            $newEntry->amount = $subtotal;
            $newEntry->shipping_charges = $collectedData['shipping_charges'] ?? null;
            $newEntry->tax_amount = $collectedData['tax_amount'] ?? null;
            $newEntry->comment = $collectedData['comment'] ?? null;
            $total = (int) $subtotal +$newEntry->shipping_charges + $newEntry->tax_amount ;
            $newEntry->final_amount = $total;
            $newEntry->save();

            // 2 insert cart data into order products
            $orderProducts = [];
            foreach($cartData as $cartValue) {
                $orderProducts[] = [
                    'order_id' => $newEntry->id,
                    'product_id' => $cartValue->product_id,
                    'product_name' => $cartValue->product_name,
                    'product_style_no' => $cartValue->product_style_no,
                    'product_image' => $cartValue->product_image,
                    'product_slug' => $cartValue->product_slug,
                    'product_variation_id' => $cartValue->product_variation_id,
                    'price' => $cartValue->price,
                    'offer_price' => $cartValue->offer_price,
                    'color' => $cartValue->color,
                    'size' => $cartValue->size,
                    'qty' => $cartValue->qty,
                ];
            }

            $orderProductsNewEntry = OrderProductDistributor::insert($orderProducts);

            // empty cart data
            CartDistributor::where('user_id', $newEntry->user_id)->delete();

            DB::commit();
            return $order_no;
        } catch (\Throwable $th) {
            throw $th;
            // dd($th);
            DB::rollback();
            return $th;
        }
    }
}
