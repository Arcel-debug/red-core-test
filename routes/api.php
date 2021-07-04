<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user/login',                                              [UserController::class, 'login']);
Route::post('/user/register',                                           [UserController::class, 'register']);
Route::post('/user/logout',                                             [UserController::class, 'logout']);

Route::get( '/user/auth_check',                                         [UserController::class, 'auth_check']);
Route::get( '/user/get_roles',                                          [UserController::class, 'get_roles']);
Route::get( '/user/get_user_details',                                   [UserController::class, 'get_user_details']);

Route::get( '/user/records',                                            [UserController::class, 'records']);
Route::get( '/user/edit',                                               [UserController::class, 'edit']);
Route::post('/user/update',                                             [UserController::class, 'update']);
Route::post('/user/delete',                                             [UserController::class, 'delete']);

Route::get( '/role/records',                                            [RoleController::class, 'records']);
Route::get( '/role/edit',                                               [RoleController::class, 'edit']);
Route::post('/role/update',                                             [RoleController::class, 'update']);
Route::post('/role/delete',                                             [RoleController::class, 'delete']);