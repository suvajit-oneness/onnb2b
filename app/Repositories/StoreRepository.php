<?php

namespace App\Repositories;

use App\Interfaces\StoreInterface;
use App\Models\Store;
use App\Traits\UploadAble;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Models\NoOrderReason;
use App\Models\RetailerListOfOcc;
use App\UserNoorderreason;
use Illuminate\Support\Str;
class StoreRepository implements StoreInterface
{
    use UploadAble;
    /**
     * This method is for show store list
     *
     */
    public function listAll()
    {
        return Store::all();
    }

    /**
     * this method is use for get all store in admin section
     *
     */
    public function listAllStore()
    {
        return Store::latest('id','desc')->paginate(30);
    }
    /**
     * This method is to get active store details only
     *
     */
    public function viewStoreInFrontend()
    {
        return Store::where('status', 1)->orderBy('store_name')->paginate(30);
    }

     /**
     * This method is for show user list
     *
     */
    public function listUsers()
    {
        return User::all();
    }

    /**
     * This method is for show store details
     * @param  $id
     *
     */
    public function listById($id)
    {
       return Store::where('id',$id)->with('user', 'ProductDetails')->first();
    }

    /**
     * This method is for show store details
     * @param  $id
     *
     */
    public function listBySlug($slug)
    {
       return Store::where('slug',$slug)->with('user', 'ProductDetails')->first();
    }

    /**
     * This method is for store delete
     * @param  $id
     *
     */
    public function delete($id)
    {
        Store::destroy($id);
    }

    /**
     * This method is for store create
     * @param array $data
     * return in array format
     */
    public function create(array $data)
    {
         $collection = collect($data);
        $user_id = $collection['user_id'];
        $result = DB::select("select * from users where id='".$user_id."'");
        $item=$result[0];
        $name = $item->name;
        $result1 = DB::select("select * from retailer_list_of_occ where ase='$name'");
        $store = new Store;
        $store->user_id = $collection['user_id'];
         $store->store_name = $collection['store_name'];
        $store->bussiness_name	 = $collection['bussiness_name'];
		$store->owner_name	 = $collection['owner_name'];
        $store->store_OCC_number = $collection['store_OCC_number'];
        $store->gst_no = $collection['gst_no'] ?? null;
        $store->contact = $collection['contact'];
        $store->whatsapp = $collection['whatsapp'];
        $store->email	 = $collection['email'];
        $store->address	 = $collection['address'];
        $store->state	 = $collection['state'];
        $store->city	 = $collection['city'];
        $store->pin	 = $collection['pin'];
        $store->area	 = $collection['area'];
		$store->status = '0';
		$store->gst_no = '';
        if (!empty($collection['image'])) {
        	/* $upload_path = "uploads/store/";
			$image = $collection['image'];
			$imageName = time().".".$image->getClientOriginalName();
			$image->move($upload_path, $imageName);
			$uploadedImage = $imageName; */
        	$store->image= $collection['image'];
        }
        // if (!empty($collection['slug'])) {
            $slug = Str::slug($collection['store_name'], '-');
            $slugExistCount = Store::where('slug', $slug)->count();
            if ($slugExistCount > 0) $slug = $slug.'-'.($slugExistCount+1);
            $store->slug = $slug;
        // }
        $store->save();

        $vp = $result1[0]->vp;
        $state = $result1[0]->state;
        $vp = $result1[0]->vp;
        $vp = $result1[0]->vp;

        $retailerListOfOcc = new RetailerListOfOcc;
        $retailerListOfOcc->vp = $result1[0]->vp;
        $retailerListOfOcc->state = $result1[0]->state;
        // $retailerListOfOcc->distributor_name = $result1[0]->distributor_name;
        $retailerListOfOcc->distributor_name = $collection['distributor_name'];
        $retailerListOfOcc->area = $result1[0]->area;
        $retailerListOfOcc->retailer = $collection['store_name'];
        $retailerListOfOcc->rsm = $result1[0]->rsm;
        $retailerListOfOcc->asm = $result1[0]->asm;
        $retailerListOfOcc->ase = $result1[0]->ase;
        $retailerListOfOcc->is_active = '1';
        $retailerListOfOcc->is_deleted = '0';
        $retailerListOfOcc->asm_rsm = $result1[0]->rsm;
        $retailerListOfOcc->code = '';
        $retailerListOfOcc->save();

        return $store;




    }
    /**
     * This method is for store update
     * @param array $newDetails
     * return in array format
     */
    public function update($id, array $newDetails)
    {
        $upload_path = "uploads/store/";
        $store = Store::findOrFail($id);
        $collection = collect($newDetails);
        if (!empty($collection['user_id'])) {
            $store->user_id = $collection['user_id'];
        }
        // dd($newDetails);

        (!empty($newDetails['store_name'])) ? $store->store_name = $collection['store_name'] : '';
        (!empty($newDetails['bussiness_name'])) ? $store->bussiness_name = $collection['bussiness_name'] : '';
        (!empty($newDetails['store_OCC_number'])) ? $store->store_OCC_number = $collection['store_OCC_number'] : '';
        (!empty($newDetails['contact'])) ? $store->contact = $collection['contact'] : '';
        (!empty($newDetails['email'])) ? $store->email = $collection['email'] : '';
        (!empty($newDetails['address'])) ? $store->address = $collection['address'] : '';
        (!empty($newDetails['state'])) ? $store->state = $collection['state'] : '';
        (!empty($newDetails['city'])) ? $store->city = $collection['city'] : '';
        (!empty($newDetails['pin'])) ? $store->pin = $collection['pin'] : '';
        (!empty($newDetails['area'])) ? $store->area = $collection['area'] : '';
        if (isset($newDetails['image'])) {
            $image = $collection['image'];
            $imageName = time().".".mt_rand().".".$image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $store->image = $upload_path.$uploadedImage;
        }
        $store->save();
        return $store;
    }
    /**
     * This method is for  update store status
     * @param  $id
     *
     */
    public function toggle($id){
        $store = Store::findOrFail($id);
        $status = ( $store->status == 1 ) ? 0 : 1;
        $store->status = $status;
        $store->save();
        return $store;
    }


