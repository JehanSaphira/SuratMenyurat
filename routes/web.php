<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DivisionController;
use App\Http\Controllers\Admin\DivisionAccountController;
use App\Http\Controllers\Admin\LetterTypeController;
use App\Http\Controllers\Admin\MonitoringController;
use App\Http\Controllers\Division\DashboardController as DivisionDashboardController;
use App\Http\Controllers\Division\OutgoingLetterController;
use App\Http\Controllers\Division\IncomingLetterController;

Route::get('/', function () {
    return redirect()->route('masuk');
});

Route::get('/masuk', [AuthController::class, 'showLogin'])->name('masuk');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::get('/rekap-surat', [AdminDashboardController::class, 'recap'])->name('recap');
    Route::patch('/divisions/{division}/activate', [DivisionController::class, 'activate'])->name('divisions.activate');
    Route::resource('divisions', DivisionController::class);
    Route::resource('accounts', DivisionAccountController::class)->parameters(['accounts' => 'account']);
    Route::resource('letter-types', LetterTypeController::class);
    Route::get('monitoring', [MonitoringController::class, 'index'])->name('monitoring.index');
});

Route::middleware(['auth', 'role:division'])->prefix('division')->name('division.')->group(function () {
    Route::get('/dashboard', [DivisionDashboardController::class, 'index'])->name('dashboard');
    Route::get('/rekap-surat', [DivisionDashboardController::class, 'recap'])->name('recap');
    Route::get('/outgoing', [OutgoingLetterController::class, 'index'])->name('outgoing.index');
    Route::get('/outgoing/create', [OutgoingLetterController::class, 'create'])->name('outgoing.create');
    Route::post('/outgoing', [OutgoingLetterController::class, 'store'])->name('outgoing.store');
    Route::get('/outgoing/{letter}', [OutgoingLetterController::class, 'show'])->name('outgoing.show');
    Route::get('/outgoing/{letter}/download', [OutgoingLetterController::class, 'download'])->name('outgoing.download');
    Route::delete('/outgoing/{letter}', [OutgoingLetterController::class, 'destroy'])->name('outgoing.destroy');

    Route::get('/incoming', [IncomingLetterController::class, 'index'])->name('incoming.index');
    Route::get('/incoming/{target}', [IncomingLetterController::class, 'show'])->name('incoming.show');
    Route::get('/incoming/{target}/download', [IncomingLetterController::class, 'download'])->name('incoming.download');
    Route::post('/incoming/{target}/approve', [IncomingLetterController::class, 'approve'])->name('incoming.approve');
    Route::post('/incoming/{target}/reject', [IncomingLetterController::class, 'reject'])->name('incoming.reject');
    Route::post('/incoming/{target}/reply', [IncomingLetterController::class, 'reply'])->name('incoming.reply');
});
