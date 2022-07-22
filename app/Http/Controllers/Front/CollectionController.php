<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\CollectionInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Collection;

class CollectionController extends Controller
{
    public function __construct(CollectionInterface $collectionRepository)
    {
        $this->collectionRepository = $collectionRepository;
    }

    public function detail(Request $request, $slug)
    {
        // dd($request->all());
        $data = $this->collectionRepository->getCollectionBySlug($slug);
        $sizes = $this->collectionRepository->getAllSizes();
        $colors = $this->collectionRepository->getAllColors();

        if ($data) {
            return view('front.collection.detail', compact('data', 'sizes', 'colors'));
        } else {
            return view('front.404');
        }
    }

    public function filter(Request $request)
    {
        $data = $this->collectionRepository->productsByCollection($request->collectionId, $request->except('_token'));

        if ($data) {
            return response()->json(['status' => 200, 'message' => 'Products found', 'data' => $data], 200);
        } else {
            return response()->json(['status' => 400, 'message' => 'No products found'], 400);
        }
    }
}