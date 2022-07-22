<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\AddressInterface;
use App\Models\Address;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class AddressController extends Controller
{
    // private AddressInterface $addressRepository;

    public function __construct(AddressInterface $addressRepository) 
    {
        $this->addressRepository = $addressRepository;
    }

    public function index(Request $request) 
    {
        $data = $this->addressRepository->listAll();
        $users = $this->addressRepository->listUsers();
        return view('admin.address.index', compact('data', 'users'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            "user_id" => "required|integer",
            "address" => "required|string|max:255",
            "landmark" => "required|string|max:255",
            "lat" => "required",
            "lng" => "required",
            "type" => "required|integer",
            "state" => "required|string",
            "city" => "required|string",
            "pin" => "required|integer|digits:6",
            "type" => "required|integer",
        ]);

        $params = $request->except('_token');
        $storeData = $this->addressRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.address.index');
        } else {
            return redirect()->route('admin.address.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->addressRepository->listById($id);
        $users = $this->addressRepository->listUsers();
        return view('admin.address.detail', compact('data', 'users'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "user_id" => "required|integer",
            "address" => "required|string|max:255",
            "landmark" => "required|string|max:255",
            "lat" => "required",
            "lng" => "required",
            "type" => "required|integer",
            "state" => "required|string",
            "city" => "required|string",
            "pin" => "required|integer|digits:6",
            "type" => "required|integer",
        ]);

        $params = $request->except('_token');
        $storeData = $this->addressRepository->update($id, $params);

        if ($storeData) {
            return redirect()->route('admin.address.index');
        } else {
            return redirect()->route('admin.address.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id)
    {
        $storeData = $this->addressRepository->toggle($id);

        if ($storeData) {
            return redirect()->route('admin.address.index');
        } else {
            return redirect()->route('admin.address.create')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id) 
    {
        $this->addressRepository->delete($id);

        return redirect()->route('admin.address.index');
    }
}
