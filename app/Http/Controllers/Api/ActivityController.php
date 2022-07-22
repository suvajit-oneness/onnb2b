<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\UserInterface;
use App\Repositories\UserRepository;

use Illuminate\Http\JsonResponse;

use Illuminate\Http\Response;

class ActivityController extends Controller
{
    private UserRepository $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * This method is for show activity list
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $activity = $this->userRepository->useractivity();

        return response()->json(['error' => false, 'resp' => 'User Activity data fetched successfully','data' => $activity]);
    }

    public function storeVisitIndex(): JsonResponse
    {
        $activity = $this->userRepository->storeVisit();

        return response()->json(['error' => false, 'resp' => 'Store visit data fetched successfully', 'data' => $activity]);
    }
    /**
     * This method is for create activity
     *
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->only([
            'user_id',
            'date',
            'time',
            'type',
            'comment',
            'location',
            'lat',
            'lng'
        ]);

        $activity = $this->userRepository->useractivitycreate($data);

        return response()->json(['error'=>false, 'resp'=>'User activity created successfully','data'=>$activity]);
    }
    public function storeVisitStore(Request $request): JsonResponse
    {
        $data = $request->only([
            'user_id',
            'date',
            'time',
            'type',
            'comment',
            'location',
            'lat',
            'lng'
        ]);

        $resp = $this->userRepository->storeVisitCreate($data);

        if ($resp) {
            // return response()->json(['data' => $resp], Response::HTTP_CREATED);
            return response()->json(['error' => false, 'resp' => 'Store visit data created successfully', 'data' => $resp]);
        } else {
            return response()->json(['error' => true, 'resp' => 'Something happened']);
        }
    }
    
    /** This method is show last 10 StoreVisit Details**/
    
    public function storeVisitlist($storeId): JsonResponse
    {
        $activity = $this->userRepository->storeVisitlist($storeId);

        return response()->json(['error' => false, 'resp' => 'Store visit data fetched successfully', 'data' => $activity]);
    }
    /**
     * This method is for show activity details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $activityId = $request->route('id');


        $activity = $this->userRepository->useractivitylistById($activityId);

        return response()->json(['error'=>false, 'resp'=>'User Activity data fetched successfully','data'=>$activity]);
    }
     /**
     * This method is for activity update
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function update(Request $request,int $id)
    {
        // $activityId = $request->route('id');
        $newDetails = $request->only([
            'user_id',
            'date',
            'time',
            'type',
            'comment',
            'location',
            'lat',
            'lng',

        ]);

        // return response()->json([
        //     'data' => $this->userRepository->useractivityupdate($activityId, $activityDetails)
        // ]);
       // $newDetails = $request->except('_token');

        $response = $this->userRepository->useractivityupdate($id, $newDetails);

       return response()->json($response);
        // return response()->json([
        //     'error'=>false,
        //      'resp'=>$request->type
        //     // 'data' => $this->userRepository->userdayupdate($dayId),
        //     ]);
    }
    /**
     * This method is for activity delete
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        //dd($request->all());
        $activityId = $request->route('id');
        $this->userRepository->useractivitydelete($activityId);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
