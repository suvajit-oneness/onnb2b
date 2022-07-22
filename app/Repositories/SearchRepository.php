<?php

namespace App\Repositories;

use App\Interfaces\SearchInterface;
use App\Models\Product;
use App\Models\Store;
class SearchRepository implements SearchInterface
{
    public function __construct() {
        $this->ip = $_SERVER['REMOTE_ADDR'];
    }
    /**
     * This method is for search product
     * @param
     *
     */
    public function index(array $data)
    {
        $collectedData = collect($data);

        $resp = Product::where('name', 'like', '%'.$collectedData['query'].'%')
        ->orWhere('slug', 'like', '%'.$collectedData['query'].'%')
        ->orWhere('style_no', 'like', '%'.$collectedData['query'].'%')
        ->orWhere('short_desc', 'like', '%'.$collectedData['query'].'%')
        ->orWhere('desc', 'like', '%'.$collectedData['query'].'%')
        ->with('category','subCategory','collection')->get();

        return $resp;
    }


    /**
     * This method is for search store
     * @param
     *
     */
    public function Storesearch(array $data)
    {
        $collectedData = collect($data);

        $resp = Store::where('store_name', 'like', '%'.$collectedData['query'].'%')
        ->orWhere('bussiness_name', 'like', '%'.$collectedData['query'].'%')
        ->orWhere('store_OCC_number', 'like', '%'.$collectedData['query'].'%')
        ->orWhere('address', 'like', '%'.$collectedData['query'].'%')
        ->with('user')->get();

        return $resp;
    }
    }

