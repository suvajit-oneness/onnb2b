<?php

// admin guard
Route::prefix('admin')->name('admin.')->group(function () {
    // auth
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

            // order invoice
            Route::prefix('invoice')->name('invoice.')->group(function () {
                Route::get('/{id}', 'Admin\RetailerController@invoice')->name('index');
            });

            // store image
            Route::prefix('image')->name('image.')->group(function () {
                Route::get('/{id}', 'Admin\RetailerController@image')->name('index');
                Route::get('/{id}/{img}/delete', 'Admin\RetailerController@imagedelete')->name('delete');
            });
        });

        // sales
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