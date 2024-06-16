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

      Route::get('{dept_id}/notice/{category}',[BackendApiController::class,'notice_view']);
      Route::post('{dept_id}/contact_form', [BackendApiController::class,'contact_form']);
      Route::get('{dept_id}/member/{category}',[BackendApiController::class,'member_view']);
    




   
     



