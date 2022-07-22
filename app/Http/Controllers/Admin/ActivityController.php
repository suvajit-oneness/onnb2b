<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\UserInterface;
use Illuminate\Http\Request;


class ActivityController extends Controller
{
    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * This method is for show activity list
     *
     */
    public function index(Request $request)
    {

        //$data = $this->userRepository->useractivitylog();
        $users = $this->userRepository->listAll();
        $date = (isset($request->year_from) && $request->year_from!='')?$request->year_from:'';
        $time = (isset($request->year_to) && $request->year_to!='')?$request->year_to:'';
        $userId = (isset($request->user_id) && $request->user_id!='')?$request->user_id:'';
        $userType = (isset($request->user_type) && $request->user_type!='')?$request->user_type:'';
        $data = $this->userRepository->getActivityByFilter($date,$time,$userId,$userType);
        return view('admin.useractivity.index', compact('data','users'));
    }

    /**
     * This method is for show activity details
     * @param  $id
     *
     */
    public function show(Request $request, $id)
    {
        $data = $this->useractivitylistById->listById($id);

        return view('admin.useractivity.detail', compact('data'));
    }
}
