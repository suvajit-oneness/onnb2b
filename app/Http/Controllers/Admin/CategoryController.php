<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Interfaces\CategoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use App\Models\Category;

class CategoryController extends Controller
{
    private CategoryInterface $categoryRepository;

    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }
    /**
     * This method is for show category list
     *
     */
    public function index(Request $request)
    {
        if (!empty($request->term)) {

            $data = $this->categoryRepository->getSearchCategory($request->term);


        } else {
           if($request->export_all){
            $category=Category::count();

           $data = $this->categoryRepository->getCategories($category);
           //dd($data);
           }
           else{
            $data = $this->categoryRepository->getCategories();
           }
        }
        return view('admin.category.index', compact('data'));
    }
    /**
     * This method is for create category
     *
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required|string|max:255",
            "description" => "nullable|string",
            "image_path" => "required|mimes:jpg,jpeg,png,svg,gif|max:10000000"
        ]);

        // generate slug
        $slug = Str::slug($request->name, '-');
        $slugExistCount = Category::where('slug', $slug)->count();
        if ($slugExistCount > 0) $slug = $slug.'-'.($slugExistCount+1);

        // send slug
        request()->merge(['slug' => $slug]);

        // $params = $request->only(['name', 'description', 'image_path', 'slug']);
        $params = $request->except('_token');

        $categoryStore = $this->categoryRepository->createCategory($params);

        if ($categoryStore) {
            return redirect()->route('admin.category.index');
        } else {
            return redirect()->route('admin.category.create')->withInput($request->all());
        }
    }
    /**
     * This method is for show category details
     * @param  $id
     *
     */
    public function show(Request $request, $id)
    {
        $data = $this->categoryRepository->getCategoryById($id);
        return view('admin.category.detail', compact('data'));
    }
    /**
     * This method is for category update
     * @param  $id
     *
     */
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
        $slugExistCount = Category::where('slug', $slug)->count();
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
    /**
     * This method is for update category status
     * @param  $id
     *
     */
    public function status(Request $request, $id)
    {
        $categoryStat = $this->categoryRepository->toggleStatus($id);

        if ($categoryStat) {
            return redirect()->route('admin.category.index');
        } else {
            return redirect()->route('admin.category.create')->withInput($request->all());
        }
    }
    /**
     * This method is for category delete
     * @param  $id
     *
     */
    public function destroy(Request $request, $id)
    {
        $this->categoryRepository->deleteCategory($id);

        return redirect()->route('admin.category.index');
    }
}
