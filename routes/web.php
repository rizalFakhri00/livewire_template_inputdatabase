<?php

use App\Livewire\Counter;
use App\Livewire\MergeGame;
use App\Livewire\RngTest;
use App\Livewire\Users;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/counter', Counter::class);
// Route::get('/mergegame', MergeGame::class);
Route::get('/users', Users::class);
