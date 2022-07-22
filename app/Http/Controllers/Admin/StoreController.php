<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\StoreInterface;
use App\Models\NoOrderReason;
use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\RetailerListOfOcc;
use App\UserNoorderreason;
use App\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StoreExport;
class StoreController extends Controller
{
    public function __construct(StoreInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }
    /**
     * This method is for show store list
     *
     */
    public function index(Request $request)
    {
        if (!empty($request->term)) {
            $data = $this->storeRepository->getSearchStore($request->term);
        } else {
            $data=\DB::select('SELECT ro.retailer,ro.vp,ro.distributor_name,ro.ase,ro.asm,ro.rsm ,ro.state,s.status,s.id,s.store_name,s.email,s.contact,s.bussiness_name ,s.address,s.area,s.city FROM `retailer_list_of_occ` AS ro INNER JOIN stores AS s ON ro.retailer = s.store_name ORDER BY `id` DESC' );
            //dd($data);
            // $data = DB::table('retailer_list_of_occ')
            //     ->select('retailer','vp','distributor_name','ase','asm','rsm','state','status','id','store_name','area','bussiness_name','email')
            //     ->join('stores', 'retailer_list_of_occ.retailer', '=', 'stores.store_name')

            //     ->get();
        }
        $users = DB::table('retailer_list_of_occ')->select('distributor_name')
        ->groupBy('distributor_name')
        ->get();
        return view('admin.store.index', compact('data','users'));
    }
    public function create(Request $request)
    {

        $users = DB::table('retailer_list_of_occ')->select('distributor_name')
        ->groupBy('distributor_name')
        ->get();
        return view('admin.store.create', compact('users'));
    }
    /**
     * This method is for create store
     *
     */
    public function store(Request $request)
    {
       //dd($request->all());
        $request->validate([
            "store_name" => "required|string|max:255",
            "contact" => "required|",
            "whatsapp"=>"required",
            "email" => "nullable|string",
            "address" => "nullable|string",
            "state" => "nullable|string",
            "city" => "nullable|string",
            "pin" => "nullable|string",
            "area" => "nullable|string",
            "image" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);
        $store = new Store;
        $store->user_id = $request->user_id;
        $store->store_name = $request->store_name ?? null;
        $slug = Str::slug($request->store_name, '-');
        $slugExistCount = Store::where('store_name', $request->store_name)->count();
        if ($slugExistCount > 0) $slug = $slug.'-'.($slugExistCount);
        $store->slug = $slug;

        // $store->slug = null;
        $store->bussiness_name = $request->bussiness_name ?? null;
        $store->store_OCC_number = $request->store_OCC_number ?? null;
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
            $uploadPath = 'uploads/store';
            $request->image->move($uploadPath, $imageName);
            $store->image = $uploadPath.'/'.$imageName;
        }
        $store->save();
       // dd($store);
        // add into retailer occ table
        //$loggedInUser = Auth::guard('web')->user()->name;

        // $result1 = DB::select("select * from retailer_list_of_occ where ase = '$loggedInUser'");
        $result1 = DB::select("select * from retailer_list_of_occ where distributor_name LIKE '%$request->bussiness_name%' ORDER BY id ASC");

        $retailerListOfOcc = new RetailerListOfOcc;
        $retailerListOfOcc->vp = $result1[0]->vp;
        $retailerListOfOcc->state = $result1[0]->state;
        $retailerListOfOcc->distributor_name = $result1[0]->distributor_name;
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
    /**
     * This method is for show store details
     * @param  $id
     *
     */
    public function show(Request $request, $id)
    {
        $data = $this->storeRepository->listById($id);
        $users = $this->storeRepository->listUsers();
        return view('admin.store.detail', compact('data','users'));
    }
    /**
     * This method is for store update
     *
     *
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "store_name" => "required|string|max:255",
            "bussiness_name" => "nullable|string",
            "store_OCC_number"=> "required|string|max:255",
            "contact" => "required|",
            "whatsapp"=>"required",
            "email" => "nullable|string",
            "address" => "nullable|string",
            "state" => "nullable|string",
            "city" => "nullable|string",
            "pin" => "nullable|string",
            "area" => "nullable|string",
          //   "user_id"=>"nullable|required",
         //   "image" => "nullable|required|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);


        $params = $request->except('_token');

        $store = $this->storeRepository->update($id, $params);

        if ($store) {
            return redirect()->route('admin.store.index');
        } else {
            return redirect()->route('admin.store.create')->withInput($request->all());
        }
    }


    /**
     * This method is for update store status
     * @param  $id
     *
     */
    public function status(Request $request, $id)
    {
        $storeStat = $this->storeRepository->toggle($id);

        if ($storeStat) {
            return redirect()->route('admin.store.index');
        } else {
            return redirect()->route('admin.store.create')->withInput($request->all());
        }
    }
    /**
     * This method is for store delete
     * @param  $id
     *
     */
    public function destroy(Request $request, $id)
    {
        $this->storeRepository->delete($id);

        return redirect()->route('admin.store.index');
    }

    /**
     * This method is for show no order reason
     * @param  $id
     *
     */
    public function noorderreasonshow(Request $request)
    {
        if (!empty($request->term)) {
        $data = $this->storeRepository->getSearchNoorder($request->term);
    } else {
        //$data=UserNoorderreason::paginate(5);
			$data=UserNoorderreason::orderBy('id','desc')->get();
    }
        return view('admin.store.noorder',compact('data'));
    }



    public function export()
    {
        return Excel::download(new StoreExport, 'store.xlsx');
    }
}
