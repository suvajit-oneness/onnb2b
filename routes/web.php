<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// website
// user login & registration - guard
Route::middleware(['guest:web'])->name('front.')->group(function () {
    Route::prefix('user/')->name('user.')->group(function () {
        Route::get('/user/register', 'Front\UserController@register')->name('register');
        Route::post('/create', 'Front\UserController@create')->name('create');
        // Route::get('/login', 'Front\UserController@login')->name('login');
        Route::post('/login/check', 'Front\UserController@check')->name('check');
        Route::post('/login/otp/check', 'Front\UserController@loginOtp')->name('login.otp');
        Route::post('/login/mobile/otp/check', 'Front\UserController@loginMobileOtp')->name('login.mobile.otp');
    });
});

Route::middleware(['auth:web'])->name('front.')->group(function () {
    // home
    Route::get('/', 'Front\FrontController@index')->name('home');
    Route::post('/subscribe', 'Front\FrontController@mailSubscribe')->name('subscription');

    // store visit
    Route::prefix('store')->name('store.')->group(function () {
        Route::get('/add', 'Front\StoreController@add')->name('add');
        Route::post('/store', 'Front\StoreController@store')->name('store');
        Route::get('', 'Front\StoreController@index')->name('index');
        // Route::get('/{slug}', 'Front\StoreController@detail')->name('detail');
        Route::get('/{id}', 'Front\StoreController@detail')->name('detail');
        Route::get('/alert/{id}', 'Front\StoreController@alert')->name('cart.alert');
        Route::get('/visit/{id}', 'Front\StoreController@visit_store')->name('cart.visit-store');
        Route::post('/filter', 'Front\StoreController@filter')->name('filter');
        Route::post('/noorder', 'Front\StoreController@noOrder')->name('noorder');
        Route::get('/{id}/order/history', 'Front\StoreController@orderHistory')->name('order.history');

        // image
        Route::get('/image/view', 'Front\StoreController@imageView')->name('image.index');
        Route::get('/image/create', 'Front\StoreController@imageCreate')->name('image.create');
        Route::post('/image/add', 'Front\StoreController@imageAdd')->name('image.add');
        Route::get('/image/delete/{img}', 'Front\StoreController@delete_image')->name('image.delete');

        // store wise orders
        Route::get('/order/distributor', 'Front\StoreController@storeOrders')->name('order.index');
        Route::post('/search', 'Front\StoreController@search')->name('search');
    });

    // order on call
    Route::name('store.order.call.')->group(function () {
        Route::get('/order-on-call', 'Front\OrderOnCallController@index')->name('index');
        // Route::get('/order-on-call/{slug}', 'Front\OrderOnCallController@detail')->name('detail');
        Route::get('/order-on-call/{id}', 'Front\OrderOnCallController@detail')->name('detail');
        // Route::post('/order/filter', 'Front\OrderOnCallController@filter')->name('filter');
    });

    // offer
    Route::name('offer.')->group(function () {
        Route::get('/scheme', 'Front\FrontController@offerIndex')->name('index');
    });

    // category detail
    Route::name('category.')->group(function () {
        Route::get('/category/{slug}', 'Front\CategoryController@detail')->name('detail');
        Route::post('/category/filter', 'Front\CategoryController@filter')->name('filter');
    });

    // sale
    Route::name('sale.')->group(function () {
        Route::get('/sale', 'Front\SaleController@index')->name('index');
    });

    // collection detail
    Route::name('collection.')->group(function () {
        Route::get('/collection/{slug}', 'Front\CollectionController@detail')->name('detail');
        Route::post('/collection/filter', 'Front\CollectionController@filter')->name('filter');
    });

    // product detail
    Route::name('product.')->group(function () {
        Route::get('/product/{slug}', 'Front\ProductController@detail')->name('detail');
        Route::post('/product/search', 'Front\ProductController@search')->name('search');
        Route::post('/product', 'Front\ProductController@detailAjax')->name('detail.ajax');
        Route::post('/size', 'Front\ProductController@size')->name('size');
    });

    // wishlist
    Route::prefix('wishlist')->name('wishlist.')->group(function () {
        // Route::get('/', 'Front\WishlistController@viewByIp')->name('index');
        Route::post('/add', 'Front\WishlistController@add')->name('add');
        Route::get('/delete/{id}', 'Front\WishlistController@delete')->name('delete');
    });

    // catalouge download
    Route::name('catalouge.download.')->group(function () {
        Route::get('/download', 'Front\FrontController@catalougeDownloadIndex')->name('index');
    });

    // catalouge
    Route::prefix('catalouge')->name('catalouge.')->group(function () {
        Route::get('/', 'Front\CategoryController@index')->name('index');
        Route::get('/{slug}', 'Front\CategoryController@detail')->name('detail');
    });

    // distributor mom
    Route::name('directory.')->group(function () {
        Route::get('/distributor', 'Front\FrontController@directoryIndex')->name('index');
        Route::post('/distributor/mom/store', 'Front\FrontController@momStore')->name('mom.store');
    });

    // sales report
    Route::name('sales.report.')->group(function () {
        Route::get('/sales/report', 'Front\ReportController@index')->name('index');
        Route::get('/sales/report/detail', 'Front\ReportController@detail')->name('detail');
        // primary sale & secondary sale
        Route::get('/sales/report/detail/update', 'Front\ReportController@detailUpdated')->name('detail.updated');
        Route::get('/sales/report/distributor/detail', 'Front\ReportController@distributorDetail')->name('distributor.detail');
    });

    // team report
    Route::name('team.report.')->group(function () {
        Route::get('/team/report', 'Front\TeamReportController@index')->name('index');
        Route::get('/team/report/detail', 'Front\TeamReportController@detail')->name('detail');
        Route::get('/order', 'Front\TeamReportController@order')->name('order');
        // primary sale & secondary sale

    });


    // sales report
    Route::name('product.report.')->group(function () {
        Route::get('/product/report', 'Front\ProductReportController@index')->name('index');
        Route::get('/product/report/detail', 'Front\ProductReportController@detail')->name('detail');
        // primary sale & secondary sale

    });

    // region report
    Route::name('region.report.')->group(function () {
        Route::get('/region/report', 'Front\RegionReportController@index')->name('index');
        Route::get('/region/report/detail', 'Front\RegionReportController@detail')->name('detail');
        // primary sale & secondary sale

    });

    // dashboard
    Route::name('dashboard.')->group(function () {
        Route::get('/dashboard', 'Front\DashboardController@index')->name('index');
    });

    // target
    Route::name('target.')->group(function () {
        Route::get('/target', 'Front\TargetController@index')->name('index');
    });

    // cart
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', 'Front\CartController@viewByUserId')->name('index');
        Route::post('/coupon/check', 'Front\CartController@couponCheck')->name('coupon.check');
        Route::post('/coupon/remove', 'Front\CartController@couponRemove')->name('coupon.remove');
        Route::post('/add', 'Front\CartController@add')->name('add');
        Route::post('/add/bulk', 'Front\CartController@addBulk')->name('add.bulk');
        Route::post('/add/bulk/distributor', 'Front\CartController@addBulkDistributor')->name('add.bulk.distributor');
        Route::get('/delete/{id}', 'Front\CartController@delete')->name('delete');
        Route::get('/delete/{id}/distributor', 'Front\CartController@deleteDistributor')->name('distributor.delete');
        Route::get('/quantity/{id}/{type}', 'Front\CartController@qtyUpdate')->name('quantity');
    });

    // checkout
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', 'Front\CheckoutController@index')->name('index');
        // Route::post('/coupon/check', 'Front\CheckoutController@coupon')->name('coupon.check');
        Route::post('/store', 'Front\CheckoutController@store')->name('store');
        Route::post('/store/distributor', 'Front\CheckoutController@storeDistributor')->name('store.distributor');
        Route::view('/complete', 'front.checkout.complete')->name('complete');
    });

    // faq
    Route::prefix('faq')->name('faq.')->group(function () {
        Route::get('/', 'Front\FaqController@index')->name('index');
    });

    // search
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/', 'Front\SearchController@index')->name('index');
    });

    // invoice
    Route::prefix('invoice')->name('invoice.')->group(function () {
        Route::get('/', 'Front\InvoiceController@index')->name('index');
        Route::get('/create', 'Front\InvoiceController@create')->name('create');
        Route::post('/add', 'Front\InvoiceController@add')->name('add');
        Route::get('/edit/{id}', 'Front\InvoiceController@edit')->name('edit');
        Route::post('/update', 'Front\InvoiceController@update')->name('update');
        Route::get('/delete/{id}', 'Front\InvoiceController@delete')->name('delete');
    });

    // distributor order place
    Route::prefix('distributor')->name('distributor.')->group(function () {
        Route::get('order/place', 'Front\DistributorController@order')->name('order.place.index');
        Route::get('store/orders', 'Front\DistributorController@ordersFromStore')->name('store.orders.index');
        Route::post('store/orders/product', 'Front\DistributorController@productsOrdersList')->name('store.orders.product.index');
    });

    // settings contents
    Route::name('content.')->group(function () {
        Route::get('/terms-and-conditions', 'Front\ContentController@termDetails')->name('terms');
        Route::get('/privacy-statement', 'Front\ContentController@privacyDetails')->name('privacy');
        Route::get('/security', 'Front\ContentController@securityDetails')->name('security');
        Route::get('/disclaimer', 'Front\ContentController@disclaimerDetails')->name('disclaimer');
        Route::get('/shipping-and-delivery', 'Front\ContentController@shippingDetails')->name('shipping');
        Route::get('/payment-voucher-promotion', 'Front\ContentController@paymentDetails')->name('payment');
        Route::get('/return-policy', 'Front\ContentController@returnDetails')->name('return');
        Route::get('/refund-policy', 'Front\ContentController@refundDetails')->name('refund');
        Route::get('/service-and-contact', 'Front\ContentController@serviceDetails')->name('service');
    });

    // profile login & registration - guard
    // Route::middleware(['auth:web'])->group(function () {
    Route::prefix('user/')->name('user.')->group(function () {
        Route::view('profile', 'front.profile.index')->name('profile');
        Route::view('manage', 'front.profile.edit')->name('manage');
        Route::post('manage/update', 'Front\UserController@updateProfile')->name('manage.update');
        Route::view('password/edit', 'front.profile.password-edit')->name('password.edit');
        Route::post('password/update', 'Front\UserController@updatePassword')->name('password.update');
        Route::get('order', 'Front\UserController@order')->name('order');
        Route::get('order/{id}/invoice', 'Front\UserController@invoice')->name('distributor.invoice');
        Route::get('order/{id}/invoice/distributor', 'Front\UserController@invoiceDistributor')->name('invoice');
        Route::get('coupon', 'Front\UserController@coupon')->name('coupon');
        Route::get('address', 'Front\UserController@address')->name('address');
        Route::view('address/add', 'front.profile.address-add')->name('address.add');
        Route::post('address/add', 'Front\UserController@addressCreate')->name('address.create');
        Route::get('wishlist', 'Front\UserController@wishlist')->name('wishlist');
    });
    // });

    // mail template test
    Route::view('/mail/1', 'front.mail.register');
    Route::view('/mail/2', 'front.mail.order-confirm');
});

Auth::routes();

Route::get('login', 'Front\UserController@login')->name('login');
Route::get('/login/otp', 'Front\UserController@loginOtpMobile')->name('front.user.login.mobile');

// common & user guard
Route::prefix('user')->name('user.')->group(function () {
    Route::middleware(['guest:web'])->group(function () {
        Route::view('/register', 'auth.register')->name('register');
        Route::post('/create', 'User\UserController@create')->name('create');
        Route::view('/login', 'auth.login')->name('login');
        Route::post('/check', 'User\UserController@check')->name('check');
    });

    Route::middleware(['auth:web'])->group(function () {
        Route::view('/home', 'user.home')->name('home');
    });
});

require 'admin.php';

Route::get('/home', 'HomeController@index')->name('home');
