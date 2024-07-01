<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BackendApiController;


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

      Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
           return $request->user();
      });

      Route::get('{dept_id}/department_view/', [BackendApiController::class, 'department_view']);
      Route::get('{dept_id}/collor_view/', [BackendApiController::class,'collor_view']);
      Route::post('{dept_id}/contact_form', [BackendApiController::class,'contact_form']);

      Route::get('{dept_id}/category',[BackendApiController::class,'category_view']);
      Route::get('{dept_id}/category_nav',[BackendApiController::class,'category_nav']);
      Route::get('{dept_id}/category_side',[BackendApiController::class,'category_side']);
      Route::get('{dept_id}/sub_category/{category}',[BackendApiController::class,'sub_category']);

      Route::get('{dept_id}/news_highlight/{category}',[BackendApiController::class,'news_highlight_category']);
      Route::get('{dept_id}/news/{category}',[BackendApiController::class,'news_category']);
      Route::get('{dept_id}/news/{category}/{subcategory}',[BackendApiController::class,'news_subcategory']);
      Route::get('{dept_id}/news-details/{category}/{id}',[BackendApiController::class,'news_details_category']);
      Route::get('{dept_id}/news_highlight',[BackendApiController::class,'news_highlight']);
      Route::get('{dept_id}/latest_news',[BackendApiController::class,'latest_news']);
     
    




   
     



