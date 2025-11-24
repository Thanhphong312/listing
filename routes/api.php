<?php
use Illuminate\Support\Facades\Route;


Route::post('login', 'Auth\AuthController@token');
Route::post('login/social', 'Auth\SocialLoginController@index');
Route::post('logout', 'Auth\AuthController@logout');

Route::post('register', 'Auth\RegistrationController@index')->middleware('registration');

Route::group(['middleware' => ['guest', 'password-reset']], function () {
    Route::post('password/remind', 'Auth\Password\RemindController@index');
    Route::post('password/reset', 'Auth\Password\ResetController@index');
});

Route::group(['middleware' => ['auth', 'registration']], function () {
    Route::post('email/resend', 'Auth\VerificationController@resend');
    Route::post('email/verify', 'Auth\VerificationController@verify');
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('me', 'Profile\DetailsController@index');
    Route::patch('me/details', 'Profile\DetailsController@update');
    Route::patch('me/details/auth', 'Profile\AuthDetailsController@update');
    Route::post('me/avatar', 'Profile\AvatarController@update');
    Route::delete('me/avatar', 'Profile\AvatarController@destroy');
    Route::put('me/avatar/external', 'Profile\AvatarController@updateExternal');
    Route::get('me/sessions', 'Profile\SessionsController@index');

    Route::group(['middleware' => 'two-factor'], function () {
        Route::put('me/2fa', 'Profile\TwoFactorController@update');
        Route::post('me/2fa/verify', 'Profile\TwoFactorController@verify');
        Route::delete('me/2fa', 'Profile\TwoFactorController@destroy');
    });

    Route::get('stats', 'StatsController@index');

    Route::apiResource('users', 'Users\UsersController')->except('show');
    Route::get('users/{userId}', 'Users\UsersController@show');

    Route::post('users/{user}/avatar', 'Users\AvatarController@update');
    Route::put('users/{user}/avatar/external', 'Users\AvatarController@updateExternal');
    Route::delete('users/{user}/avatar', 'Users\AvatarController@destroy');

    Route::group(['middleware' => 'two-factor'], function () {
        Route::put('users/{user}/2fa', 'Users\TwoFactorController@update');
        Route::post('users/{user}/2fa/verify', 'Users\TwoFactorController@verify');
        Route::delete('users/{user}/2fa', 'Users\TwoFactorController@destroy');
    });

    Route::get('users/{user}/sessions', 'Users\SessionsController@index');

    Route::get('/sessions/{session}', 'SessionsController@show');
    Route::delete('/sessions/{session}', 'SessionsController@destroy');

    Route::apiResource('roles', 'Authorization\RolesController')->except('show');
    Route::get('/roles/{roleId}', 'Authorization\RolesController@show');

    Route::get("roles/{role}/permissions", 'Authorization\RolePermissionsController@show');
    Route::put("roles/{role}/permissions", 'Authorization\RolePermissionsController@update');

    Route::apiResource('permissions', 'Authorization\PermissionsController');

    Route::get('/settings', 'SettingsController@index');

    Route::get('/countries', 'CountriesController@index');
});
Route::any('/tiktok-webhook/{id}', 'WebhookController@index');
Route::any('/test-webhook/{id}', 'WebhookController@testwebhook');

Route::post('flashdeals/create', 'FlashDealController@createFlashDeal');
Route::post('flashdeals/product', 'FlashDealController@addProductToFlashDeal');
Route::prefix('metas')->as('metas.')->group(function () {
    Route::post('/update', 'MetaController@update')->name('update');
});

// Endpoint nhận webhook từ Telegram
Route::post('/telegram/webhook', 'TelegramWebhookController@handleWebhook');
Route::get('/telegram/set-webhook', 'TelegramWebhookController@setWebhook');
Route::get('/telegram/webhook-info', 'TelegramWebhookController@getWebhookInfo');
Route::get('/telegram/remove-webhook', 'TelegramWebhookController@removeWebhook');


Route::post('/etsy-crawler', 'EtsyController@getProduct');
Route::get('/crawls/{id}/images', 'EtsyController@getProductImages');
Route::get('/search', 'EtsyController@searchProducts')->name('searchProducts');

