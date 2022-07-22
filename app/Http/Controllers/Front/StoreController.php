<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\StoreInterface;
use App\Interfaces\CategoryInterface;
use App\Interfaces\OrderInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Cart;
use App\Models\Store;
use App\Models\Collection;
use App\Models\UserNoorderReason;
use App\Models\RetailerListOfOcc;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Str;

class StoreController extends Controller
{
    public function __construct(StoreInterface $storeRepository, CategoryInterface $categoryRepository, OrderInterface $orderRepository)
    {
        $this->storeRepository = $storeRepository;
        $this->categoryRepository = $categoryRepository;
        $this->orderRepository = $orderRepository;
    }

    public function index(Request $request)
    {
        // $data = $this->storeRepository->listAll();
        $data = $this->storeRepository->viewStoreInFrontend();

        if ($data) {
            return view('front.store.index', compact('data'));
        } else {
            return view('front.404');
        }
    }

    public function detail(Request $request, $id)
    {
        // dd($request->all());

        $data = $this->storeRepository->listById($id);
        $category = $this->categoryRepository->getAllCategories();
        $collections = Collection::where('status', 1)->orderBy('position')->get();

        if ($data) {
            // cart check
            $cartData = Cart::where('user_id', auth()->guard('web')->user()->id)->where('store_id', '!=', $id)->get();
            if (!empty(count($cartData))) {
                return redirect()->route('front.store.cart.alert', [$id, 'type' => $request->type]);
            }

            return view('front.store.detail', compact('data', 'category', 'collections'));
        } else {
            return view('front.404');
        }
    }

    public function alert(Request $request, $id)
    {
        // die($id);
        return view('front.store.alert', compact('id'));
    }

    public function visit_store(Request $request, $id)
    {
        if ((isset($request->visit))) {
            // die('Hi');
            DB::table('carts')->where('user_id', auth()->guard('web')->user()->id)->delete();
            return redirect()->route('front.store.detail', [$id, 'type' => $request->type]);
        }
    }

    public function noOrder(Request $request)
    {
        // dd($request->all());

        $reason = new UserNoorderReason();
        $reason->store_id = $request->store_id ?? null;
        $reason->user_id = Auth::guard('web')->user()->id;
        $reason->comment = $request->comment ?? null;
        $reason->description = $request->description ?? null;
        $reason->location = $request->location ?? null;
        $reason->lat = $request->lat ?? null;
        $reason->lng = $request->lng ?? null;
        $reason->date = date('Y-m-d');
        $reason->time = date('H:i:s');
        $reason->created_at = date('Y-m-d H:i:s');
        $reason->updated_at = date('Y-m-d H:i:s');
        $reason->save();

        // $request->description == null ? $description = null : $description = $request->description;

        // DB::insert('insert into user_noorderreasons (store_id, user_id, comment, date, time, created_at, updated_at, description) values (?, ?, ?, ?, ?, ?, ?, ?)', [$request->store_id, Auth::guard('web')->user()->id, $request->comment, date('Y-m-d'), date('H:i:s'), date('Y-m-d H:i:s'), date('Y-m-d H:i:s'), $description]);

        return redirect()->back()->with('success', 'No order reason added');
    }

    public function add(Request $request)
    {
        $loggedInUserType = auth()->guard('web')->user()->user_type;
        $loggedInUserName = auth()->guard('web')->user()->name;

        if ($loggedInUserType == 4) {
            $distributor = DB::table('retailer_list_of_occ')->select('distributor_name')
            ->where('ase', 'LIKE', '%'.$loggedInUserName.'%')
            ->groupBy('distributor_name')
            ->orderBy('distributor_name')
            ->get();
        } else {
            $distributor = DB::table('retailer_list_of_occ')->select('distributor_name')
            ->groupBy('distributor_name')
            ->orderBy('distributor_name')
            ->get();
        }

        return view('front.store.create', compact('distributor'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'user_id' => 'required|integer',
            'store_name' => 'required',
            'bussiness_name' => 'required',
            'distributor_name' => 'required',
			'owner_name' =>'required',
            'gst_no' => 'nullable',
            'store_OCC_number' => 'nullable',
            'contact' => 'required|integer',
            'whatsapp' => 'nullable|integer',
            'email' => 'nullable|email',
            'address' => 'required',
            'area' => 'required',
            'state' => 'required',
            'city' => 'nullable',
            'pin' => 'required|integer|digits:6',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:10000000',
        ]);

        $data = $request->only([
            'user_id', 'store_name', 'bussiness_name', 'distributor_name', 'store_OCC_number','owner_name', 'contact', 'whatsapp', 'email', 'address', 'area', 'state', 'city', 'pin', 'image'
        ]);

        //dd($data);
        //$stores = $this->storeRepository->create($data);
        //dd($stores);

        // add into store table
        $store = new Store;
        $store->user_id = $request->user_id;
        $store->store_name = $request->store_name ?? null;
        $store->gst_no = $request->gst_no ?? null;

        $slug = Str::slug($request->store_name, '-');
        $slugExistCount = Store::where('store_name', $request->store_name)->count();
        if ($slugExistCount > 0) $slug = $slug.'-'.($slugExistCount);
        $store->slug = $slug;

        // $store->slug = null;
        $store->bussiness_name = $request->bussiness_name ?? null;
        $store->store_OCC_number = $request->store_OCC_number ?? null;
		$store->owner_name = $request->owner_name ?? null;
        $store->contact = $request->contact ?? null;
        $store->email = $request->email ?? null;
        $store->whatsapp = $request->whatsapp ?? null;
        $store->address = $request->address ?? null;
        $store->area = $request->area ?? null;
        $store->state = $request->state ?? null;
        $store->city = $request->city ?? $request->area;
        $store->pin = $request->pin ?? null;
        $store->status = 0;

        if($request->hasFile('image')) {
            $imageName = mt_rand().'.'.$request->image->extension();
            $uploadPath = 'public/uploads/store';
            $request->image->move($uploadPath, $imageName);
            $store->image = $uploadPath.'/'.$imageName;
        }

        $store->save();

        // add into retailer occ table
        $loggedInUser = Auth::guard('web')->user()->name;

        // $result1 = DB::select("select * from retailer_list_of_occ where ase = '$loggedInUser'");
        $result1 = DB::select("select * from retailer_list_of_occ where distributor_name LIKE '%$request->distributor_name%' ORDER BY id ASC");

        $retailerListOfOcc = new RetailerListOfOcc;
        $retailerListOfOcc->vp = $result1[0]->vp;
        $retailerListOfOcc->state = $result1[0]->state;
        $retailerListOfOcc->distributor_name = $request->distributor_name;
        $retailerListOfOcc->area = $result1[0]->area;
        $retailerListOfOcc->retailer = $request->store_name ?? null;
        $retailerListOfOcc->rsm = $result1[0]->rsm;
        $retailerListOfOcc->asm = $result1[0]->asm;
        $retailerListOfOcc->ase = $result1[0]->ase;
        $retailerListOfOcc->is_active = '1';
        $retailerListOfOcc->is_deleted = '0';
        $retailerListOfOcc->asm_rsm = $result1[0]->rsm;
        $retailerListOfOcc->code = '';
        $retailerListOfOcc->save();

        /* $store = new Store;
        $store = Store::create($request->all());

        $data = RetailerListOfOcc::where('distributor_name',$store->bussiness_name)->get();
        $item=$data[0];
        $item->retailer = $store['store_name']; 
        //dd($item);// or whatever you get the ID, maybe through a URL parameter.
        $item->save(); */

        return redirect()->back()->with('success', 'Store added successfully');
    }

