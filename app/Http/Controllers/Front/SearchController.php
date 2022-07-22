<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\SearchInterface;
use Illuminate\Http\Request;
// use Illuminate\Http\Response;

class SearchController extends Controller
{
    public function __construct(SearchInterface $searchRepository) 
    {
        $this->searchRepository = $searchRepository;
    }

    public function index(Request $request) 
    {
        $params = $request->except('_token');

        $data = $this->searchRepository->index($params);

        return view('front.search.index', compact('data', 'request'));
    }
}
