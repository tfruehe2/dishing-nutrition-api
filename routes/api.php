<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('recipes')->group(function () {
    Route::get('/', [Api\RecipeController::class, 'index']);
    Route::post('/', [Api\RecipeController::class, 'store']);
    Route::get('/{recipe}', [Api\RecipeController::class, 'show']);
    Route::post('/{recipe}', [Api\RecipeController::class, 'update']);
    Route::get('/{recipe}/instructions', [Api\RecipeController::class, 'showInstructions']);
    Route::post('/{recipe}/instructions', [Api\RecipeController::class, 'updateInstructions']);
});


Route::prefix('ingredients')->group(function () {
    Route::get('/', [Api\IngredientController::class, 'index']);
    Route::post('/', [Api\IngredientController::class, 'store']);
    Route::get('/{ingredient}', [Api\IngredientController::class, 'show']);
    Route::post('/{ingredient}', [Api\IngredientController::class, 'update']);
});

Route::prefix('blogs')->group(function() {
    Route::get('/', [Api\BlogPostController::class, 'index']);
    Route::post('/', [Api\BlogPostController::class, 'store']);
    Route::get('/{blogPost}', [Api\BlogPostController::class, 'show']);
    Route::post('/{blogPost}', [Api\BlogPostController::class, 'update']);
});
