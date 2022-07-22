<?php

namespace App\Http\Controllers\Admin;

use App\Interfaces\SubcategoryInterface;
use App\Models\SubCategory;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class SubCategoryController extends Controller
{
    // private SubcategoryInterface $SubcategoryRepository;

    public function __construct(SubcategoryInterface $SubcategoryRepository)
    {
        $this->subcategoryRepository = $SubcategoryRepository;
    }
    /**
     * This method is for show sub category list
     *
     */
    public function index(Request $request)
    {
        $data = $this->subcategoryRepository->getAllSubcategories();
        $categories = $this->subcategoryRepository->getAllCategories();
        return view('admin.subcategory.index', compact('data', 'categories'));
    }
     /**
     * This method is for create subcategory
     *
     */
    public function store(Request $request)
    {
        $request->validate([
            "cat_id" => "required|integer",
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "image_path" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);

        // generate slug
        $slug = Str::slug($request->name, '-');
        $slugExistCount = SubCategory::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug.'-'.($slugExistCount+1);

        // send slug
        request()->merge(['slug' => $slug]);

        // $params = $request->only(['name', 'description', 'image_path', 'slug']);
        $params = $request->except('_token');

        $storeData = $this->subcategoryRepository->createSubcategory($params);

        if ($storeData) {
            return redirect()->route('admin.subcategory.index');
        } else {
            return redirect()->route('admin.subcategory.create')->withInput($request->all());
        }
    }
    /**
     * This method is for show subcategory details
     * @param  $id
     *
     */
    public function show(Request $request, $id)
    {
        $data = $this->subcategoryRepository->getSubcategoryById($id);
        $categories = $this->subcategoryRepository->getAllCategories();
        return view('admin.subcategory.detail', compact('data', 'categories'));
    }
    /**
     * This method is for subcategory update
     *
     *
     */
    public function update(Request $request, $id)
    {
        // dd($request->all());

        $request->validate([
            "cat_id" => "nullable|integer",
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "image_path" => "nullable|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);

        // generate slug
        $slug = Str::slug($request->name, '-');
        $slugExistCount = SubCategory::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug.'-'.($slugExistCount+1);

        // send slug
        request()->merge(['slug' => $slug]);

        $params = $request->except('_token');

        $storeData = $this->subcategoryRepository->updateSubcategory($id, $params);

        if ($storeData) {
            return redirect()->route('admin.subcategory.index');
        } else {
            return redirect()->route('admin.subcategory.create')->withInput($request->all());
        }
    }
    /**
     * This method is for update subcategory status
     * @param  $id
     *
     */
    public function status(Request $request, $id)
    {
        $storeData = $this->subcategoryRepository->toggleStatus($id);

        if ($storeData) {
            return redirect()->route('admin.subcategory.index');
        } else {
            return redirect()->route('admin.subcategory.create')->withInput($request->all());
        }
    }
    /**
     * This method is for subcategory delete
     * @param  $id
     *
     */
    public function destroy(Request $request, $id)
    {
        $this->subcategoryRepository->deleteSubcategory($id);

        return redirect()->route('admin.subcategory.index');
    }
}