    /**
     * This method is to update  store details through API
     * @param str $id
     */
    public function storeupdate($id, array $newDetails)
    {
       // return Store::find($storeId)->update($newDetails);
        try {
            $data = Store::whereId($id)->update($newDetails);

            // if ($data) {
                $resp = ['error' => false, 'message' => 'Data updated successfully'];
            // } else {
            //     $resp = ['error' => true, 'message' => 'Something happened'];
            // }
        } catch (\Throwable $th) {
            $resp = ['error' => true, 'message' => $th];
        }
        return $resp;
    }



    /**
     * This method is to submit no order reason
     *
     *
     */
    public function noorderreasonupdate(array $data)
    {
       // return Store::find($storeId)->update($newDetails);
       $collection = collect($data);
       $store = new UserNoorderreason();
       $store->user_id = $collection['user_id'];
        $store->store_id = $collection['store_id'];
       $store->comment	 = $collection['comment'];
       $store->location = $collection['location'];
       $store->lat = $collection['lat'];
       $store->lng = $collection['lng'];
       $store->date	 = $collection['date'];
       $store->time	 = $collection['time'];

       $store->save();
       return $store;
    }

    /**
     * This method is to list no order reason
     *
     *
     */
    public function noorderlistAll()
    {
        return NoOrderReason::all();

    }
    public function getSearchStore(string $term)
    {
        return Store::where([['store_name', 'LIKE', '%' . $term . '%']])
        ->orWhere('bussiness_name', 'LIKE', '%' . $term . '%')
        ->orWhere('email', 'LIKE', '%' . $term . '%')
        ->orWhere('contact', 'LIKE', '%' . $term . '%')
        ->paginate(5);

    }
	
	
	public function getSearchNoorder(string $term)
    {
        return UserNoorderreason::where([['user_id', 'LIKE', '%' . $term . '%']])
        ->orWhere('store_id', 'LIKE', '%' . $term . '%')
        ->orWhere('comment', 'LIKE', '%' . $term . '%')
        ->orWhere('location', 'LIKE', '%' . $term . '%')
        ->paginate(5);

    }

}
