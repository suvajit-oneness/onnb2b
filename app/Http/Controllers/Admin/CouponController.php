<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\CouponInterface;
use App\Models\Coupon;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    // private CouponInterface $couponRepository;

    public function __construct(CouponInterface $couponRepository) 
    {
        $this->couponRepository = $couponRepository;
    }

    public function index(Request $request) 
    {
        $data = $this->couponRepository->listAll();
        return view('admin.coupon.index', compact('data'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            "name" => "required|string|max:255",
            "coupon_code" => "required|string|max:255",
            "type" => "required|integer",
            "amount" => "required",
            "max_time_of_use" => "required|integer",
            "max_time_one_can_use" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->couponRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.coupon.index');
        } else {
            return redirect()->route('admin.coupon.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->couponRepository->listById($id);
        return view('admin.coupon.detail', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "name" => "required|string|max:255",
            "coupon_code" => "required|string|max:255",
            "type" => "required|integer",
            "amount" => "required",
            "max_time_of_use" => "required|integer",
            "max_time_one_can_use" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->couponRepository->update($id, $params);

        if ($storeData) {
            return redirect()->route('admin.coupon.index');
        } else {
            return redirect()->route('admin.coupon.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id)
    {
        $storeData = $this->couponRepository->toggle($id);

        if ($storeData) {
            return redirect()->route('admin.coupon.index');
        } else {
            return redirect()->route('admin.coupon.create')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id) 
    {
        $this->couponRepository->delete($id);

        return redirect()->route('admin.coupon.index');
    }
}
