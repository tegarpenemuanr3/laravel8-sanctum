<?php

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//API route for register new user
Route::post('/register', [App\Http\Controllers\API\AuthController::class, 'register']);
//API route for login user
Route::post('/login', [App\Http\Controllers\API\AuthController::class, 'login']);

//Protecting Routes
Route::group(['middleware' => ['auth:sanctum', 'CekLevelAdmin::admin']], function () {
    Route::get('/profile', function (Request $request) {
        return auth()->user();
    });

    // Route::resource('programs', App\Http\Controllers\API\ProgramController::class);

    // API route for logout user
    Route::post('/logout', [App\Http\Controllers\API\AuthController::class, 'logout']);

    //Admin
    Route::post('/admin/logout', [App\Http\Controllers\API\AdminController::class, 'logout']);
    Route::post('/admin/update_profile/{id}', [App\Http\Controllers\API\AdminController::class, 'update_profile']);
    Route::post('/admin/ganti_password/{id}', [App\Http\Controllers\API\AdminController::class, 'ganti_password']);

    Route::get('/admin/all_umkm', [App\Http\Controllers\API\AdminController::class, 'all_umkm']);
    Route::get('/admin/umkm_register', [App\Http\Controllers\API\AdminController::class, 'umkm_register']);
    Route::get('/admin/umkm_banned', [App\Http\Controllers\API\AdminController::class, 'umkm_banned']);
    Route::get('/admin/umkm_aktif', [App\Http\Controllers\API\AdminController::class, 'umkm_aktif']);
    Route::post('/admin/update_status_umkm/{id}', [App\Http\Controllers\API\AdminController::class, 'update_status_umkm']);
});

Route::group(['middleware' => ['auth:sanctum', 'CekLevelKonsumen::konsumen']], function () {
    // Route::get('/profile', function (Request $request) {
    //     return auth()->user();
    // });

    // Route::resource('programs', App\Http\Controllers\API\ProgramController::class);
    Route::post('/menus/{id}', [App\Http\Controllers\API\MenuController::class, 'update']);

    Route::resource('keranjang', App\Http\Controllers\API\KeranjangController::class);
    Route::post('/keranjang/{id}', [App\Http\Controllers\API\KeranjangController::class, 'update']);

    // API route for logout user
    Route::post('/logoutKonsumen', [App\Http\Controllers\API\AuthController::class, 'logout']);
});

Route::resource('programs', App\Http\Controllers\API\ProgramController::class);

//Admin
Route::post('/admin/register', [App\Http\Controllers\API\AdminController::class, 'register']);
Route::post('/admin/login', [App\Http\Controllers\API\AdminController::class, 'login']);
Route::resource('admin', App\Http\Controllers\API\AdminController::class);
Route::post('/admin/{id}', [App\Http\Controllers\API\AdminController::class, 'update']);

Route::group(['middleware' => ['auth:sanctum', 'CekLevelUmkm::umkm']], function () {
    Route::resource('menus', App\Http\Controllers\API\MenuController::class);
});

Route::post('/umkm/register', [App\Http\Controllers\API\UmkmController::class, 'register']);
Route::post('/umkm/login', [App\Http\Controllers\API\UmkmController::class, 'login']);
Route::resource('umkm', App\Http\Controllers\API\UmkmController::class);
Route::post('/umkm/{id}', [App\Http\Controllers\API\UmkmController::class, 'update']);
