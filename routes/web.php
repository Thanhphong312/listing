<?php
use Illuminate\Support\Facades\Route;
use Vanguard\Http\Controllers\Web\ImageController;
use Vanguard\Http\Controllers\Web\OverideDesignController;
use Vanguard\Http\Controllers\Web\ImageQrNewController;
use Vanguard\Http\Controllers\Web\ReportController;
use Vanguard\Http\Controllers\Api\EtsyController;

/**
 * Authentication
 */
// Route::get('/', 'Auth\LoginController@home')->name('home.index');

Route::any('/', 'Auth\LoginController@show')->name('auth.login');
Route::any('login', 'Auth\LoginController@login');
Route::get('logout', 'Auth\LoginController@logout')->name('auth.logout');

// Route::group(['middleware' => ['registration', 'guest']], function () {
//     Route::get('register', 'Auth\RegisterController@show')->name('auth.register');
//     Route::post('register', 'Auth\RegisterController@register');
// });

Route::emailVerification();

Route::group(['middleware' => ['password-reset', 'guest']], function () {
    Route::resetPassword();
});

/**
 * Two-Factor Authentication
 */
Route::group(['middleware' => 'two-factor'], function () {
    Route::get('auth/two-factor-authentication', 'Auth\TwoFactorTokenController@show')->name('auth.token');
    Route::post('auth/two-factor-authentication', 'Auth\TwoFactorTokenController@update')->name('auth.token.validate');
});

/**
 * Social Login
 */
Route::get('auth/{provider}/login', 'Auth\SocialAuthController@redirectToProvider')->name('social.login');
Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');

/**
 * Impersonate Routes
 */
Route::group(['middleware' => 'auth'], function () {
    Route::impersonate();
});