    public function orderHistory(Request $request, $id)
    {
        $resp = $this->orderRepository->storeFilter($id);
        return view('front.store.order')->with('data', $resp);
    }

    public function storeOrders(Request $request)
    {
        $loggedInUser = Auth::guard('web')->user()->name;

        $data = DB::select('SELECT o.*, u.name AS ordered_by_username FROM stores AS s 
        INNER JOIN orders AS o ON o.store_id = s.id 
        INNER JOIN users AS u ON o.user_id = u.id 
        WHERE s.bussiness_name LIKE "%' . $loggedInUser . '%"');

        return view('front.order.store', compact('data'));
    }

    public function imageView()
    {
        $data = DB::table('retailer_store_image')->where('store_id', Auth::guard('web')->user()->id)->get();
        return view('front.retailer_image.index', compact('data'));
    }

    public function imageAdd(Request $request)
    {
        $images = [];

        foreach ($request->images as $image) {
            $img = uniqid() . '.' . strtolower($image->getClientOriginalExtension());
            array_push($images, $img);
            $image->move(public_path() . '/uploads/store_images/', $img);
        }

        $findData = DB::table('retailer_store_image')->where('store_id', Auth::guard('web')->user()->id)->get();
        if (count($findData) > 0) {
            $oldimages = explode(',', $findData[0]->images);
            $newimages = array_merge($images, $oldimages);
            $update = DB::table('retailer_store_image')->where('store_id', Auth::guard('web')->user()->id)->update([
                'images' => implode(',', $newimages),
            ]);
            if ($update)
                return redirect()->back()->with(['success' => "Images uploaded successfully!"]);
            else
                return redirect()->back()->with(['failure' => "Uploads cannot be done!"]);
        } else {
            $insert = DB::table('retailer_store_image')->insert([
                'store_id' => Auth::guard('web')->user()->id,
                'images' => implode(',', $images),
            ]);
            if ($insert)
                return redirect()->back()->with(['success' => "Images uploaded successfully!"]);
            else
                return redirect()->back()->with(['failure' => "Uploads cannot be done!"]);
        }
    }

    public function delete_image($img)
    {
        $findData = DB::table('retailer_store_image')->where('store_id', Auth::guard('web')->user()->id)->get();
        $img_arr = explode(',', $findData[0]->images);

        if (count($img_arr) == 1) {
            $update = DB::table('retailer_store_image')->where('store_id', Auth::guard('web')->user()->id)->delete();
        } else {
            $key = array_search($img, $img_arr);
            unset($img_arr[$key]);

            $new_array = implode(',', $img_arr);

            $update = DB::table('retailer_store_image')->where('store_id', Auth::guard('web')->user()->id)->update([
                'images' => $new_array,
            ]);
        }

        File::delete(public_path() . '/uploads/store_images/' . $img);

        if ($update)
            return redirect()->back()->with(['success' => "Images deleted successfully!"]);
        else
            return redirect()->back()->with(['failure' => "Deletion cannot be done!"]);
    }

    public function search(Request $request)
    {
        // dd($request->name);
        $data = Store::where('store_name', 'LIKE', '%'.$request->name.'%')->get();
        if (count($data) > 0) {
            $resp = [];
            foreach($data as $item) {
                $resp[] = [
                    'store_name' => $item->store_name,
                    'bussiness_name' => $item->bussiness_name,
                    'route' => route('front.store.detail', [$item->id, 'type' => $request->type])
                ];
            }

            return response()->json(['error' => false, 'message'=> 'Store found', 'data'  => $resp]);
        } else {
            return response()->json(['error' => true, 'message' => 'No data found']);
        }
    }
}
