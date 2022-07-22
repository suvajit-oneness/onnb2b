<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\CategoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
     * @return \Illuminate\Http\JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $categories = $this->categoryRepository->getAllCategories();

        return response()->json(['error'=>false, 'resp'=>'Category data fetched successfully','data'=>$categories]);
    }
    /**
     * This method is for create category
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->only([
            'name', 'description', 'image_path', 'slug'
        ]);

        return response()->json(
            [
                'data' => $this->categoryRepository->createCategory($data)
            ],
            Response::HTTP_CREATED
        );
    }

    /**
     * This method is for show category details
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Request $request): JsonResponse
    {
        $categoryId = $request->route('id');

        return response()->json([
            'data' => $this->categoryRepository->getCategoryById($categoryId)
        ]);
    }
    /**
     * This method is for category update
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request): JsonResponse
    {
        $categoryId = $request->route('id');
        $newDetails = $request->only([
             'name','description', 'image_path', 'slug'
        ]);

        return response()->json([
            'data' => $this->categoryRepository->updateCategory($categoryId, $newDetails)
        ]);
    }
    /**
     * This method is for category delete
     * @param  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function delete(Request $request): JsonResponse
    {
        $categoryId = $request->route('id');
        $this->categoryRepository->deleteCategory($categoryId);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }

}
