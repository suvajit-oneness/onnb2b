<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Interfaces\WishlistInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
// use App\Models\Wishlist;

class WishlistController extends Controller
{
    public function __construct(WishlistInterface $wishlistRepository) 
    {
        $this->wishlistRepository = $wishlistRepository;
    }

    public function add(Request $request) 
    {
        $request->validate([
            "product_id" => "required|integer",
        ]);

        $params = $request->except('_token');

        $wishlistStore = $this->wishlistRepository->addToWishlist($params);

        if ($wishlistStore) {
            return redirect()->back()->with('success', $wishlistStore);
        } else {
            return redirect()->back()->with('failure', 'Something happened');
        }
    }
}
