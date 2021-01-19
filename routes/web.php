<?php

use App\Http\Controllers\FilesController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
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
    return view('welcome');
});

Route::get('/files', 'FilesController@returnFiLes');
Route::get('/v1_only', 'FilesController@return_v1_only'); 
Route::get('/v2_only', 'FilesController@return_v2_only'); 
Route::get('/common_and_different', 'FilesController@return_common_and_different'); 
Route::get('/common_and_same', 'FilesController@return_common_and_same'); 
Route::get('/artisan/storage', function() {
    $command = 'storage:link';
    $result = Artisan::call($command);
    return Artisan::output();
});

// Route::get('/files', 'FilesController@returnFiLes');