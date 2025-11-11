<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CSVToPeopleController;

Route::get('/', function () {
    return view('welcome');
});

// CSV Endpoint
Route::post('/people/import', [CSVToPeopleController::class, 'import']);
