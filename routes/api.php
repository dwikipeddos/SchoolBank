<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClassroomController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SchoolController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Spatie\Activitylog\Models\Activity;

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
Route::get('logs', fn () => Activity::all());

Route::get('role', [RoleController::class, "index"]);
Route::put('role/assign', [RoleController::class, 'assign']);
Route::put('role/revoke', [RoleController::class, 'revoke']);

Route::apiResource('classroom', ClassroomController::class);
Route::post('classroom/batch', [ClassroomController::class, 'storeMany']);

Route::apiResource('employee', EmployeeController::class);
Route::get('employee/nip/{nip}', [EmployeeController::class, 'showByNip']);
Route::post('employee/batch', [EmployeeController::class, 'storeMany']);

Route::apiResource('student', StudentController::class);
Route::get('student/nis/{nis}', [StudentController::class, 'showByNis']);
Route::post('student_batch', [StudentController::class, 'storeMany']);

Route::apiResource('transaction', TransactionController::class)->only('index');
Route::post('transaction/{user}', [TransactionController::class, 'store']);
Route::post('transaction_batch', [TransactionController::class, 'storeMany']);
Route::post('transaction_count', [TransactionController::class, 'totalReport']);
Route::get('transaction_total', [TransactionController::class, 'activeWalletReport']);
Route::get('transaction_weekly', [TransactionController::class, 'weeklyReport']);
Route::get('transaction_highest', [TransactionController::class, 'highestReport']);


Route::prefix('auth')->group(function () {
    Route::post('login', [AuthController::class, 'auth']);
    Route::delete('logout', [AuthController::class, 'logout']);
});

Route::get('ola', fn () => "ola");
