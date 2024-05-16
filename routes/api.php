<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

use App\Http\Controllers\Simple;
Route::get('/data',[Simple::class,'index']);

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
Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/employee', [AuthController::class, 'employee']);
    Route::post('/account', [AuthController::class, 'account']);
    Route::post('/patient', [AuthController::class, 'patient']);
    Route::post('/medical_record', [AuthController::class, 'medical_record']);
    Route::post('/raylab', [AuthController::class, 'raylab']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/pharmacy', [AuthController::class, 'pharmacy']);
    Route::post('/pharmacist', [AuthController::class, 'pharmacist']);
    Route::post('/prescribe', [AuthController::class, 'prescribe']);
    Route::post('/dr_reports', [AuthController::class, 'dr_reports']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::get('/user-profile', [AuthController::class, 'userProfile']);

});
