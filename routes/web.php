<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PutusanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KeputusanController;
use App\Http\Controllers\PenilaianController;
use App\Http\Controllers\Kepala\StaffController;
use App\Http\Controllers\Sekretariat\StaffController as SekretariatStaffController;
use App\Http\Controllers\Sekretariat\PenilaianController as SekretariatPenilaianController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/photo-profile', [ProfileController::class, 'updatePhoto'])->name('profile.updatePhoto');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';

//router penilaian
Route::middleware(['auth', 'verified', 'role:HRD|Manager OPS|Staff|Admin'])->group(function () {

    Route::group(['prefix' => 'penilaian', 'as' => "Penilaian."], function () {
        Route::controller(PenilaianController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/list-data/karyawan', 'listkaryawan')->name('karyawan');
            Route::get('/tambah-data/penilaian', 'create')->name('create');
            Route::get('/edit-data/penilaian', 'edit')->name('edit');
            Route::get('/detail-data/penilaian', 'show')->name('show');
            Route::post('/store-data/penilaian', 'store')->name('store');
            Route::post('/store-penilaian-data/penilaian', 'storeUlang')->name('storeUlang');
            Route::put('/update-data/penilaian', 'update')->name('update');
            Route::delete('/hapus-data/penilaian', 'destroy')->name('destroy');
        });
    });


    Route::group(['prefix' => 'punishment-reward', 'as' => "Putusan."], function () {
        Route::controller(PutusanController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/detail-data/penilaian', 'show')->name('show');
        });
    });
});

//router Staff Departement HRD
Route::middleware(['auth', 'verified', 'role:HRD'])->group(function () {

    Route::group(['prefix' => 'karyawan', 'as' => "Kepala.staff."], function () {
        Route::controller(StaffController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/detail-data/karyawan', 'show')->name('show');
        });
    });
});


//router putusan
Route::middleware(['auth', 'verified'])->group(function () {

    Route::group(['prefix' => 'putusan', 'as' => "Keputusan."], function () {
        Route::controller(KeputusanController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/tambah-data/putusan', 'create')->name('create');
            Route::get('/edit-data/putusan', 'edit')->name('edit');
            Route::get('/detail-data/putusan', 'show')->name('show');
            Route::post('/store-data/putusan', 'store')->name('store');
            Route::put('/update-data/putusan', 'update')->name('update');
            Route::delete('/hapus-data/putusan', 'destroy')->name('destroy');
        });
    });
    Route::get('riwayat', [PenilaianController::class, 'riwayat'])->name('admin.riwayat.index');
    Route::get('riwayat/detail', [PenilaianController::class, 'riwayat_show'])->name('admin.riwayat.show');


});
//router Penilaian Untuk Kepala Sekeretariat
Route::middleware(['auth', 'verified', 'role:Manager OPS'])->group(function () {

    // Route::group(['prefix' => 'penilaian-karyawan', 'as' => "Sekretariat.penilaian."], function () {
    //     Route::controller(SekretariatPenilaianController::class)->group(function () {
    //         Route::get('/', 'index')->name('index');
    //         Route::get('/detail-data', 'show')->name('show');
    //     });
    // });
    Route::group(['prefix' => 'data-karyawan', 'as' => "Sekretariat.staff."], function () {
        Route::controller(SekretariatStaffController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/detail-data', 'show')->name('show');
        });
    });
});
