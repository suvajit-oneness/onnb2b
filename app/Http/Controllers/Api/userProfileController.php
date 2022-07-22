<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\UserInterface;
use Illuminate\Http\Request;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
class userProfileController extends Controller
{
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * This method is for show user profile details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function myprofile($id): JsonResponse
    {
        $user = $this->userRepository->userdetailsById($id);
        
        // return response()->json([
        //   //  'data' => $this->userRepository->userlistById($userId)
        //     $data = $this->userRepository->userdetailsById($id)
        // ]);
        return response()->json(['error'=>false, 'resp'=>'User data fetched successfully','data'=>$user[0]]);

    }

    /**
     * This method is to update user profile details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateprofile(Request $request,$id): JsonResponse
    {
        $validator = Validator::make($request->all(), [

            'fname' => ['nullable', 'string'],
            'lname' => ['nullable', 'string'],
            'email' => ['nullable', 'email'],
            'mobile' => ['nullable', 'numeric'],
            'address' => ['nullable', 'string'],
            'landmark' => ['nullable', 'string'],
            'state' => ['nullable', 'string'],
            'city' => ['nullable', 'string'],
            'pin' => ['nullable','numeric','digits:6'],
            'aadhar_no' => ['nullable'],
            'pan_no' => ['nullable'],
            'gender' => ['nullable'],
            'whatsapp_no' => ['nullable','numeric'],
            'image' => ['nullable'],
            'anniversary_date' => ['nullable','date'],
            'social_id' => ['nullable'],
            'dob' => ['nullable','date'],


        ]);
        // $newDetails = $request->only([
        //     'name', 'fname','lname', 'email', 'password','type','mobile','address','landmark','state','city','pin','aadhar_no','pan_no','gender','employee_id','whatsapp_no','otp','image','dob','anniversary_date','social_id'
        // ]);

        if (!$validator->fails()) {

           $data = $this->userRepository->updateuserprofile($id, $request->all());
        } else {
            return response()->json(['status' => 400, 'message' => $validator->errors()->first()]);
        }
        $params = $request->except('_token');
        return response()->json(
            [
                'data' => $this->userRepository->updateuserprofile($id,$params)
            ],
           // return response()->json(['error'=>false, 'resp'=>$response]);
           Response::HTTP_CREATED
        );

    }

}
