<?php

namespace App\Repositories;

use App\Interfaces\UserInterface;
use App\User;
use App\Models\Distributor;
use App\Models\Order;
use App\Activity;
use App\StoreVisit;
use App\UserAttendance;
use App\StartEndDay;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRepository implements UserInterface
{
    /**
     * This method is for show user list
     *
     */
    public function listAll()
    {
        return User::all();
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
		$newEntry->name = $collectedData['name'];
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
        if($newEntry->image){
        $upload_path = "uploads/user/";
        $image = $collectedData['image'];
        $imageName = time() . "." . $image->getClientOriginalName();
        $image->move($upload_path, $imageName);
        $uploadedImage = $imageName;
        $newEntry->image = $upload_path . $uploadedImage;
		}
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
     * show activity in admin section
     */
    public function useractivitylog()
    {
        //return Activity::all();

        return Activity::latest('id','desc')->paginate(10);
    }

    /**
     * This method is to get user activity details
     * @param str $id
     */
    public function useractivity()
    {
        //return Activity::all();
        $today = date("Y-m-d");
        return Activity::where('date', '=', $today)->get();
    }

    public function storeVisit()
    {
        return StoreVisit::latest('id', 'desc')->get();
    }


    //last 10 visit

    public function storeVisitlist($storeId)
    {
        return StoreVisit::where('store_id', $storeId)->where('created_at', '>', \Carbon\Carbon::now()->startOfDay())->latest('id', 'desc')->limit(10)->with('users')->get();
    }
    /**
     * This method is to get user activity details by Id
     * @param str $id
     */
    public function useractivitylistById($activityId)
    {

        return Activity::where('id', $activityId)->with('users')->first();
    }
    /**
     * This method is to get user activity details
     * @param str $data
     * return array
     */
    public function useractivitycreate(array $data)
    {
        return Activity::create($data);
    }
    public function storeVisitCreate(array $data)
    {
        return StoreVisit::create($data);
    }

    /**
     * This method is to update user activity details
     * @param str $id
     */
    public function useractivityupdate($id, array $newDetails)
    {
        // return Activity::find($activityId)->update($newDetails);
        try {
            $data = Activity::whereId($id)->update($newDetails);

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
     * This method is to delete user activity details
     *
     */
    public function useractivitydelete($activityId)
    {
        try {
            Activity::findOrFail($activityId)->delete();
            $resp = ['error' => false, 'message' => 'Data Deleted successfully'];
            // } else {
            //     $resp = ['error' => true, 'message' => 'Something happened'];
            // }
        } catch (\Throwable $th) {
            $resp = ['error' => true, 'message' => $th];
        }
        return $resp;
    }


    /**
     * This method is to get user attendance details
     *
     */
    public function userattendance()
    {
        return UserAttendance::all();
    }
    /**
     * This method is to get user attendance details by Id
     * @param str $id
     */
    public function userattendanceById($id)
    {
        return UserAttendance::where('id', $id)->with('users')->first();
    }
    /**
     * This method is to create user attendance details
     * @param str $data
     * return array
     */
    public function userattendancecreate(array $data)
    {
        return UserAttendance::create($data);
    }
    /**
     * This method is to update user attendance details
     * @param str $id
     */
    public function userattendanceupdate(int $id, array $newDetails)
    {


        try {
            $data = UserAttendance::whereId($id)->update($newDetails);

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
     * This method is to delete user attendance details
     *
     */
    public function userattendancedelete($id)
    {
        UserAttendance::destroy($id);
    }


    /**
     * This method is to get user start day end day details
     *
     */
    public function userday()
    {
        return StartEndDay::all();
    }
    /**
     * This method is to get user start day end day details by Id
     * @param str $id
     */
    public function userdaylistById($id)
    {
        return StartEndDay::where('id', $id)->with('users')->first();
    }
    /**
     * This method is to create user start day end day details
     * @param str $data
     * return array
     */
    public function userdaycreate(array $data)
    {
        return StartEndDay::create($data);
    }
    /**
     * This method is to update user start day end day details
     * @param str $id
     */
    public function userdayupdate(int $id, array $newDetails)
    {
        try {
            $data = StartEndDay::whereId($id)->update($newDetails);

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
     * This method is to delete user start day end day details
     *
     */
    public function userdaydelete($id)
    {
        StartEndDay::destroy($id);
    }

    /**
     * This method is to check validation of mobile no and generate otp
     * @param str $mobileNo
     *
     */

    public function otpGenerate($mobileNo)
    {
        $userExists = User::where('mobile', $mobileNo)->first();

        if ($userExists) {
            $otp = mt_rand(0, 10000);
            $userExists->otp = 1234;

            // sms gateway
            $userExists->save();

            $resp = 'OTP generated successfully';
        } else {
            $resp = 'User does not exist';
        }
        return $resp;
    }

    /**
     * This method is to check validation of otp
     * @param str $otp
     *
     */
    public function otpcheck($mobile, $otp)
    {
        $userExists = User::where('mobile', $mobile)->where('otp', $otp)->first();

        if ($userExists) return $userExists;
        else return false;
    }


    /**
     * This method is to get user  details by Id
     * @param str $id
     */
    public function userdetailsById($id)
    {
        return User::where('id', $id)->get();
    }
    public function updateuserprofile($userId, $newDetails)
    {
        try {
            $data = User::whereId($userId)->update($newDetails);

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
     * This method is to get user activity details
     * @param str $data
     * return array
     */
    public function distributorcreate(array $data)
    {
        return Distributor::create($data);
    }

    public function distributorlist()
    {
        $result = DB::select("select * from distributors where bussiness_name in (select DISTINCT distributor_name from retailer_list_of_occ where ase='Lalit Sharma')");
        //return Distributor::all();
        return $result;
    }



    public function distributorview($id)
    {
        return Distributor::where('id', $id)->get();
    }
    // public function storeVisitCreate(array $data)
    // {
    //     return StoreVisit::create($data);
    // }

    public function addressById($id)
    {
        return Address::where('user_id', $id)->get();
    }

    public function addressCreate(array $data)
    {
        $collectedData = collect($data);
        $newEntry = new Address;
        $newEntry->user_id = $collectedData['user_id'];
        $newEntry->address = $collectedData['address'];
        $newEntry->landmark = $collectedData['landmark'];
        $newEntry->lat = $collectedData['lat'];
        $newEntry->lng = $collectedData['lng'];
        $newEntry->state = $collectedData['state'];
        $newEntry->city = $collectedData['city'];
        $newEntry->pin = $collectedData['pin'];
        $newEntry->pin = $collectedData['pin'];
        $newEntry->country = $collectedData['country'];
        $newEntry->save();

        return $newEntry;
    }

    public function updateProfile(array $data)
    {
        $collectedData = collect($data);
        // dd($collectedData);
        $updateEntry = User::findOrFail(Auth::guard('web')->user()->id);
        $updateEntry->fname = $collectedData['fname'] ?? null;
        $updateEntry->lname = $collectedData['lname'] ?? null;
        // $updateEntry->name = $collectedData['name'] ?? null;
        $updateEntry->email = $collectedData['email'] ?? null;

        if (isset($collectedData['mobile'])) {
            $mobileCHk = User::select('mobile')->where('mobile', $collectedData['mobile'])->get();
            if (count($mobileCHk) == 0) {
                $updateEntry->mobile = $collectedData['mobile'] ?? null;
            }
        }

        $updateEntry->whatsapp_no = $collectedData['whatsapp_no'] ?? null;
        $updateEntry->address = $collectedData['address'] ?? null;
        $updateEntry->landmark = $collectedData['landmark'] ?? null;
        $updateEntry->state = $collectedData['state'] ?? null;
        $updateEntry->city = $collectedData['city'] ?? null;
        $updateEntry->pin = $collectedData['pin'] ?? null;
        $updateEntry->aadhar_no = $collectedData['adhar_no'] ?? null;
        $updateEntry->pan_no = $collectedData['pan_no'] ?? null;
        $updateEntry->dob = $collectedData['dob'] ?? null;
        $updateEntry->anniversary_date = $collectedData['anniversary_date'] ?? null;
        $updateEntry->gender = $collectedData['gender'] ?? null;
        $updateEntry->social_id = $collectedData['social_id'] ?? null;

        $updateEntry->save();

        return $updateEntry;
    }

    public function updatePassword(array $data)
    {
        $collectedData = collect($data);
        $userExists = User::findOrFail(Auth::guard('web')->user()->id);

        if ($userExists) {
            if (Auth::guard('web')->user()->password != "") {
                if (Hash::check($collectedData['old_password'], $userExists->password)) {
                    $userExists->password = Hash::make($collectedData['new_password']);
                    $userExists->save();
                    return true;
                } else {
                    return false;
                }
            } else {
                $userExists->password = Hash::make($collectedData['new_password']);
                $userExists->save();
                return true;
            }
        } else {
            return false;
        }
    }

    public function orderDetails()
    {
        $loggedInUserId = Auth::guard('web')->user()->id;
        $loggedInUser = Auth::guard('web')->user()->name;
        $loggedInUserEmail = Auth::guard('web')->user()->email;
        $loggedInUserType = Auth::guard('web')->user()->user_type;
        $loggedInUserState = Auth::guard('web')->user()->state;

        if ($loggedInUserType == 6) {
            // $data = Order::where('email', Auth::guard('web')->user()->email)->latest('id')->get();
            $data = DB::select('SELECT * FROM orders WHERE email = "' . $loggedInUserEmail . '" OR user_id = "' . $loggedInUserId . '" ORDER BY id DESC');
        } elseif ($loggedInUserType == 5) {
            // Distributor orders only
            $data = DB::select('SELECT * FROM orders_distributors WHERE user_id = "' . $loggedInUserId . '" ORDER BY id DESC');
        } elseif ($loggedInUserType == 4) {
            // $data = Order::where('email', Auth::guard('web')->user()->email)->latest('id')->get();
            $data = DB::select('SELECT * FROM orders WHERE email = "' . $loggedInUserEmail . '" OR user_id = "' . $loggedInUserId . '" ORDER BY id DESC');
        } elseif ($loggedInUserType == 3) {
            $data = DB::select('SELECT o.*, u.name AS ordered_by_username FROM retailer_list_of_occ AS ro INNER JOIN stores AS s ON ro.retailer = s.store_name INNER JOIN orders AS o ON s.id = o.store_id INNER JOIN users AS u ON o.user_id = u.id WHERE ro.asm LIKE "%' . $loggedInUser . '%" OR o.user_id = "' . $loggedInUserId . '" ORDER BY o.id DESC');
        } elseif ($loggedInUserType == 2) {
            $data = DB::select('SELECT o.*, u.name AS ordered_by_username FROM retailer_list_of_occ AS ro INNER JOIN stores AS s ON ro.retailer = s.store_name INNER JOIN orders AS o ON s.id = o.store_id INNER JOIN users AS u ON o.user_id = u.id WHERE ro.rsm LIKE "%' . $loggedInUser . '%" OR o.user_id = "' . $loggedInUserId . '" ORDER BY o.id DESC');
        } elseif ($loggedInUserType == 1) {
            $data = DB::select('SELECT o.*, u.name AS ordered_by_username FROM retailer_list_of_occ AS ro INNER JOIN stores AS s ON ro.retailer = s.store_name INNER JOIN orders AS o ON s.id = o.store_id INNER JOIN users AS u ON o.user_id = u.id WHERE ro.vp LIKE "%' . $loggedInUser . '%" ORDER BY o.id DESC');
        }

        return $data;
    }

    public function orderByStore($store_id)
    {
        $data = Order::where('store_id', $store_id)->latest('id')->get();
        return $data;
    }

    public function recommendedProducts()
    {
        $data = Product::latest('is_best_seller', 'id')->get();
        return $data;
    }

    public function wishlist()
    {
        $data = Wishlist::where('ip', $this->ip)->get();
        return $data;
    }

    public function couponList()
    {
        $data = Coupon::orderBy('end_date', 'desc')->get();
        return $data;
    }

    /**
     * This method is to check login
     * @param str $otp
     *
     */
    public function login($email,$state)
    {
        $userExists = User::where('email', $email)->where('state',$state)->first();

        if ($userExists) return $userExists;
        else return false;
    }
    /**
     * This method is to check login
     * @param str $otp
     *
     */
    public function mobilelogin($email, $password)
    {
        $userExists = User::where('email', $email)->where('password', $password)->first();

        if ($userExists) return $userExists;
        else return false;
    }

    public function getSearchUser($keyword,$user_type)
    {
        return User:: when($keyword, function($query) use ($keyword){
            $query->where('name', '=', $keyword);


        })

        ->when($user_type, function($query) use ($user_type){
            $query->where('user_type', '=', $user_type);
        })

        ->paginate(5);

    }


    /**
     * This method is to get activity details by filter
     * @param str $date,$time,$userId,$userType
     */
     public function getActivityByFilter($date,$time,$userId,$userType)
    {
        $target = Activity::

        /*when($date, function($query) use ($date){
            $query->where('date', '=', $date);
        })
        ->when($time!='', function($query) use ($time){
            $query->where('time', '=', $time);
        })*/
        when($userId!='', function($query) use ($userId){
            $query->where('user_id', '=', $userId);
        })

        /*->when($userType!='', function($query) use ($userType){
            $query->where('user_type', '=', $userType);
        })*/


        ->latest('id','desc')->paginate(5);

        return $target;
    }



}
