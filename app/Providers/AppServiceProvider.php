<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use App\Models\Category;
use App\Models\CategoryParent;
use App\Models\Collection;
use App\Models\Settings;
use App\Models\Cart;
use App\Models\CartDistributor;
use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view::composer('*', function($view) {
            $ip = $_SERVER['REMOTE_ADDR'];

            // categories
            $categoryExists = Schema::hasTable('categories');
            if ($categoryExists) {
                // $parentCategories = CategoryParent::orderBy('position', 'asc')->orderBy('id', 'desc')->where('status', 1)->get();
                // dd($parentCategories);
                $categories = Category::with('parentCatDetails')->orderBy('parent', 'asc')->orderBy('position', 'desc')->where('status', 1)->get();

                $categoryNavList = [];

                foreach ($categories as $catKey => $catValue) {
                    // if (in_array_r($catValue->parent, $categoryNavList)) continue;
                    //if (in_array_r($catValue->parentCatDetails->name, $categoryNavList)) continue;

                    //$childCategories = Category::select('slug', 'name', 'sketch_icon')->where('parent', $catValue->parent)->get()->toArray();

                    // $categoryParent = CategoryParent::where('id', $catValue->parent)->first();

                   // $categoryNavList[] = [
                    //    'parent' => $catValue->parentCatDetails->name,
                        // 'parent' => $catValue->parent,
                        // 'parent' => $categoryParent->name,
                     //   'child' => $childCategories
                   // ];
                }
            }

            // dd($categoryNavList);

            // collections
            $collectionExists = Schema::hasTable('collections');
            if ($collectionExists) {
                $collections = Collection::orderBy('position', 'asc')->orderBy('id', 'desc')->where('status', 1)->get();
            }

            // settings
            $settingsExists = Schema::hasTable('settings');
            if ($settingsExists) {
                $settings = Settings::where('status', 1)->get();
            }

            // cart count
            $cartExists = Schema::hasTable('carts');
            if ($cartExists) {
                if (Auth::guard('web')->check()) {
                    if (Auth::guard('web')->user()->user_type == 5) {
                        $cartCount = CartDistributor::where('user_id', Auth::guard('web')->user()->id)->get();
                    } else {
                        $cartCount = Cart::where('user_id', Auth::guard('web')->user()->id)->get();
                    }

                    $totalCartProducts = 0;
                    foreach($cartCount as $cartKey => $cartVal) {
                        $totalCartProducts += $cartVal->qty;
                    }
                } else {
                    $totalCartProducts = 0;
                }
            }

            // wishlist count
            $wishlistExists = Schema::hasTable('wishlists');
            if ($wishlistExists) {
                $wishlistCount = Wishlist::where('ip', $ip)->count();
            }

            $loggedInUserType = '';
            // user type detail
            if (Auth::guard('web')->check()) {
                $loggedInUserType = Auth::guard('web')->user()->user_type;
            }

            switch ($loggedInUserType) {
                case 1: $userTypeDetail = "Vice President";break;
                case 2: $userTypeDetail = "Regional sales manager";break;
                case 3: $userTypeDetail = "Area sales manager";break;
                case 4: $userTypeDetail = "Area sales executive";break;
                case 5: $userTypeDetail = "Distributor";break;
                case 6: $userTypeDetail = "Retailer";break;
                default: $userTypeDetail = "";break;
            }

            view()->share('categories', $categories);
            view()->share('categoryNavList', $categoryNavList);
            view()->share('collections', $collections);
            view()->share('settings', $settings);
            view()->share('cartCount', $totalCartProducts);
            view()->share('wishlistCount', $wishlistCount);
            view()->share('userTypeDetail', $userTypeDetail);
        });
    }
}
