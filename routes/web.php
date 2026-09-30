<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/items', function () {
    return "Hola soy la ruta de items WEB";
});
