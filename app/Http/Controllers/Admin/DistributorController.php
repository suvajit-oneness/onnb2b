<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Interfaces\DistributorInterface;
use App\Interfaces\OrderInterface;
use App\Models\OrderDistributor;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
class DistributorController extends Controller
{
   // private DistributorInterface $userRepository;

    public function __construct(DistributorInterface $distributorRepository,OrderInterface $orderRepository)
    {
        $this->distributorRepository = $distributorRepository;
        $this->orderRepository = $orderRepository;
    }


    /**
     * This method is for show distributor/retailer list
     *
     */
    public function index(Request $request)
    {
        if ($request->term) {
            $data = $this->distributorRepository->customSearch($request->term);
        } else {
                $data = $this->distributorRepository->listAllDistributor();
               // dd($data);
        }
        return view('admin.distributor.index', compact('data'));
    }

    /**
     * This method is for create distributor/retailer list
     *
     */
    public function create(Request $request)
    {
        //dd($user_type);
        $users = User::all();
        return view('admin.distributor.create', compact('users'));
    }
    /**
     * This method is for create distributor/retailer
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
        $storeData = $this->distributorRepository->create($params);
        $userType = ($request->get('user_type') == 5) ? '5' : '6';
        if ($storeData) {
            return redirect()->route('admin.distributor.index', $userType);
        } else {
            return redirect()->route('admin.distributor.index', $userType)->withInput($request->all());
        }
    }
    /**
     * This method is for show distributor/retailer details
     * @param  $id
     *
     */
    public function show(Request $request, $id,$userType)
    {
        if ($userType == 5) {
            $data = $this->distributorRepository->getDistributorById($id,$userType);
        }
        elseif ($userType == 6) {
            $data = $this->distributorRepository->getRetailerById($id,$userType);
        }
        return view('admin.distributor.detail', compact('data', 'userType'));
    }


    public function edit(Request $request,$id ,$userType)
    {
        //dd($userType);
        if ($userType == 5) {
            $data = $this->distributorRepository->getDistributorById($id,$userType);
        }
        elseif ($userType == 6) {
            $data = $this->distributorRepository->getRetailerById($id,$userType);
        }

        return view('admin.distributor.edit', compact('data','userType'));
    }
    /**
     * This method is for distributor/retailer update
     *
     *
     */


    public function update(Request $request, $id)
    {
        dd($request->all());
        $params = $request->except('_token');
        $storeData = $this->distributorRepository->update($id, $params);
        $userType = ($request->get('user_type') == 5) ? '5' : '6';
        if ($storeData) {
            return redirect()->route('admin.distributor.index', $userType);
        } else {
            return redirect()->route('admin.distributor.index', $userType)->withInput($request->all());
        }
    }
    /**
     * This method is for update distributor/retailer status
     * @param  $id
     *
     */
    public function status(Request $request, $id, $userType)
    {
        $userTypeVal = ($userType == 'distributor') ? 5 : 6;
        $storeData = $this->distributorRepository->toggle($id,$userTypeVal);
        if ($storeData) {
            return redirect()->route('admin.distributor.index', $userType);
        } else {
            return redirect()->route('admin.distributor.index', $userType)->withInput($request->all());
        }
    }
    /**
     * This method is for update distributor/retailer verification
     * @param  $id
     *
     */
    public function verification(Request $request, $id)
    {
        $storeData = $this->distributorRepository->verification($id);

        if ($storeData) {
            return redirect()->route('admin.user.index');
        } else {
            return redirect()->route('admin.user.index')->withInput($request->all());
        }
    }
    /**
     * This method is for distributor/retailer delete
     * @param  $id
     *
     */
    public function destroy(Request $request, $id ,$userType)
    {
        $userTypeVal = ($userType == '5') ? 5 : 6;
        $this->distributorRepository->delete($id);
        return redirect()->route('admin.distributor.index', $userType);
    }



    public function order(Request $request)
    {

            if (!empty($request->term)) {

                $data = $this->distributorRepository->searchOrderlist($request->term);
                 // dd($data);
            }

         else {
            if($request->export_all){
                $category=OrderDistributor::count();

            $data = $this->orderRepository->listbyDistributorOrder($category);
            }else{
            $data = $this->orderRepository->listbyDistributorOrder();
            }
        }

        return view('admin.distributor.order', compact('data'));
    }


    public function orderdetail(Request $request, $id)
    {
        $data = $this->orderRepository->listByIdForDistributor($id);
        return view('admin.distributor.order-details', compact('data'));
    }
}
