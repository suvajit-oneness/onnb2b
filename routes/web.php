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

// admin guard
Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware(['guest:admin'])->group(function () {
        Route::view('/login', 'admin.auth.login')->name('login');
        Route::post('/check', 'Admin\AdminController@check')->name('login.check');
        Route::get('forget-password', 'Admin\ForgotPasswordController@showForgetPasswordForm')->name('forget.password.get');
        Route::post('forget-password', 'Admin\ForgotPasswordController@submitForgetPasswordForm')->name('forget.password.post');
        Route::get('reset-password/{token}', 'Admin\ForgotPasswordController@showResetPasswordForm')->name('reset.password.get');
        Route::post('reset-password', 'Admin\ForgotPasswordController@submitResetPasswordForm')->name('reset.password.post');
    });

    Route::middleware(['auth:admin'])->group(function () {
        // dashboard
        Route::get('/home', 'Admin\AdminController@home')->name('home');
        Route::get('/profile', 'Admin\ProfileController@index')->name('admin.profile');
        Route::post('/profile', 'Admin\ProfileController@update')->name('admin.profile.update');
        Route::post('/changepassword', 'Admin\ProfileController@changePassword')->name('admin.profile.changepassword');

        // category
        Route::prefix('category')->name('category.')->group(function () {
            Route::get('/', 'Admin\CategoryController@index')->name('index');
            // Route::get('/active', 'Admin\CategoryController@activeCategory')->name('active');
            // Route::get('/inactive', 'Admin\CategoryController@inactiveCategory')->name('inactive');
            Route::post('/store', 'Admin\CategoryController@store')->name('store');
            Route::get('/{id}/view', 'Admin\CategoryController@show')->name('view');
            Route::post('/{id}/update', 'Admin\CategoryController@update')->name('update');
            Route::get('/{id}/status', 'Admin\CategoryController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\CategoryController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\CategoryController@bulkDestroy')->name('bulkDestroy');
        });

        // sub-category
        Route::prefix('subcategory')->name('subcategory.')->group(function () {
            Route::get('/', 'Admin\SubCategoryController@index')->name('index');
            Route::post('/store', 'Admin\SubCategoryController@store')->name('store');
            Route::get('/{id}/view', 'Admin\SubCategoryController@show')->name('view');
            Route::post('/{id}/update', 'Admin\SubCategoryController@update')->name('update');
            Route::get('/{id}/status', 'Admin\SubCategoryController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\SubCategoryController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\SubCategoryController@bulkDestroy')->name('bulkDestroy');
        });

        // collection
        Route::prefix('collection')->name('collection.')->group(function () {
            Route::get('/', 'Admin\CollectionController@index')->name('index');
            Route::post('/store', 'Admin\CollectionController@store')->name('store');
            Route::get('/{id}/view', 'Admin\CollectionController@show')->name('view');
            Route::post('/{id}/update', 'Admin\CollectionController@update')->name('update');
            Route::get('/{id}/status', 'Admin\CollectionController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\CollectionController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\CollectionController@bulkDestroy')->name('bulkDestroy');
        });

        // catalogue
        Route::prefix('catalogue')->name('catalogue.')->group(function () {
            Route::get('/', 'Admin\CatalogueController@index')->name('index');
            Route::post('/store', 'Admin\CatalogueController@store')->name('store');
            Route::get('/{id}/view', 'Admin\CatalogueController@show')->name('view');
            Route::post('/{id}/update', 'Admin\CatalogueController@update')->name('update');
            Route::get('/{id}/status', 'Admin\CatalogueController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\CatalogueController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\CatalogueController@bulkDestroy')->name('bulkDestroy');
        });

        // coupon
        Route::prefix('coupon')->name('coupon.')->group(function () {
            Route::get('/', 'Admin\CouponController@index')->name('index');
            Route::post('/store', 'Admin\CouponController@store')->name('store');
            Route::get('/{id}/view', 'Admin\CouponController@show')->name('view');
            Route::post('/{id}/update', 'Admin\CouponController@update')->name('update');
            Route::get('/{id}/status', 'Admin\CouponController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\CouponController@destroy')->name('delete');
        });

        // offer
        Route::prefix('offer')->name('offer.')->group(function () {
            Route::get('/', 'Admin\OfferController@index')->name('index');
            Route::post('/offer', 'Admin\OfferController@store')->name('store');
            Route::get('/{id}/view', 'Admin\OfferController@show')->name('view');
            Route::post('/{id}/update', 'Admin\OfferController@update')->name('update');
            Route::get('/{id}/status', 'Admin\OfferController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\OfferController@destroy')->name('delete');
        });

        // order
        Route::prefix('order')->name('order.')->group(function () {
            Route::get('/', 'Admin\OrderController@index')->name('index');
            Route::post('/store', 'Admin\OrderController@store')->name('store');
            Route::get('/{id}/view', 'Admin\OrderController@show')->name('view');
            Route::post('/{id}/update', 'Admin\OrderController@update')->name('update');
            Route::get('/{id}/status/{status}', 'Admin\OrderController@status')->name('status');
        });


        // transaction
        Route::prefix('transaction')->name('transaction.')->group(function () {
            Route::get('/', 'Admin\TransactionController@index')->name('index');
            Route::get('/{id}/view', 'Admin\TransactionController@show')->name('view');
        });

        // store
        Route::prefix('store')->name('store.')->group(function () {
            Route::get('/', 'Admin\StoreController@index')->name('index');
            Route::get('/create', 'Admin\StoreController@create')->name('create');
            Route::post('/store', 'Admin\StoreController@store')->name('store');
            Route::get('/{id}/view', 'Admin\StoreController@show')->name('view');
            Route::post('/{id}/update', 'Admin\StoreController@update')->name('update');
            Route::get('/{id}/status', 'Admin\StoreController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\StoreController@destroy')->name('delete');

        });
        //no order
        Route::get('/no-order-reason', 'Admin\StoreController@noorderreasonshow')->name('noorderreasonview');
        // user
        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/', 'Admin\UserController@index')->name('index');
            Route::get('/create', 'Admin\UserController@create')->name('create');
            Route::post('/store', 'Admin\UserController@store')->name('store');
            Route::get('/{id}/view', 'Admin\UserController@show')->name('view');
            Route::get('/{id}/edit', 'Admin\UserController@edit')->name('edit');
            Route::post('/{id}/update', 'Admin\UserController@update')->name('update');
            Route::get('/{id}/status', 'Admin\UserController@status')->name('status');
            Route::get('/{id}/verification', 'Admin\UserController@verification')->name('verification');
            Route::get('/{id}/delete', 'Admin\UserController@destroy')->name('delete');
        });

        //user activity
        Route::prefix('useractivity')->name('useractivity.')->group(function () {
            Route::get('/', 'Admin\ActivityController@index')->name('index');
            Route::get('/{id}/view', 'Admin\ActivityController@show')->name('view');
        });

       // product
        Route::prefix('product')->name('product.')->group(function () {
            Route::get('/list', 'Admin\ProductController@index')->name('index');
            Route::get('/create', 'Admin\ProductController@create')->name('create');
            Route::post('/store', 'Admin\ProductController@store')->name('store');
            Route::get('/{id}/view', 'Admin\ProductController@show')->name('view');
            Route::post('/size', 'Admin\ProductController@size')->name('size');
            Route::get('/{id}/edit', 'Admin\ProductController@edit')->name('edit');
            Route::post('/update', 'Admin\ProductController@update')->name('update');
            Route::get('/{id}/status', 'Admin\ProductController@status')->name('status');
            Route::get('/{id}/sale', 'Admin\ProductController@sale')->name('sale');
            Route::get('/{id}/trending', 'Admin\ProductController@trending')->name('trending');
            Route::get('/{id}/delete', 'Admin\ProductController@destroy')->name('delete');
            Route::get('/{id}/image/delete', 'Admin\ProductController@destroySingleImage')->name('image.delete');
            Route::get('/bulkDelete', 'Admin\ProductController@bulkDestroy')->name('bulkDestroy');

            // variation
            Route::post('/variation/color/add', 'Admin\ProductController@variationColorAdd')->name('variation.color.add');
            Route::get('/variation/{productId}/color/{colorId}/delete', 'Admin\ProductController@variationColorDestroy')->name('variation.color.delete');
            Route::post('/variation/color/edit', 'Admin\ProductController@variationColorEdit')->name('variation.color.edit');
            Route::post('/variation/color/position', 'Admin\ProductController@variationColorPosition')->name('variation.color.position');
            Route::post('/variation/color/status/toggle', 'Admin\ProductController@variationStatusToggle')->name('variation.color.status.toggle');
            Route::post('/variation/size/add', 'Admin\ProductController@variationSizeUpload')->name('variation.size.add');
            Route::get('/variation/{id}/size/remove', 'Admin\ProductController@variationSizeDestroy')->name('variation.size.delete');
            Route::post('/variation/image/add', 'Admin\ProductController@variationImageUpload')->name('variation.image.add');
            Route::post('/variation/image/remove', 'Admin\ProductController@variationImageDestroy')->name('variation.image.delete');
        });


        // address
        Route::prefix('address')->name('address.')->group(function () {
            Route::get('/', 'Admin\AddressController@index')->name('index');
            Route::post('/store', 'Admin\AddressController@store')->name('store');
            Route::get('/{id}/view', 'Admin\AddressController@show')->name('view');
            Route::post('/{id}/update', 'Admin\AddressController@update')->name('update');
            Route::get('/{id}/status', 'Admin\AddressController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\AddressController@destroy')->name('delete');
        });

        // faq
        Route::prefix('faq')->name('faq.')->group(function () {
            Route::get('/', 'Admin\FaqController@index')->name('index');
            Route::post('/store', 'Admin\FaqController@store')->name('store');
            Route::get('/{id}/view', 'Admin\FaqController@show')->name('view');
            Route::post('/{id}/update', 'Admin\FaqController@update')->name('update');
            Route::get('/{id}/status', 'Admin\FaqController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\FaqController@destroy')->name('delete');
        });

        // settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', 'Admin\SettingsController@index')->name('index');
            Route::post('/store', 'Admin\SettingsController@store')->name('store');
            Route::get('/{id}/view', 'Admin\SettingsController@show')->name('view');
            Route::post('/{id}/update', 'Admin\SettingsController@update')->name('update');
            Route::get('/{id}/status', 'Admin\SettingsController@status')->name('status');
            Route::get('/{id}/delete', 'Admin\SettingsController@destroy')->name('delete');
            Route::get('/bulkDelete', 'Admin\SettingsController@bulkDestroy')->name('bulkDestroy');
        });

        // order
        Route::prefix('order')->name('order.')->group(function () {
            Route::get('/', 'Admin\OrderController@index')->name('index');
            Route::post('/store', 'Admin\OrderController@store')->name('store');
            Route::get('/{id}/view', 'Admin\OrderController@show')->name('view');
            Route::post('/{id}/update', 'Admin\OrderController@update')->name('update');
            Route::get('/{id}/status/{status}', 'Admin\OrderController@status')->name('status');
        });

        // transaction
        Route::prefix('transaction')->name('transaction.')->group(function () {
            Route::get('/', 'Admin\TransactionController@index')->name('index');
            Route::get('/{id}/view', 'Admin\TransactionController@show')->name('view');
        });

        // target
        Route::prefix('target')->name('target.')->group(function () {
            Route::get('/', 'Admin\TargetController@index')->name('index');
            Route::get('/create', 'Admin\TargetController@create')->name('create');
            Route::post('/store', 'Admin\TargetController@store')->name('store');
            Route::get('/{id}/edit/', 'Admin\TargetController@edit')->name('edit');
            Route::get('/{id}/view', 'Admin\TargetController@show')->name('view');
            Route::post('/{id}/update', 'Admin\TargetController@update')->name('update');
            Route::get('/{id}/delete', 'Admin\TargetController@destroy')->name('delete');
            Route::get('/{id}/status', 'Admin\TargetController@status')->name('status');
        });
        // achievement
        Route::prefix('achievement')->name('achievement.')->group(function () {
            Route::get('/', 'Admin\AchievementController@index')->name('index');
        });
        //distributor create,edit,delete,order
        Route::prefix('distributor')->name('distributor.')->group(function () {
            Route::get('/list', 'Admin\DistributorController@index')->name('index');
            Route::get('/create', 'Admin\DistributorController@create')->name('create');
            Route::post('/store', 'Admin\DistributorController@store')->name('store');
            Route::get('/{id}/view', 'Admin\DistributorController@show')->name('view');
            Route::get('/{id}/edit', 'Admin\DistributorController@edit')->name('edit');
            Route::post('/{id}/update', 'Admin\DistributorController@update')->name('update');
            Route::get('/{id}/status', 'Admin\DistributorController@status')->name('status');
            Route::get('/{id}/verification', 'Admin\DistributorController@verification')->name('verification');
            Route::get('/{id}/delete', 'Admin\DistributorController@destroy')->name('delete');
            Route::get('/order', 'Admin\DistributorController@order')->name('order.index');
            Route::get('/{id}/order/details', 'Admin\DistributorController@orderdetail')->name('order.details');
            Route::get('/directory', 'Admin\AdminController@directory')->name('directory');
        });

        //retailer
        Route::prefix('retailer')->name('retailer.')->group(function () {
            Route::get('/list', 'Admin\RetailerController@index')->name('index');
            Route::get('/create', 'Admin\RetailerController@create')->name('create');
            Route::post('/store', 'Admin\RetailerController@store')->name('store');
            Route::get('/{id}/view', 'Admin\RetailerController@show')->name('view');
            Route::get('/{id}/edit', 'Admin\RetailerController@edit')->name('edit');
            Route::post('/{id}/update', 'Admin\RetailerController@update')->name('update');
            Route::get('/{id}/status', 'Admin\RetailerController@status')->name('status');
            Route::get('/{id}/verification', 'Admin\RetailerController@verification')->name('verification');
            Route::get('/{id}/delete', 'Admin\RetailerController@destroy')->name('delete');
            Route::get('/order', 'Admin\RetailerController@order')->name('order.index');
            Route::get('/{id}/order/details', 'Admin\RetailerController@orderdetail')->name('order.details');
            Route::prefix('invoice')->name('invoice.')->group(function () {
                Route::get('/{id}', 'Admin\RetailerController@invoice')->name('index');
            });
            // retailer-image
            Route::prefix('image')->name('image.')->group(function () {
                Route::get('/{id}', 'Admin\RetailerController@image')->name('index');
                Route::get('/{id}/{img}/delete', 'Admin\RetailerController@imagedelete')->name('delete');

        });
        });
            // retailer-invoice


        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', 'Admin\SaleController@sale')->name('index');

        });
        // sales report
        Route::name('sales.report.')->group(function () {
            Route::get('/sales/report', 'Admin\SaleController@index')->name('index');
            Route::get('/sales/report/detail', 'Admin\SaleController@detail')->name('detail');
        });


        });
});

Route::get('/home', 'HomeController@index')->name('home');
