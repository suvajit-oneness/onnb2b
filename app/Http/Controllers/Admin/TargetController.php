<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\TargetInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Target;
use App\Models\Collection;
use App\User;
class TargetController extends Controller
{
    // TargetInterface $targetRepository;

    public function __construct(TargetInterface $targetRepository)
    {
        $this->targetRepository = $targetRepository;
    }
    public function index(Request $request)
    {
        $data = Target::orderBy('id', 'desc')->get();
        $collections= Collection::orderBy('name')->get();
        $users= User::orderBy('name')->get();
        $collection = (isset($request->collection_id) && $request->collection_id!='')?$request->collection_id:'';
        $year_from = (isset($request->year_from) && $request->year_from!='')?$request->year_from:'';
        $year_to = (isset($request->year_to) && $request->year_to!='')?$request->year_to:'';
        $userId = (isset($request->user_id) && $request->user_id!='')?$request->user_id:'';
        $userType = (isset($request->user_type) && $request->user_type!='')?$request->user_type:'';
        $target = $this->targetRepository->getTargetByFilter($collection,$year_from,$year_to,$userId,$userType);
        //dd($target);
        return view('admin.target.index', compact('data','users','collections','target'));
    }

    public function create(Request $request)
    {
        $data = Target::orderBy('id', 'desc')->get();
        $users= User::orderBy('fname')->get();
        $collection= Collection::orderBy('name')->get();
        return view('admin.target.create', compact('data','users','collection'));
    }

    public function store(Request $request)
    {
        //dd($request->all());
        // $request->validate([
        //     "to" => "required",
        //     "amount" => "required",
        //     "year_from" => "required",
        //     "year_to" => "required",
        //     "collection" => "required",
        //     "title" => "required",

        // ]);
        $params = $request->except('_token');

        $targetStore = $this->targetRepository->createTarget($params);

        if ($targetStore) {
            return redirect()->route('admin.target.index');
        } else {
            return redirect()->route('admin.target.create')->withInput($request->all());
        }
    }
     /**
     * This method is for show category details
     * @param  $id
     *
     */
    public function show(Request $request, $targetId)
    {
        $data = $this->targetRepository->getTargetById($targetId);
        return view('admin.target.detail', compact('data'));
    }


    public function edit(Request $request,$targetId)
    {
        $data = $this->targetRepository->getTargetById($targetId);
        $users= User::orderBy('fname')->get();
        $collection= Collection::orderBy('name')->get();
        return view('admin.target.edit', compact('targetId','data','users','collection'));
    }
    /**
     * This method is for category update
     * @param  $id
     *
     */
    public function update(Request $request, $id)
    {
         dd($request->all());

        // $request->validate([
        //     "to" => "required",
        //     "amount" => "required|string",
        //     "year_from" => "required",
        //     "year_to" => "required",
        //     "collection" => "required",
        //     "title" => "required|string|max:255",
        // ]);

        $params = $request->except('_token');

        $targetStore = $this->targetRepository->updateTarget($id,$params);

        if ($targetStore) {
            return redirect()->route('admin.target.index');
        } else {
            return redirect()->route('admin.target.create')->withInput($request->all());
        }
    }
    /**
     * This method is for update category status
     * @param  $id
     *
     */
    public function status(Request $request, $targetId)
    {
        $targetStore = $this->targetRepository->targetStatus($targetId);

        if ($targetStore) {
            return redirect()->route('admin.target.index');
        } else {
            return redirect()->route('admin.target.create')->withInput($request->all());
        }
    }
    /**
     * This method is for target delete
     * @param  $id
     *
     */
    public function destroy(Request $request, $targetId)
    {
        $this->targetRepository->deleteTarget($targetId);

        return redirect()->route('admin.target.index');
    }

}
