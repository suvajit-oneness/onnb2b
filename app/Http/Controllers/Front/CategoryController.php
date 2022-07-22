<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\CategoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Category;

class CategoryController extends Controller
{
    public function __construct(CategoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        $data = $this->categoryRepository->getAllCategories();

        if ($data) {
            return view('front.category.index', compact('data'));
        } else {
            return view('front.404');
        }
    }

    public function detail(Request $request, $slug)
    {
        $data = $this->categoryRepository->getCategoryBySlug($slug);
        $sizes = $this->categoryRepository->getAllSizes();
        $colors = $this->categoryRepository->getAllColors();

        if ($data) {
            return view('front.category.detail', compact('data', 'sizes', 'colors'));
        } else {
            return view('front.404');
        }
    }

    public function filter(Request $request)
    {
        $data = $this->categoryRepository->productsByCategory($request->categoryId, $request->except('_token'));

        if ($data) {
            return response()->json(['status' => 200, 'message' => 'Products found', 'data' => $data], 200);
        } else {
            return response()->json(['status' => 400, 'message' => 'No products found'], 400);
        }
    }
}
