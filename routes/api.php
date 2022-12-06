<?php

use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TransactionController;
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('school', SchoolController::class);

Route::apiResource('classroom', ClassroomController::class);
Route::post('classroom/batch', [ClassroomController::class, 'storeMany']);

Route::apiResource('employee', EmployeeController::class);
Route::post('employee/batch', [EmployeeController::class, 'storeMany']);

Route::apiResource('student', StudentController::class);
Route::post('student/batch', [StudentController::class, 'storeMany']);

Route::apiResource('transaction', TransactionController::class)->only('index', 'store');
Route::post('transaction/batch', [TransactionController::class, 'storeMany']);
