<?php

use Illuminate\Support\Facades\Route;
use Filament\Http\Livewire\Auth\Login;

Route::middleware('auth')->group(function () {
});

// Route::get('/admin', [Login::class, 'render'])->name('admin.login');

Route::get('/', function () {
    return view('welcome');
});
