<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Interfaces\StoreInterface;
use Illuminate\Http\Request;
use App\Models\Store;
class StoreController extends Controller
{
    public function __construct(StoreInterface $storeRepository)
    {
        $this->storeRepository = $storeRepository;
    }

    public function index(Request $request)
    {

        $data = $this->storeRepository->listAll();
        return view('user.store.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            "store_name" => "required|string|max:255",
            "bussiness_name" => "nullable|string",
            "contact" => "nullable|string",
            "email" => "nullable|string",
            "address" => "nullable|string",
            "state" => "nullable|string",
            "city" => "nullable|string",
            "pin" => "nullable|string",
            "area" => "nullable|string",

            "image" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);





        $params = $request->except('_token');

        $Store = $this->storeRepository->create($params);

        if ($Store) {
            return redirect()->route('store.index');
        } else {
            return redirect()->route('store.create')->withInput($request->all());
        }
    }

    public function show(Request $request, $id)
    {
        $data = $this->categoryRepository->getCategoryById($id);
        return view('admin.category.detail', compact('data'));
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "image_path" => "nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);

        // generate slug
        $slug = Str::slug($request->name, '-');
        $slugExistCount = Store::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug.'-'.($slugExistCount+1);

        // send slug
        request()->merge(['slug' => $slug]);

        $params = $request->except('_token');

        $categoryStore = $this->categoryRepository->updateCategory($id, $params);

        if ($categoryStore) {
            return redirect()->route('admin.category.index');
        } else {
            return redirect()->route('admin.category.create')->withInput($request->all());
        }
    }

    public function status(Request $request, $id)
    {
        $categoryStat = $this->categoryRepository->toggleStatus($id);

        if ($categoryStat) {
            return redirect()->route('admin.category.index');
        } else {
            return redirect()->route('admin.category.create')->withInput($request->all());
        }
    }

    public function destroy(Request $request, $id)
    {
        $this->categoryRepository->deleteCategory($id);

        return redirect()->route('admin.category.index');
    }
}
