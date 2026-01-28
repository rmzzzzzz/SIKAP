<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {return view('welcome');});
Route::get('/hadir', function () { return view('hadir'); });
