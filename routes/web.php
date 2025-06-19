<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatStreamController;
use App\Http\Controllers\DrMartinStreamController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

Route::get('/chatbot', function () {
    return view('chatbot');
})->name('chatbot');

Route::post('/api/chat/stream', [ChatStreamController::class, 'stream']);

Route::get('/dr-martin', function () {
    return view('dr-martin');
})->name('dr-martin');

Route::post('/api/dr-martin/stream', [DrMartinStreamController::class, 'stream']);

require __DIR__.'/auth.php';
