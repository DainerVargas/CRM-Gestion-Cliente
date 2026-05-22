<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth; // Added
use App\Livewire\Dashboard;
use App\Livewire\Auth\Login;
use App\Livewire\Users\Index as UsersIndex;

Route::get('/login', Login::class)->name('login')->middleware('guest'); // Added

Route::middleware(['auth'])->group(function () { // Added middleware group
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/dashboard', Dashboard::class);

    // Placeholder for other routes
    Route::get('/clientes', \App\Livewire\Clients\Index::class)->name('clients.index');
    Route::get('/clientes/{id}', \App\Livewire\Clients\Show::class)->name('clients.show');
    Route::get('/llamadas', \App\Livewire\Calls\Index::class)->name('calls.index');
    Route::get('/usuarios', UsersIndex::class)->name('users.index');
    Route::get('/mensajes-predeterminados', \App\Livewire\WhatsappTemplates\Index::class)->name('whatsapp-templates.index');
    Route::get('/perfil', \App\Livewire\Profile\Show::class)->name('profile.show');
    Route::post('/call-recordings', [\App\Http\Controllers\CallRecordingController::class, 'store'])->name('call-recordings.store');
    Route::get('/call-recordings/{id}', [\App\Http\Controllers\CallRecordingController::class, 'show'])->name('call-recordings.playback');

    // Sales and Settlement
    Route::get('/ventas-y-cierre', \App\Livewire\Admin\Sales\SettlementManager::class)->name('admin.sales.settlement');

    Route::post('/logout', function () { // Added logout route
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        return redirect('/login');
    })->name('logout');
});