Route::group(['middleware' => ['auth', 'verified']], function () {

    /**
     * Dashboard
     */

    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/dashboard/ajax/AjaxOrderToday', 'AjaxController@AjaxOrderToday');
    Route::get('/dashboard/ajax/AjaxOrderYesterday', 'AjaxController@AjaxOrderYesterday');
    Route::get('/dashboard/ajax/AjaxOrderThisMonth', 'AjaxController@AjaxOrderThisMonth');
    Route::get('/dashboard/ajax/AjaxOrderLastMonth', 'AjaxController@AjaxOrderLastMonth');
    Route::get('/dashboard/ajax/ajaxChart', 'AjaxController@ajaxChart');
    Route::get('/dashboard/ajax/ajaxListOrders', 'AjaxController@ajaxListOrders');

    // Route::prefix('image')->as('image.')->group(function () {
    //     Route::get('/', [ImageController::class, 'index'])->name('home');
    //     Route::get('/design', [ImageController::class, 'design'])->name('make.design');
    //     Route::get('/testDesign', [ImageController::class, 'design'])->name('make.testDesign');
    //     Route::get('/convertpdf', [ImageController::class, 'convertLabel'])->name('make.convertlabel');

    // });


    /**
     * User Profile
     */

    Route::group(['prefix' => 'profile', 'namespace' => 'Profile'], function () {
        Route::get('/', 'ProfileController@show')->name('profile');
        Route::get('activity', 'ActivityController@show')->name('profile.activity');
        Route::put('details', 'DetailsController@update')->name('profile.update.details');

        Route::post('avatar', 'AvatarController@update')->name('profile.update.avatar');
        Route::post('avatar/external', 'AvatarController@updateExternal')
            ->name('profile.update.avatar-external');

        Route::put('login-details', 'LoginDetailsController@update')
            ->name('profile.update.login-details');

        Route::get('sessions', 'SessionsController@index')
            ->name('profile.sessions')
            ->middleware('session.database');

        Route::delete('sessions/{session}/invalidate', 'SessionsController@destroy')
            ->name('profile.sessions.invalidate')
            ->middleware('session.database');
    });

    /**
     * Two-Factor Authentication Setup
     */

    Route::group(['middleware' => 'two-factor'], function () {
        Route::post('two-factor/enable', 'TwoFactorController@enable')->name('two-factor.enable');

        Route::get('two-factor/verification', 'TwoFactorController@verification')
            ->name('two-factor.verification')
            ->middleware('verify-2fa-phone');

        Route::post('two-factor/resend', 'TwoFactorController@resend')
            ->name('two-factor.resend')
            ->middleware('throttle:1,1', 'verify-2fa-phone');

        Route::post('two-factor/verify', 'TwoFactorController@verify')
            ->name('two-factor.verify')
            ->middleware('verify-2fa-phone');

        Route::post('two-factor/disable', 'TwoFactorController@disable')->name('two-factor.disable');
    });



    /**
     * User Management
     */
    Route::resource('users', 'Users\UsersController')
        ->except('update')->middleware('permission:users.manage');

    Route::group(['prefix' => 'users/{user}', 'middleware' => 'permission:users.manage'], function () {
        Route::put('update/details', 'Users\DetailsController@update')->name('users.update.details');
        Route::put('update/login-details', 'Users\LoginDetailsController@update')
            ->name('users.update.login-details');

        Route::post('update/avatar', 'Users\AvatarController@update')->name('user.update.avatar');
        Route::post('update/avatar/external', 'Users\AvatarController@updateExternal')
            ->name('user.update.avatar.external');

        Route::get('sessions', 'Users\SessionsController@index')
            ->name('user.sessions')->middleware('session.database');

        Route::delete('sessions/{session}/invalidate', 'Users\SessionsController@destroy')
            ->name('user.sessions.invalidate')->middleware('session.database');

        Route::post('two-factor/enable', 'TwoFactorController@enable')->name('user.two-factor.enable');
        Route::post('two-factor/disable', 'TwoFactorController@disable')->name('user.two-factor.disable');
    });

    /**
     * Roles & Permissions
     */
    Route::group(['namespace' => 'Authorization'], function () {
        Route::resource('roles', 'RolesController')->except('show')->middleware('permission:roles.manage');

        Route::post('permissions/save', 'RolePermissionsController@update')
            ->name('permissions.save')
            ->middleware('permission:permissions.manage');

        Route::resource('permissions', 'PermissionsController')->middleware('permission:permissions.manage');
    });


    /**
     * Settings
     */

    Route::get('settings', 'SettingsController@general')->name('settings.general')
        ->middleware('permission:settings.general');

    Route::any('settings/general/edit/{id}', 'SettingsController@edit')->name('settings.general.edit')
        ->middleware('permission:settings.general');

    Route::any('settings/general/add', 'SettingsController@add')->name('settings.general.add')
        ->middleware('permission:settings.general');

    Route::get('settings/auth', 'SettingsController@auth')->name('settings.auth')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth', 'SettingsController@update')->name('settings.auth.update')
        ->middleware('permission:settings.auth');

    if (config('services.authy.key')) {
        Route::post('settings/auth/2fa/enable', 'SettingsController@enableTwoFactor')
            ->name('settings.auth.2fa.enable')
            ->middleware('permission:settings.auth');

        Route::post('settings/auth/2fa/disable', 'SettingsController@disableTwoFactor')
            ->name('settings.auth.2fa.disable')
            ->middleware('permission:settings.auth');
    }

    Route::post('settings/auth/registration/captcha/enable', 'SettingsController@enableCaptcha')
        ->name('settings.registration.captcha.enable')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth/registration/captcha/disable', 'SettingsController@disableCaptcha')
        ->name('settings.registration.captcha.disable')
        ->middleware('permission:settings.auth');

    Route::get('settings/notifications', 'SettingsController@notifications')
        ->name('settings.notifications')
        ->middleware('permission:settings.notifications');

    Route::post('settings/notifications', 'SettingsController@update')
        ->name('settings.notifications.update')
        ->middleware('permission:settings.notifications');

    /**
     * Activity Log
     */

    Route::get('activity', 'ActivityController@index')->name('activity.index')
        ->middleware('permission:users.activity');

    Route::get('activity/user/{user}/log', 'Users\ActivityController@index')->name('activity.user')
        ->middleware('permission:users.activity');

    /**
     * designs
     */

    Route::prefix('designs')->as('designs.')->group(function () {
        Route::get('/', 'DesignController@index')->name('index');
        Route::any('/add', 'DesignController@addFile')->name('addFile');
        Route::any('/addurl', 'DesignController@addurl')->name('addurl');
        Route::any('/show/{id}', 'DesignController@show')->name('show');
        Route::any('/edit/{id}', 'DesignController@edit')->name('edit');
        Route::any('/delete/{id}', 'DesignController@delete')->name('delete');
        Route::any('/upload', 'DesignController@upload')->name('upload');
        Route::any('/cenvert-design', 'DesignController@cenvert')->name('cenvert');
        Route::any('/gen', 'DesignController@gen')->name('gen');
        Route::any('/upload-show', 'DesignController@uploadShow')->name('uploadShow');
        Route::any('/edit-title', 'DesignController@updateTitle')->name('updateTitle');
        Route::any('/delete-image-idea', 'DesignController@deleteImage')->name('deleteImage');
        Route::any('/ajax-mockup', 'DesignController@ajaxMockup')->name('ajaxMockup');
        Route::any('/ajax-mockup-human', 'DesignController@ajaxMockupHuman')->name('ajaxMockupHuman');
        Route::any('/edit-category/{id}', 'DesignController@editCategory')->name('editCategory');
        Route::any('/change-number-side', 'DesignController@changeNumberDesignItem')->name('changeNumberDesignItem');
        Route::any('/create-product-design', 'DesignController@createProductDesign')->name('createProductDesign');
        Route::any('/genPositions', 'DesignController@genPositions')->name('genPositions');
        Route::any('/genPositionhumans', 'DesignController@genPositionhumans')->name('genPositionhumans');
        Route::any('/download/{id}', 'DesignController@download_image')->name('download');
        Route::any('/getDesignUrl', 'DesignController@getDesignUrl')->name('getDesignUrl');
        Route::any('/import-image', 'DesignController@import_design')->name('import_design');
        Route::any('/list-image/{id}', 'DesignController@list_image')->name('list_image');
        Route::any('/export-design', 'DesignController@export_design')->name('export_design');
        Route::any('/download-design/{id}', 'DesignController@download_design')->name('download_design');
    });

    Route::prefix('ideas')->as('ideas.')->group(function () {
        Route::get('/', 'IdeaController@index')->name('index');
        Route::any('/add', 'IdeaController@add')->name('add');
        Route::any('/show/{id}', 'IdeaController@show')->name('show');
        Route::any('/edit/{id}', 'IdeaController@edit')->name('update');
        Route::any('/delete/{id}', 'IdeaController@delete')->name('delete');
        Route::any('/upload', 'IdeaController@upload')->name('upload');
        Route::any('/edit-title', 'IdeaController@updateTitle')->name('updateTitle');
        Route::any('/edit-des', 'IdeaController@updateDescription')->name('updateDescription');
        Route::any('/delete-image-idea', 'IdeaController@deleteImageIdea')->name('deleteImageIdea');
    });

    Route::prefix('design-items')->as('designItems.')->group(function () {
        Route::get('/', 'DesignItemController@index')->name('index');
        Route::any('/add', 'DesignItemController@add')->name('add');
        Route::any('/show/{id}', 'DesignItemController@show')->name('show');
        Route::any('/edit/{id}', 'DesignItemController@edit')->name('edit');
        Route::any('/delete/{id}', 'DesignItemController@delete')->name('delete');
        Route::any('/upload', 'DesignItemController@upload')->name('upload');
        Route::any('/cenvert-design/{id}', 'DesignItemController@cenvert')->name('cenvert');
        Route::any('/gen', 'DesignItemController@gen')->name('gen');
        Route::any('/upload-show', 'DesignItemController@uploadShow')->name('uploadShow');
        Route::any('/edit-title', 'DesignItemController@updateTitle')->name('updateTitle');
        Route::any('/delete-image-idea', 'DesignItemController@deleteImage')->name('deleteImage');
        Route::any('/ajax-mockup', 'DesignItemController@ajaxMockup')->name('ajaxMockup');
        Route::any('/ajax-mockup-human', 'DesignItemController@ajaxMockupHuman')->name('ajaxMockupHuman');
        Route::any('/edit-category/{id}', 'DesignItemController@editCategory')->name('editCategory');
        Route::any('/change-number-side', 'DesignItemController@changeNumberDesignItem')->name('changeNumberDesignItem');
        Route::any('/create-product-design', 'DesignItemController@createProductDesign')->name('createProductDesign');
        Route::any('/genPositions', 'DesignItemController@genPositions')->name('genPositions');
        Route::any('/genPositionhumans', 'DesignItemController@genPositionhumans')->name('genPositionhumans');

    });
    //templetes
    Route::prefix('templates')->as('templates.')->group(function () {
        Route::get('/', 'TempleteController@index')->name('index');
        Route::any('/add', 'TempleteController@add')->name('add');
        Route::any('/testedit/{id}', 'TempleteController@test')->name('test');
        Route::any('/edit/{id}', 'TempleteController@edit')->name('edit');
        Route::any('/delete/{id}', 'TempleteController@delete')->name('delete');
        Route::any('/upload', 'TempleteController@upload')->name('upload');
        Route::any('/get-json', 'TempleteController@getjson')->name('getjson');
        Route::any('/duplicate/{id}', 'TempleteController@duplicate')->name('duplicate');
        Route::any('/choose-user/{id}', 'TempleteController@chooseUser')->name('chooseUser');
        Route::any('/accept-user/{id}', 'TempleteController@acceptUser')->name('acceptUser');
        Route::any('/setup', 'TempleteController@setup')->name('setup');

    });

    Route::prefix('templates2')->as('templates2.')->group(function () {
        Route::get('/', 'TempleteNewController@index')->name('index');
        Route::any('/add', 'TempleteNewController@add')->name('add');
        Route::any('/testedit/{id}', 'TempleteNewController@test')->name('test');
        Route::any('/edit/{id}', 'TempleteNewController@edit')->name('edit');
        Route::any('/delete/{id}', 'TempleteNewController@delete')->name('delete');
        Route::any('/upload', 'TempleteNewController@upload')->name('upload');
        Route::any('/get-json', 'TempleteNewController@getjson')->name('getjson');
        Route::any('/duplicate/{id}', 'TempleteNewController@duplicate')->name('duplicate');
        Route::any('/choose-user/{id}', 'TempleteNewController@chooseUser')->name('chooseUser');
        Route::any('/accept-user/{id}', 'TempleteNewController@acceptUser')->name('acceptUser');
        Route::any('/setup', 'TempleteNewController@setup')->name('setup');

    });
    Route::prefix('design-metas')->as('designMetas.')->group(function () {
        Route::get('/', 'DesignMetaController@index')->name('index');
        Route::any('/add', 'DesignMetaController@add')->name('add');
        Route::any('/edit/{id}', 'DesignMetaController@edit')->name('edit');
        Route::any('/delete/{id}', 'DesignMetaController@delete')->name('delete');
    });

    Route::prefix('category')->as('categories.')->group(function () {
        Route::get('/', 'CategoryController@index')->name('index');
        Route::any('/add', 'CategoryController@add')->name('add');
        Route::any('/edit/{id}', 'CategoryController@edit')->name('edit');
        Route::any('/delete/{id}', 'CategoryController@delete')->name('delete');
    });

    Route::prefix('colors')->as('colors.')->group(function () {
        Route::get('/', 'ColorController@index')->name('index');
        Route::any('/add', 'ColorController@add')->name('add');
        Route::any('/edit/{id}', 'ColorController@edit')->name('edit');
        Route::any('/delete/{id}', 'ColorController@delete')->name('delete');
    });

    //team
    Route::prefix('teams')->as('teams.')->group(function () {
        Route::get('/', 'TeamController@index')->name('index');
        Route::any('/add', 'TeamController@add')->name('add');
        Route::any('/store', 'TeamController@store')->name('store');
        Route::any('/view/{id}', 'TeamController@view')->name('view');
        Route::any('/update/{id}', 'TeamController@update')->name('update');
        Route::any('/delete/{id}', 'TeamController@delete')->name('delete');
        Route::any('/choose-user/{id}', 'TeamController@chooseUser')->name('chooseUser');
        Route::any('/accept-user/{id}', 'TeamController@acceptUser')->name('acceptUser');
    });
    //partnerapp
    Route::prefix('partner-apps')->as('partner-apps.')->group(function () {
        Route::get('/', 'PartnerAppController@index')->name('index');
        Route::any('/add', 'PartnerAppController@add')->name('add');
        Route::any('/edit/{id}', 'PartnerAppController@edit')->name('edit');
        Route::any('/delete/{id}', 'PartnerAppController@delete')->name('delete');
        Route::any('/check-proxy/{id}', 'PartnerAppController@checkproxy')->name('checkproxy');
    });
    //proxy
    Route::prefix('proxies')->as('proxies.')->group(function () {
        Route::get('/', 'ColorController@index')->name('index');
        Route::any('/add', 'ColorController@add')->name('add');
        Route::any('/edit/{id}', 'ColorController@edit')->name('edit');
        Route::any('/delete/{id}', 'ColorController@delete')->name('delete');
    });

    //products
    Route::prefix('products')->as('products.')->group(function () {
        Route::get('/', 'ProductController@index')->name('index');
        Route::any('/add', 'ProductController@add')->name('add');
        Route::any('/edit/{id}', 'ProductController@edit')->name('edit');
        Route::any('/delete/{id}', 'ProductController@delete')->name('delete');
        Route::any('/delete-multi', 'ProductController@deleteMulti')->name('deleteMulti');
        Route::any('/duplicate/{id}', 'ProductController@duplicate')->name('duplicate');
        Route::any('/view-mockup/{id}', 'ProductController@viewmockup')->name('view-mockup');
        Route::any('/view-store', 'ProductController@viewstore')->name('view-store');
        Route::any('/post-to-store', 'ProductController@postToStore')->name('post-to-store');
        Route::any('/post-to-store-tiktok', 'ProductController@postToStoreTiktok')->name('post-to-store-tiktok');
        Route::any('/upload-image-color', 'ProductController@uploadimagecolor')->name('upload-image-color');
        Route::any('/get-attributes', 'ProductController@getAttributes')->name('get-attributes');
        Route::any('/product-template', 'ProductController@addtemplate')->name('addtemplate');
        Route::any('/update-image-order', 'ProductController@updateImageOrder')->name('updateImageOrder');
        Route::any('/showstore/{id}', 'ProductController@showstore')->name('showstore');
        Route::get('/ajax/{id}', 'ProductController@ajax')->name('ajax');
        Route::post('/generate-titles', 'ProductController@generateTitles')->name('generate-titles');
        Route::any('/multi-duplicate', 'ProductController@multiDuplicate')->name('multiDuplicate');
    });

    //reports
    Route::prefix('reports')->as('report.')->group(function () {
        Route::get('/generate', [ReportController::class, 'index'])->name('index');
        Route::any('/all', [ReportController::class, 'reportall'])->name('reportall');
        Route::post('/orders', [ReportController::class, 'getOrderReport'])->name('orders');
        Route::get('/sellers', [ReportController::class, 'seller'])->name('sellers');
        Route::any('/payouts', [ReportController::class, 'payout'])->name('payouts');
        Route::get('/report-staff', [ReportController::class, 'reportstaff'])->name('reportstaff');
        Route::get('/report-payout', [ReportController::class, 'reportpayout'])->name('reportpayout');
    });

    //stores
    Route::prefix('stores')->as('stores.')->group(function () {
        Route::get('/', 'StoreController@index')->name('index');
        Route::any('/add', 'StoreController@add')->name('add');
        Route::any('/edit/{id}', 'StoreController@edit')->name('edit');
        Route::any('/delete/{id}', 'StoreController@delete')->name('delete');
        Route::any('/syncStoreSupover/{id}', 'StoreController@syncStoreSupover')->name('syncStoreSupover');
        Route::get('/ajax/{id}', 'StoreController@ajax')->name('ajax');
        Route::any('/syncName/{id}', 'StoreController@syncName')->name('syncName');
        Route::any('/changeStatus/{id}', 'StoreController@changeStatus')->name('changeStatus');
        Route::any('/changeCron/{id}', 'StoreController@changeCron')->name('changeStatus');
    });

    //store products
    Route::prefix('storeproducts')->as('storeproducts.')->group(function () {
        Route::get('/', 'StoreProductController@index')->name('index');
        Route::any('/add', 'StoreProductController@add')->name('add');
        Route::any('/edit/{id}', 'StoreProductController@edit')->name('edit');
        Route::any('/show/{id}', 'StoreProductController@show')->name('show');
        Route::any('/delete/{id}', 'StoreProductController@delete')->name('delete');
        Route::any('/post-to-tiktok', 'StoreProductController@postToTiktok')->name('post-to-tiktok');
        Route::any('/delete-product-tiktok', 'StoreProductController@deleteProductTiktok')->name('delete-product-tiktok');
        Route::any('/sync-quality-product', 'StoreProductController@syncQualityProduct')->name('syncQualityProduct');
        Route::any('/check-product-listing', 'StoreProductController@checkproductlisting')->name('checkproductlisting');

    });

    //imagemeta
    Route::prefix('images')->as('images.')->group(function () {
        Route::get('/', 'MetaImageController@index')->name('index');
        Route::any('/add', 'MetaImageController@add')->name('add');
        Route::any('/edit/{id}', 'MetaImageController@edit')->name('edit');
        Route::any('/delete/{id}', 'MetaImageController@delete')->name('delete');
        Route::any('/upload', 'MetaImageController@upload')->name('upload');

    });
    //flashdeals
    Route::prefix('flashdeals')->as('flashdeals.')->group(function () {
        Route::get('/', 'FlashDealController@index')->name('index');
        Route::get('/store/{id}', 'FlashDealController@show')->name('store');
        Route::get('/show/{id}', 'FlashDealController@showproduct')->name('showproduct');
        Route::get('/ajax/{id}', 'FlashDealController@ajax')->name('ajax');
        Route::any('/add', 'FlashDealController@add')->name('add');
        Route::any('/edit/{id}', 'FlashDealController@edit')->name('edit');
        Route::any('/delete/{id}', 'FlashDealController@delete')->name('delete');
        Route::any('/upload', 'FlashDealController@upload')->name('upload');
        Route::any('/sync-flash-deal/{id}', 'FlashDealController@sync_flashdeal')->name('sync_flashdeal');
        Route::any('/sync-all-flash-deal', 'FlashDealController@sync_all_flashdeal')->name('sync_all_flashdeal');
        Route::any('/upload', 'FlashDealController@upload')->name('upload');
        Route::any('/get-all-product', 'FlashDealController@getallproduct')->name('get-all-product');
        Route::any('/get-all-product-tiktok', 'FlashDealController@getallproducttiktok')->name('get-all-product-tiktok');
        Route::any('/sync-all-product-store/{id}', 'FlashDealController@sync_product_store')->name('sync-all-product-store');
        Route::any('/sync-product-flashdeal', 'FlashDealController@sync_product_flashdeal')->name('sync-product-flashdeal');
        Route::any('/ajax-detail/{id}', 'FlashDealController@ajaxdetail')->name('ajax-detail-flashdeals');
        Route::any('/post-product-flashdeals', 'FlashDealController@postproductflashdeals')->name('postproductflashdeals');
        Route::any('/re-post-product-flashdeals', 'FlashDealController@repostproductflashdeals')->name('repostproductflashdeals');
        Route::any('/change-status-fld/{id}', 'FlashDealController@changeStatusFld')->name('changeStatusFld');
        Route::any('/change-renew-fld/{id}', 'FlashDealController@changeRenewFld')->name('changeRenewFld');
        Route::any('/count-product-shop/{store_id}', 'FlashDealController@countproductshop')->name('countproductshop');
        Route::any('/deactiveflashdeal/{store_id}', 'FlashDealController@deactiveflashdeal')->name('deactiveflashdeal');
        Route::any('/changediscountproduct', 'FlashDealController@changediscountproduct')->name('changediscountproduct');
        Route::any('/edit-all-product-tiktok', 'FlashDealController@editallproducttiktok')->name('editallproducttiktok');
        Route::any('/edit-all-product-flashdeal', 'FlashDealController@editallproductflashdeal')->name('editallproductflashdeal');
        Route::any('/changepriority', 'FlashDealController@changepriority')->name('changepriority');

    });
    Route::get('/new/{id}', 'TiktokController@test');
    Route::get('/fld', 'FlashDealController@fldrenew');

    //orders
    Route::prefix('orders')->as('orders.')->group(function () {
        Route::get('/', 'OrderController@index')->name('index');
        Route::get('/ajax-total', 'OrderController@ajaxtotalorder')->name('ajaxtotalorder');

    });
    //payouts
    Route::prefix('payouts')->as('payouts.')->group(function () {
        Route::get('/', 'PayoutController@index')->name('index');
        Route::get('/ajax-total', 'PayoutController@ajaxtotalpayout')->name('ajaxtotalpayout');

    });
    //get order
    Route::prefix('order-tiktoks')->as('order-tiktok.')->group(function () {
        Route::get('/', 'GetOrderTiktokController@index')->name('index');
    });
});
Route::get('test', "TestController@test");

