<?php

namespace App\Repositories;

use App\Interfaces\DistributorInterface;
use App\User;
use App\Models\Distributor;
use App\Models\Order;
use App\Models\DirectoryMom;
use App\Activity;
use App\Models\OrderDistributor;
use App\Models\RetailerListOfOcc;
use App\StoreVisit;
use App\UserAttendance;
use App\StartEndDay;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DistributorRepository implements DistributorInterface
{
    /**
     * This method is for show user list
     *
     */
    public function listAll()
    {
        return User::all();
    }


    public function listAllDistributor(){
       //dd($user_type);
        return DB::table('retailer_list_of_occ')->select('id','distributor_name','retailer','state','ase','asm','rsm','vp','area','asm_rsm','is_active')
        ->groupBy('distributor_name')
        ->paginate(5);
    }

    public function listAllRetailer()

    {
        return DB::table('retailer_list_of_occ')->select('id','distributor_name','retailer','state','ase','asm','rsm','vp','asm_rsm','is_active')
        ->groupBy('retailer')
        ->paginate(5);
    }
    /**
     * This method is for show user details
     * @param  $id
     *
     */
    public function listById($id)
    {
        // return User::where('id', $id)->get();
        return User::find($id);
    }


    public function getDistributorById($id,$user_type)
   {
    return User::where('id',$id)->where('user_type',$user_type)->get();
   }

    /**
     * This method is for show retailer details
     * @param  $id
     *
     */
    public function getRetailerById($id)
    {
        return User::find($id);
    }
    /**
     * This method is for create user
     *
     */
    public function create(array $data)
    {
        $collectedData = collect($data);
        $newEntry = new User;
        $newEntry->title = $collectedData['title'];
        $newEntry->fname = $collectedData['fname'];
        $newEntry->lname = $collectedData['lname'];
        $newEntry->email = $collectedData['email'];
        $newEntry->mobile = $collectedData['mobile'];
        $newEntry->whatsapp_no = $collectedData['whatsapp_no'];
        $newEntry->dob = $collectedData['dob'];
        $newEntry->gender = $collectedData['gender'];
        $newEntry->employee_id = $collectedData['employee_id'];
        $newEntry->user_type = $collectedData['user_type'];
        $newEntry->address = $collectedData['address'];
        $newEntry->landmark = $collectedData['landmark'];
        $newEntry->state = $collectedData['state'];
        $newEntry->city = $collectedData['city'];
        $newEntry->pin = $collectedData['pin'];
        $newEntry->aadhar_no = $collectedData['aadhar_no'];
        $newEntry->pan_no = $collectedData['pan_no'];
        $newEntry->password = Hash::make($collectedData['password']);

        $upload_path = "uploads/user/";
        $image = $collectedData['image'];
        $imageName = time() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $newEntry->image = $upload_path . $uploadedImage;

        $newEntry->save();

        return $newEntry;
    }
    /**
     * This method is for user update
     *
     *
     */
    public function update($id, array $newDetails)
    {
        $upload_path = "uploads/user/";
        $updatedEntry = User::findOrFail($id);
        $collectedData = collect($newDetails);
        $updatedEntry->title = $collectedData['title'];
        $updatedEntry->fname = $collectedData['fname'];
        $updatedEntry->lname = $collectedData['lname'];
        $updatedEntry->mobile = $collectedData['mobile'];
        $updatedEntry->dob = $collectedData['dob'];
        $updatedEntry->whatsapp_no = $collectedData['whatsapp_no'];
        $updatedEntry->employee_id = $collectedData['employee_id'];
        $updatedEntry->user_type = $collectedData['user_type'];
        $updatedEntry->address = $collectedData['address'];
        $updatedEntry->landmark = $collectedData['landmark'];
        $updatedEntry->state = $collectedData['state'];
        $updatedEntry->city = $collectedData['city'];
        $updatedEntry->pin = $collectedData['pin'];
        $updatedEntry->aadhar_no = $collectedData['aadhar_no'];
        $updatedEntry->pan_no = $collectedData['pan_no'];

        if (!empty($collectedData['gender'])) {
            $updatedEntry->gender = $collectedData['gender'];
        }
        if (!empty($collectedData['user_type'])) {
            $updatedEntry->user_type = $collectedData['user_type'];
        }
        if (isset($newDetails['image'])) {
            // dd('here');
            $image = $collectedData['image'];
            $imageName = time() . "." . mt_rand() . "." . $image->getClientOriginalName();
            $image->move($upload_path, $imageName);
            $uploadedImage = $imageName;
            $updatedEntry->image = $upload_path . $uploadedImage;
        }
        $updatedEntry->save();

        return $updatedEntry;
    }
    /**
     * This method is for update user status
     * @param  $id
     *
     */
    public function toggle($id)
    {
        $updatedEntry = User::findOrFail($id);

        $status = ($updatedEntry->status == 1) ? 0 : 1;
        $updatedEntry->status = $status;
        $updatedEntry->save();

        return $updatedEntry;
    }
    /**
     * This method is for update user verification
     * @param  $id
     *
     */
    public function verification($id)
    {
        $updatedEntry = User::findOrFail($id);

        $is_verified = ($updatedEntry->is_verified == 1) ? 0 : 1;
        $updatedEntry->is_verified = $is_verified;
        $updatedEntry->save();

        return $updatedEntry;
    }
    /**
     * This method is for user delete
     * @param  $id
     *
     */
    public function delete($id)
    {
        User::destroy($id);
    }


    /**
     * This method is for search Supplier/customer list
     *
     */
    public function customSearch(String $term)
    {
        return RetailerListOfOcc::where([['vp', 'LIKE', '%' . $term . '%']])
        ->orWhere('rsm', 'LIKE', '%' . $term . '%')
        ->orWhere('asm', 'LIKE', '%' . $term . '%')
        ->orWhere('ase', 'LIKE', '%' . $term . '%')
        ->orWhere('distributor_name', 'LIKE', '%' . $term . '%')
        ->orWhere('retailer', 'LIKE', '%' . $term . '%')
        ->orWhere('state', 'LIKE', '%' . $term . '%')
        ->orWhere('area', 'LIKE', '%' . $term . '%')
        ->paginate(5);
    }

//filter order in admin panel
        public function searchOrderlist(string $term)
        {
            return OrderDistributor::where([['order_no', 'LIKE', '%' . $term . '%']])
            ->orWhere('fname', 'LIKE', '%' . $term . '%')
            ->orWhere('lname', 'LIKE', '%' . $term . '%')
            ->orWhere('email', 'LIKE', '%' . $term . '%')
            ->orWhere('created_at', 'LIKE', '%' . $term . '%')
            ->orWhere('final_amount', 'LIKE', '%' . $term . '%')
            ->paginate(5);
        }
    public function getSearchDirectorymom(string $term)
    {
        return DirectoryMom::where([['distributor_name', 'LIKE', '%' . $term . '%']])
        ->orWhere('comment', 'LIKE', '%' . $term . '%')

        ->paginate(5);
    }





}
