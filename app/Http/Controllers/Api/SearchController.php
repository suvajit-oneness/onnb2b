<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Interfaces\SearchInterface;
use Illuminate\Http\Request;
 use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
class SearchController extends Controller
{
    public function __construct(SearchInterface $searchRepository)
    {
        $this->searchRepository = $searchRepository;
    }
    /**
     * This method is for search product
     * @return \Illuminate\Http\JsonResponse
     *
     */
    public function index(Request $request): JsonResponse
    {
        $params = $request->except('_token');

        // $name = $request->route('name');

        $resp = $this->searchRepository->index($params);

        return response()->json([
            'status' => 200,
            'data' => $resp
        ]);

    }



    /**
     * This method is for search store
     * @param
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchStore(Request $request): JsonResponse
    {
        $params = $request->except('_token');

        // $name = $request->route('name');

        $resp = $this->searchRepository->Storesearch($params);

        return response()->json([
            'status' => 200,
            'data' => $resp
        ]);

    }
    
}
