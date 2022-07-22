<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\StoreInterface;
use App\Interfaces\CategoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderOnCallController extends Controller
{
    public function __construct(StoreInterface $storeRepository, CategoryInterface $categoryRepository)
    {
        $this->storeRepository = $storeRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function index(Request $request)
    {
        // $data = $this->storeRepository->listAll();
        $data = $this->storeRepository->viewStoreInFrontend();

        if ($data) {
            return view('front.store.index', compact('data'));
        } else {
            return view('front.404');
        }
    }

    public function detail(Request $request, $id)
    {
        $data = $this->storeRepository->listById($id);
        // $data = $this->storeRepository->listBySlug($slug);
        $category = $this->categoryRepository->getAllCategories();

        if ($data) {
            return view('front.store.detail', compact('data', 'category'));
        } else {
            return view('front.404');
        }
    }
}