Route::get('api/list-order-pending', "TestController@listOrderPending");
// Route::get('test10', "TestController@test10");

/**
 * Installation
 */

Route::group(['prefix' => 'install'], function () {
    Route::get('/', 'InstallController@index')->name('install.start');
    Route::get('requirements', 'InstallController@requirements')->name('install.requirements');
    Route::get('permissions', 'InstallController@permissions')->name('install.permissions');
    Route::get('database', 'InstallController@databaseInfo')->name('install.database');
    Route::get('start-installation', 'InstallController@installation')->name('install.installation');
    Route::post('start-installation', 'InstallController@installation')->name('install.installation');
    Route::post('install-app', 'InstallController@install')->name('install.install');
    Route::get('complete', 'InstallController@complete')->name('install.complete');
    Route::get('error', 'InstallController@error')->name('install.error');
});

Route::any('flashdeals/addFlashdeal', 'FlashDealController@addFlashdeal')->name('addFlashdeal');


Route::prefix('metas')->as('metas.')->group(function () {
    Route::post('/update', 'MetaController@update')->name('update');
});


Route::get('/saved-crawls', 'EtsyController@listProducts')->name('crawls.view');
Route::prefix('etsy-crawler')->group(function () {
    Route::get('/', 'EtsyController@index')->name('etsy-crawler');
    Route::post('/save-product', 'EtsyController@store');
});

