<?php

use App\Http\Controllers\ArticleCategoriesController;
use App\Http\Controllers\ArticleCommentsController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/logout', [UserController::class, 'logout']);
    Route::get('/users', [UserController::class, 'getUsers'])->middleware('role_check:Admin');
    Route::post('/user/create', [UserController::class, 'createUser'])->middleware('role_check:Admin');
    Route::post('/user/update', [UserController::class, 'updateUser'])->middleware('role_check:Admin');
    Route::post('/user/delete', [UserController::class, 'deleteUser'])->middleware('role_check:Admin');

    Route::post('/article/create', [ArticleController::class, 'createArticle'])->middleware('role_check:Admin');
    Route::post('/article/update', [ArticleController::class, 'updateArticle'])->middleware('role_check:Admin');
    Route::post('/article/delete', [ArticleController::class, 'deleteArticle'])->middleware('role_check:Admin');
    
    Route::post('/articlecats/create', [ArticleCategoriesController::class, 'createArticleCategory'])->middleware('role_check:Admin');
    Route::post('/articlecats/update', [ArticleCategoriesController::class, 'updateArticleCategory'])->middleware('role_check:Admin');
    Route::post('/articlecats/delete', [ArticleCategoriesController::class, 'deleteArticleCategory'])->middleware('role_check:Admin');

    Route::post('/articlecomms/create', [ArticleCommentsController::class, 'createArticleComment']);
    Route::post('/articlecomms/update', [ArticleCommentsController::class, 'updateArticleComment']);
    Route::post('/articlecomms/delete', [ArticleCommentsController::class, 'deleteArticleComment']);
});
Route::get('/articles', [ArticleController::class, 'getArticles']);
Route::get('/article/{art_id}', [ArticleController::class, 'getArticleById']);

Route::get('/articlecats', [ArticleCategoriesController::class, 'getArticleCategories']);

Route::get('/articlecomms/{art_id}', [ArticleCommentsController::class, 'getArticleComments']);

Route::post('auth/login', [UserController::class, 'loginUser']);
Route::post('register', [UserController::class, 'createAccount']);
