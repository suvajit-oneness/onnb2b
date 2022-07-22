<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\UserInterface;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class UserController extends Controller
{
     private UserInterface $userRepository;

    public function __construct(UserInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    /**
     * This method is for show user list
     *
     */
    public function index(Request $request)
    {
            $keyword = (isset($request->keyword) && $request->keyword!='')?$request->keyword:'';
            $user_type = (isset($request->user_type) && $request->user_type!='')?$request->user_type:'';

            $data = $this->userRepository->getSearchUser($request->keyword,$request->user_type);



       // $data = $this->userRepository->listAll();

        return view('admin.user.index', compact('data'));
    }

    /**
     * This method is for create user list
     *
     */
    public function create(Request $request)
    {
        $users = User::all();
        return view('admin.user.create', compact('users'));
    }
    /**
     * This method is for create user
     *
     */
    public function store(Request $request)
    {
        //dd($request->all());
        $request->validate([
            "title" => "required",
            "fname" => "required|string|max:255",
            "lname" => "required|string|max:255",
            "email" => "required|string|max:255|unique:users,email",
            "mobile" => "required|integer|digits:10",
            "whatsapp_no" => "required|integer|digits:10",
            "dob" => "required",
            "gender" => "required|string",
            "user_type" => "required",
            "employee_id" => "string|min:1",
            "address" => "required|string",
            "landmark" => "required|string",
            "state" => "required|string",
            "city" => "required|string",
            "aadhar_no" => "required|string",
            "pan_no" => "required|string",
            "pin" => "required|integer|digits:6",
            "password" => "required",
            "image"    =>"nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);

        $params = $request->except('_token');
        $storeData = $this->userRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.user.index');
        } else {
            return redirect()->route('admin.user.index')->withInput($request->all());
        }
    }
    /**
     * This method is for show user details
     * @param  $id
     *
     */
    public function show(Request $request, $id)
    {
        $data = $this->userRepository->listById($id);
        return view('admin.user.detail', compact('data'));
    }


    public function edit(Request $request,$id)
    {
        $data=User::findOrfail($id);
        $users = User::all();
        return view('admin.user.edit', compact('users','data'));
    }
    /**
     * This method is for user update
     *
     *
     */


    public function update(Request $request, $id)
    {

        $params = $request->except('_token');
        $storeData = $this->userRepository->update($id, $params);

        if ($storeData) {
            return redirect()->route('admin.user.index');
        } else {
            return redirect()->route('admin.user.create')->withInput($request->all());
        }
    }
    /**
     * This method is for update user status
     * @param  $id
     *
     */
    public function status(Request $request, $id)
    {
        $storeData = $this->userRepository->toggle($id);

        if ($storeData) {
            return redirect()->route('admin.user.index');
        } else {
            return redirect()->route('admin.user.index')->withInput($request->all());
        }
    }
    /**
     * This method is for update user verification
     * @param  $id
     *
     */
    public function verification(Request $request, $id)
    {
        $storeData = $this->userRepository->verification($id);

        if ($storeData) {
            return redirect()->route('admin.user.index');
        } else {
            return redirect()->route('admin.user.index')->withInput($request->all());
        }
    }
    /**
     * This method is for user delete
     * @param  $id
     *
     */
    public function destroy(Request $request, $id)
    {
        $this->userRepository->delete($id);

        return redirect()->route('admin.user.index');
    }
}
