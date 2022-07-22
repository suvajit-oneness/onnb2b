<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\DistributorInterface;
use App\Interfaces\InvoiceInterface;
use App\Interfaces\OrderInterface;
use App\Models\InvoiceRetailer;
use App\Models\RetailerListOfOcc;
use App\Models\RetailerStoreImage;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
class RetailerController extends Controller
{
    private DistributorInterface $userRepository;

    public function __construct(DistributorInterface $distributorRepository,OrderInterface $orderRepository , InvoiceInterface $invoiceRepository)
    {
        $this->distributorRepository = $distributorRepository;
        $this->orderRepository = $orderRepository;
        $this->invoiceRepository = $invoiceRepository;
    }


    public function index(Request $request)
    {
        if ($request->term) {
            $data = $this->distributorRepository->customSearch($request->term);
        } else {
        $data = $this->distributorRepository->listAllRetailer();
        }
        return view('admin.retailer.index', compact('data'));
    }

    public function order(Request $request)
    {
        if (!empty($request->status)) {
            if (!empty($request->term)) {
                $data = $this->orderRepository->listByStatus($request->status);
                $data = $this->orderRepository->searchOrder($request->term);
            } else {
                $data = $this->orderRepository->listByStatus($request->status);
            }
        } else {
            $data = $this->orderRepository->listAll();
        }

        return view('admin.retailer.order', compact('data'));
    }


    public function orderdetail(Request $request, $id)
    {
        $data = $this->orderRepository->listByIdForRetailer($id);
        return view('admin.retailer.order-details', compact('data'));
    }

    public function invoice($storeId,Request $request)
    {
        $data = DB::table('invoice_retailer')->where('retailer_id',$storeId)->get()->toArray();
        //dd($data);
        return view('admin.retailer.invoice', compact('data'));
    }
    public function image($storeId,Request $request)
    {
        $data = DB::table('retailer_store_image')->where('store_id',$storeId)->get()->toArray();
        //dd($data);
        return view('admin.retailer.image', compact('data'));
    }

    public function imagedelete($id,$img)
    {
        DB::delete('delete from retailer_store_image where id = ?',[$id]);
        return redirect()->back()->with(['success' => "Images deleted successfully!"]);
    }

}
