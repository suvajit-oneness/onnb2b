<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\TransactionInterface;
use App\Models\Transaction;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    // private TransactionInterface $transactionRepository;

    public function __construct(TransactionInterface $transactionRepository) 
    {
        $this->transactionRepository = $transactionRepository;
    }

    public function index(Request $request) 
    {
        $data = $this->transactionRepository->listAll();
        return view('admin.transaction.index', compact('data'));
    }

    public function store(Request $request) 
    {
        $request->validate([
            "name" => "required|string|max:255",
            "transaction_code" => "required|string|max:255",
            "type" => "required|integer",
            "amount" => "required",
            "max_time_of_use" => "required|integer",
            "max_time_one_can_use" => "required|integer",
            "start_date" => "required",
            "end_date" => "required",
        ]);

        $params = $request->except('_token');
        $storeData = $this->transactionRepository->create($params);

        if ($storeData) {
            return redirect()->route('admin.transaction.index');
        } else {
            return redirect()->route('admin.transaction.index')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->transactionRepository->listById($id);
        return view('admin.transaction.detail', compact('data'));
    }
}
