<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user()->with([
        'specialty' => function($query){
            $query->select(['id', 'name']);
        }
    ])->first()->toArray();
});

Route::prefix('auth')->group(function () {
    Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login'])->middleware('guest');
    Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register'])->middleware('guest');
    Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);
});

Route::group(['prefix' => 'recipes', 'as' => 'recipe.'], function (){
    Route::get('/all', [\App\Http\Controllers\Api\RecipeController::class, 'all'])->name('all');
    Route::get('/latests', [\App\Http\Controllers\Api\RecipeController::class, 'latests'])->name('latests');

    Route::group(['prefix' => 'items', 'as' => 'item.'], function (){
        Route::get('/categories', [\App\Http\Controllers\Api\RecipeController::class, 'recipe_item_categories'])->name('categories');
    });
});

Route::group(['prefix' => 'specialties', 'as' => 'specialty.'], function (){
    Route::get('/', [\App\Http\Controllers\Api\SpecialtyController::class, 'index'])->name('index');
});

Route::group(['prefix' => 'address', 'as' => 'address.'], function (){
    Route::get('/cities', [\App\Http\Controllers\Api\AddressController::class, 'cities'])->name('cities');
    Route::get('/districts/{city}', [\App\Http\Controllers\Api\AddressController::class, 'districts'])->name('districts');
    Route::get('/neighbourhoods/{city}/{district}', [\App\Http\Controllers\Api\AddressController::class, 'neighbourhoods'])->name('neighbourhoods');
})->middleware('auth:sanctum');

Route::group(['prefix' => 'account', 'as' => 'account.'], function (){
    Route::post('/update', [\App\Http\Controllers\Api\AccountController::class, 'update'])->name('update')->middleware('auth:sanctum');

    Route::group(['prefix' => 'recipes', 'as' => 'recipe.'], function (){
        Route::post('/', [\App\Http\Controllers\Api\RecipeController::class, 'store'])->name('store')->middleware('auth:sanctum');
        Route::get('/my', [\App\Http\Controllers\Api\RecipeController::class, 'my'])->name('my')->middleware('auth:sanctum');
        Route::post('/change-status', [\App\Http\Controllers\Api\RecipeController::class, 'change_status'])->name('change_status')->middleware('auth:sanctum');
    });

    Route::group(['prefix' => 'referral-links', 'as' => 'referral_link.'], function (){
        Route::get('/', [\App\Http\Controllers\Api\ReferralLinkController::class, 'my_codes'])->name('my_codes');
        Route::post('/verify-friend', [\App\Http\Controllers\Api\ReferralLinkController::class, 'verify_friend'])->name('verify_friend');
    });
})->middleware('auth:sanctum');
