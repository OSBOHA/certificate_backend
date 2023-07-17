<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\PDFController;
use App\Http\Controllers\InstallFonts;


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

Route::get('install-fonts', [InstallFonts::class, 'index']);

Route::get('/', function () {
    return view('welcome');
});
Route::get('/certificate', function () {
    return view('certificate.page2');
});
Route::get('/confairm', function () {
    return view('confairnEmail');
});
Route::get('generate-pdf', [PDFController::class, 'generatePDF']);

