<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\OrderInterface;
use App\Interfaces\UserInterface;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    // private OrderInterface $orderRepository;

    public function __construct(OrderInterface $orderRepository,UserInterface $userRepository)
    {
        $this->orderRepository = $orderRepository;
        $this->userRepository = $userRepository;
    }

    public function index(Request $request)
    {

           if (!empty($request->term)) {

            $data = $this->orderRepository->searchOrderlist($request->term);
           // dd($data);
           }
        else{
            if($request->export_all){
                $category=Order::count();

            $data = $this->orderRepository->listAllOrder($category);
        }
        else{
         $data = $this->orderRepository->listAllOrder();
        }

    }
        return view('admin.order.index', compact('data'));
    }

    public function indexStatus(Request $request, $status)
    {
        $data = $this->orderRepository->listByStatus($status);
        return view('admin.order.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "type" => "required|integer",
            "amount" => "required",
            "max_time_of_use" => "required|integer",
            "max_time_one_can_use" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->orderRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.order.index');
        } else {
            return redirect()->route('admin.order.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->orderRepository->listById($id);
       // dd($data);
        return view('admin.order.detail', compact('data'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "type" => "required|integer",
            "amount" => "required",
            "max_time_of_use" => "required|integer",
            "max_time_one_can_use" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->orderRepository->update($id, $params);

        if ($storeData) {
            return redirect()->route('admin.order.index');
        } else {
            return redirect()->route('admin.order.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id, $status)
    {
        $storeData = $this->orderRepository->toggle($id, $status);

        if ($storeData) {
            return redirect()->back();
        } else {
            return redirect()->route('admin.order.index');
        }
    }
}
