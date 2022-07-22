<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\CatalogueInterface;
use Illuminate\Http\Request;
use App\ProductCatalogue;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\CatalogueExport;

class CatalogueController extends Controller
{
    public function __construct(CatalogueInterface $catalogueRepository)
    {
        $this->catalogueRepository = $catalogueRepository;
    }

    public function index(Request $request)
    {
        if (!empty($request->term)) {
            $data = $this->catalogueRepository->getSearchCatalogue($request->term);
        } else {
            if($request->export_all){
                $category=ProductCatalogue::count();
            $data = $this->catalogueRepository->getAllCatalogue($category);
        }
        else{
         $data = $this->catalogueRepository->getAllCatalogue();
        }
    }
        return view('admin.catalogue.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "title" => "required|string|max:255",
            "start_date" => "nullable|date",
            "end_date" => "nullable|date",
            "image" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "pdf" => "required|mimes:doc,docs,png,svg,jpg,excel,csv,pdf|max:10000000",

        ]);

        $params = $request->except('_token');
        $storeData = $this->catalogueRepository->createCatalogue($params);

        if ($storeData) {
            return redirect()->route('admin.catalogue.index');
        } else {
            return redirect()->route('admin.catalogue.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->catalogueRepository->getCatalogueById($id);
        return view('admin.catalogue.detail', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "title" => "required|string|max:255",
            "start_date" => "nullable|date",
            "end_date" => "nullable|date",
            "image" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000",
            "pdf" => "required|mimes:doc,docs,png,svg,jpg,excel,csv,pdf|max:10000000",
        ]);

        $params = $request->except('_token');
        $storeData = $this->catalogueRepository->updateCatalogue($id, $params);

        if ($storeData) {
            return redirect()->route('admin.catalogue.index');
        } else {
            return redirect()->route('admin.catalogue.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id)
    {
        $storeData = $this->catalogueRepository->toggleStatus($id);

        if ($storeData) {
            return redirect()->route('admin.catalogue.index');
        } else {
            return redirect()->route('admin.catalogue.create')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->catalogueRepository->deleteCatalogue($id);

        return redirect()->route('admin.catalogue.index');
    }
    public function bulkDestroy(Request $request)
    {
        // $request->validate([
        //     'bulk_action' => 'required',
        //     'delete_check' => 'required|array',
        // ]);

        $validator = Validator::make($request->all(), [
            'bulk_action' => 'required',
            'delete_check' => 'required|array',
        ], [
            'delete_check.*' => 'Please select at least one item'
        ]);

        if (!$validator->fails()) {
            if ($request['bulk_action'] == 'delete') {
                foreach ($request->delete_check as $index => $delete_id) {
                    Collection::where('id', $delete_id)->delete();
                }

                return redirect()->route('admin.collection.index')->with('success', 'Selected items deleted');
            } else {
                return redirect()->route('admin.collection.index')->with('failure', 'Please select an action')->withInput($request->all());
            }
        } else {
            return redirect()->route('admin.collection.index')->with('failure', $validator->errors()->first())->withInput($request->all());
        }
    }



    public function export()
    {


        return Excel::download(new CatalogueExport, 'catalogue.xlsx');
    }
}
