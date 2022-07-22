<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\UserInterface;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use \Illuminate\Support\Str;
use App\User;
use DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    //private UserRepository $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * This method is to get user attendance details
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {

        $activity = $this->userRepository->userattendance();

        return response()->json(['error'=>false, 'resp'=>'User Attendance data fetched successfully','data'=>$activity]);
    }
    /**
     * This method is to create user attendance details
     * @param str $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'min:1'],
            'location' => ['required', 'string'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],

        ]);

        if (!$validator->fails()) {
            // return response()->json(['status' => 200, 'message' => 'okay']);
            $data = $this->userRepository->userattendancecreate($request->all());
        } else {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }
        $params = $request->except('_token');
        return response()->json(
            [
                'data' => $this->userRepository->userattendancecreate($params)
            ],
            Response::HTTP_CREATED
        );
        // return response()->json(
        //     [
        //         'data' => $this->userRepository->userattendancecreate($data)
        //     ],
        //     Response::HTTP_CREATED
        // );
    }
    /**
     * This method is to get user attendance details by Id
     * @param str $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $id = $request->route('id');


        $activity = $this->userRepository->userattendanceById($id);

        return response()->json(['error'=>false, 'resp'=>'User Attendance data fetched successfully','data'=>$activity]);
    }
    /**
     * This method is to update user attendance details
     * @param str $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request,int $id)
    {
        // $id = $request->route('id');
        // $newDetails = $request->only([
        //     'user_id',
        //     'date',
        //     'location',
        //     'lat',
        //     'lng',
        //     'start_time',
        //     'end_time'
        // ]);


        //     $response = $this->userRepository->userattendanceupdate($id, $newDetails);
        //     return response()->json(['error'=>false, 'resp'=>$response]);
        $newDetails = $request->except('_token');

        $response = $this->userRepository->userattendanceupdate($id, $newDetails);

        return response()->json($response);

    }
    /**
     * This method is to delete user attendance details
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $id = $request->route('id');
        $this->userRepository->userattendancedelete($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }


    /**
     * This method is to get user start day end day details
     * @return \Illuminate\Http\JsonResponse
     */
    public function daylist(): JsonResponse
    {

        $activity = $this->userRepository->userday();

        return response()->json(['error'=>false, 'resp'=>'User Day data fetched successfully','data'=>$activity]);
    }
    /**
     * This method is to create user start day end day details
     * @param str $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function daystore(Request $request): JsonResponse
    {
      $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'min:1'],
            'start_location' => ['required', 'string'],
            'end_location' => ['required', 'string'],
            'start_lat' => ['required', 'numeric'],
            'end_lat' => ['required', 'numeric'],
            'start_lng' => ['required', 'numeric'],
            'end_lng' => ['required', 'numeric'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'start_time' => ['required'],
            'end_time' => ['required'],

        ]);

        if (!$validator->fails()) {
            // return response()->json(['status' => 200, 'message' => 'okay']);
            $data = $this->userRepository->userdaycreate($request->all());
        } else {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }
        $params = $request->except('_token');
        return response()->json(
            [
                'data' => $this->userRepository->userdaycreate($params)
            ],
            Response::HTTP_CREATED
        );
    }
    /**
     * This method is to get user start day end day details by Id
     * @param str $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function dayshow(Request $request): JsonResponse
    {
        $id = $request->route('id');


        $activity = $this->userRepository->userdaylistById($id);

        return response()->json(['error'=>false, 'resp'=>'User Day data fetched successfully','data'=>$activity]);
    }
    /**
     * This method is to update user start day end day details
     * @param str $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function dayupdate(Request $request,int $id)
    {
        $newDetails = $request->only([
            'user_id', 'start_location', 'end_location', 'start_lat', 'end_lat', 'start_lng', 'end_lng', 'start_date', 'end_date', 'start_time', 'end_time'
        ]);

        $newDetails = $request->except('_token');

        $response = $this->userRepository->userdayupdate($id, $newDetails);

        return response()->json($response);

        //  return response()->json([
        //     'error'=>false,
        //      'resp'=>$request->end_location
        //     // 'data' => $this->userRepository->userdayupdate($dayId),
        //     ]);
    }
    /**
     * This method is to delete user start day end day details
     * @return \Illuminate\Http\JsonResponse
     */
    public function daydelete(Request $request): JsonResponse
    {
        $id = $request->route('id');
        $this->userRepository->userdaydelete($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

     /**
     * This method is to check validation of mobile no and generate otp
     * @param str $mobileNo
     * @return \Illuminate\Http\JsonResponse
     */
    public function logincheck(Request $request) {
        $response = $this->userRepository->otpGenerate($request->mobile);
        dd($response);
        return response()->json(['error'=>false, 'resp'=>$response]);
    }
    /**
     * This method is to check validation of otp
     * @param str $otp
     * @return \Illuminate\Http\JsonResponse
     */
    public function otpcheck(Request $request) {
        $response = $this->userRepository->otpcheck($request->mobile, $request->otp);

        if ($response==false) {
            return response()->json(['error'=>true, 'resp'=>'You have entered an wrong otp. Please try with the correct one.']);
        } else {
            return response()->json(['error'=>false, 'resp'=>'OTP Correct','data'=>$response]);
        }


        // return response()->json(['error'=>false, 'resp'=>$response]);
    }

    /**
     * This method is to create user start day end day details
     * @param str $data
     * @return \Illuminate\Http\JsonResponse
     */
    public function distributorstore(Request $request): JsonResponse
    {
        $data = $request->only([
            'user_id', 'area_id','store_name', 'bussiness_name', 'store_OCC_number','contact','whatsapp','email','address','area','state','city','pin'
        ]);

        $response = $this->userRepository->distributorcreate($data);

        if ($response) {
            return response()->json(['error'=>false, 'resp'=>'Distributor created successfuly.','data'=>$response]);
        }
        else {
            return response()->json(['error'=>true, 'resp'=>'Something happened.']);
        }

      }

       public function distributorlist(): JsonResponse
       {
		   $ase = $_GET['ase'];
		   $area = $_GET['area'];
        //$activity = $this->userRepository->distributorlist();
		   //$activity = DB::select("select * from distributors where bussiness_name in (select distinct distributor_name from retailer_list_of_occ where ase like '%$ase%')");

		   // $activity = DB::select("select distributors.*,users.id as distributor_id from distributors join users on distributors.bussiness_name = users.name where distributors.bussiness_name in (select distinct distributor_name from retailer_list_of_occ where ase like '%$ase%') and area='$area'");

		   $activity = DB::select("select distributors.*,users.id as distributor_id from distributors join users on distributors.bussiness_name = users.name where distributors.bussiness_name in (select distinct distributor_name from retailer_list_of_occ where ase like '%$ase%') and area='$area' group by distributors.bussiness_name");

        return response()->json(['error' => false, 'resp' => 'Distributor data fetched successfully','data' => $activity]);
      }


    public function distributorshow(Request $request): JsonResponse
    {
        $id = $request->route('id');


        $activity = $this->userRepository->distributorview($id);

        return response()->json(['error'=>false, 'resp'=>'Distributor data fetched successfully','data'=>$activity]);
    }
        // return response()->json(
        //     [
        //         'data' => $this->userRepository->distributorcreate($data)
        //     ],
        //     Response::HTTP_CREATED
        // );




    /**
     * This method is to check login
     * @param str $mobileNo
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request) {
        //dd($request->all());
        $response = $this->userRepository->login( $request->email, $request->state);
        //dd($response);
        if ($response==false) {
            return response()->json(['error'=>true, 'resp'=>'You have entered an wrong login credential. Please try with the correct one.']);
        } else {
            return response()->json(['error'=>false, 'resp'=>'Login successfully','data'=>$response]);
        }
    }


    /**
     * This method is to check login
     * @param str $mobileNo
     * @return \Illuminate\Http\JsonResponse
     */
    public function mobilelogin(Request $request) {
		 $mobile = $request->mobile;
		$hash = bcrypt($request->password);
		//echo $hash;
        //dd($request->all());
        //$response = $this->userRepository->mobilelogin( $request->mobile, $request->password);
		$userExists = User::where('mobile', $mobile)->get();
        //print_r($userExists);
		//die();
        if (count($userExists)==0) {
            return response()->json(['error'=>true, 'resp'=>'You have entered an wrong login credential. Please try with the correct one.']);
        } else {
            return response()->json(['error'=>false, 'resp'=>'Login successfully','data'=>$userExists[0]]);
        } 

        // $mobile = $request->mobile;
        // $hashedPassword = Hash::make($request->password);

        // $userCheck = User::where('mobile', $mobile)->first();

        // if ($userCheck) {
        //     if (Hash::check($hashedPassword, $userCheck->password)) {
        //         return response()->json(['error' => false, 'resp' => 'Login successful', 'data' => $userCheck[0]]);
        //     } else {
        //         return response()->json(['error' => true, 'resp' => 'You have entered wrong login credential. Please try with the correct one.', 'data' => $userCheck->password]);
        //     }
        // } else {
        //     return response()->json(['error' => true, 'resp' => 'You have entered wrong login credential. Please try with the correct one.']);
        // }
    }
}

