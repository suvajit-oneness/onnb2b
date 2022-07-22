<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use App\Models\Admin;
use Auth;
use Illuminate\Support\Facades\Hash;
use App\Services\Admin\AdminService;

class ProfileController extends BaseController
{
    protected $adminService;

    /**
     * ProfileController constructor
     */
    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }
    public function dashboard()
    {
        $this->setPageTitle('Dashboard', 'Manage Dashboard');
        return view('admin.dashboard.index');
    }
    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
		$profile = $this->adminService->fetchProfile(Auth::guard('admin')->user()->id);
        $this->setPageTitle('Profile', 'Manage Profile');
        return view('admin.profile.index', compact('profile'));
    }

    /**
     * @param Request $request
     */
    public function update(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "email" => "nullable|string",
           
        ]);
        $updateRequest = $request->all();
        $id = Auth::guard('admin')->user()->id;

        $this->adminService->updateProfile($updateRequest, $id);
        return $this->responseRedirectBack('Profile updated successfully.', 'success');
    }

    /**
     * @param Request $request
     */
    public function changePassword(Request $request) {
        $request->validate([
            "current_password" => "required|string|min:6|",
            "new_password" => "required|string|min:6|",
            "new_confirm_password" => "required|string|min:6|",
        ]);
        $id = Auth::guard('admin')->user()->id;
        $info = $this->adminService->changePassword($request, $id);

        if ($info['type'] == 'error') {
            return $this->responseRedirectBack($info['message'], $info['type'], true, true, '#password');
        } else {
            return $this->responseRedirectBack('Password updated successfully.', 'success');
        }
    }
}
