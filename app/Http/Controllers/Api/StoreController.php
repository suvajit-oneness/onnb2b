<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\StoreInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use DB;
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

    public function list(Request $request): JsonResponse
    {
		$ase = $_GET['ase'];
		
		//die($ase);
		
		$stores = DB::select("select * from stores where status=1 AND  store_name in (select retailer from retailer_list_of_occ where ase like '%$ase%')");
		
		//echo "<pre>";
		//print_r($stores);
		
        //$stores = $this->storeRepository->listAll();

        if ($stores) {
            /*$storesCustom = [];
            foreach($stores as $storeKey => $storeValue) {
                $storesCustom[] = [
                    'id' => $storeValue->id,
                    'user_id' => $storeValue->user_id,
                    'store_name'=>$storeValue->store_name,
                    //'bussiness_name'=>$storeValue->bussiness_name,
                    'store_OCC_number'=>$storeValue->store_OCC_number,
                    'contact'=>$storeValue->contact,
                    'whatsapp'=>$storeValue->whatsapp,
                    'email'=>$storeValue->email,
                    'address'=>$storeValue->address,
                    'area'=>$storeValue->area,
                    'state'=>$storeValue->state,
                    'city'=>$storeValue->city,
                    'pin'=>$storeValue->pin,
                    'image' => asset($storeValue->image),
                ];
            }*/

            return response()->json(['error'=>false, 'resp'=>'Store data fetched successfully','data'=>$stores]);
        } else {
            return response()->json(['error' => true, 'resp' => 'Something happened']);
        }
    }

    /**
     * This method is for create store
     *
     */
    public function store(Request $request): JsonResponse
    {
        //dd($request->all);
        $data = $request->only([
            'user_id', 'store_name', 'distributor_name', 'bussiness_name', 'owner_name','store_OCC_number','contact','whatsapp','email','address','area','state','city','pin','gst_no', 'image'
        ]);
        //dd($data);
        $stores = $this->storeRepository->create($data);
         //dd($stores);
        return response()->json(['error'=>false, 'resp'=>'Store data created successfully','data'=>$stores]);
    }

    /**
     * This method is for show store details
     * @param  $id
     *
     */
    public function show($id): JsonResponse
    {

        $stores = $this->storeRepository->listById($id);

        return response()->json(['error'=>false, 'resp'=>'Store data fetched successfully','data'=>$stores]);
    }
    /**
     * This method is for store update
     *
     *
     */
    public function update(Request $request, int $id)
    {

        // $newDetails = $request->only([
        //     'user_id', 'store_name', 'bussiness_name', 'store_OCC_number','contact','whatsapp','email','address','area','state','city','pin','image'
        // ]);

        // return response()->json([
        //     'data' => $this->storeRepository->update($storeId, $newDetails)
        // ]);
        // $newDetails = $request->except('_token');

        $response = $this->storeRepository->update($id, $request->all());

        return response()->json(['status' => 200, 'message' => 'Data updated successfully', 'data' => $response]);
    }


    /**
     * This method is for store delete
     * @param  $id
     *
     */
    public function delete(Request $request): JsonResponse
    {
        $storeId = $request->route('id');
        $this->storeRepository->delete($storeId);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }



    /**
     * This method is to submit no order reason
     *
     *
     */
    public function noorder(Request $request)
    {
        // $newDetails = $request->only([
        //     'user_id', 'start_location', 'end_location', 'start_lat', 'end_lat', 'start_lng', 'end_lng', 'start_date', 'end_date', 'start_time', 'end_time'
        // ]);

        $data = $request->only([
            'user_id', 'store_id', 'comment', 'lat','lng','location','date','time'
        ]);
        //dd($data);
        $stores = $this->storeRepository->noorderreasonupdate($data);
         //dd($stores);
        return response()->json(['error'=>false, 'resp'=>'No order Reason data created successfully','data'=>$stores]);
    }

    /**
     * This method is to list no order reason
     *
     */

    public function noorderlist(): JsonResponse
    {

        $stores = $this->storeRepository->noorderlistAll();

        return response()->json(['error'=>false, 'resp'=>'no order list data fetched successfully','data'=>$stores]);
    }


}
