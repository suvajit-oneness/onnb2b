<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Target;

class TargetController extends Controller
{
    public function index(Request $request)
    {
        $data = Target::where('user_id', Auth::guard('web')->user()->id)->latest('id')->get();
        return view('front.target.index', compact('data'));
    }
}
