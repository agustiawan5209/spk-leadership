<?php

use App\Http\Controllers\Api\PenilaianController;
use App\Http\Controllers\Api\StaffController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('list/staff/{departement_id}', [StaffController::class,'getStaff'])->name('api.staff.list');
Route::get('penilaian/staff/', [PenilaianController::class,'create'])->name('api.staff.penilaian');
