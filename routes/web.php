<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BaseController;

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

// Route::get('/', function () {
//     return view('welcome');
// });
// Route::get('/table', function () {
//     return view('table');
// })->name('table');


//******************************************** View *************************************************//
Route::get('/', [BaseController::class, 'index'])->name('index');
Route::POST('/table', [BaseController::class, 'dataTable'])->name('table');
Route::get('/step-one', [BaseController::class, 'getStepOne'])->name('getStepOne');
Route::get('/step-one/{token}', [BaseController::class, 'getStepOne'])->name('StepOne');
Route::get('/step-two/{token}', [BaseController::class, 'getStepTwo'])->name('getStepTwo');
Route::get('/step-three/{token}', [BaseController::class, 'getStepThree'])->name('getStepThree');
Route::get('/step-four/{token}', [BaseController::class, 'getStepFour'])->name('getStepFour');
Route::get('/edit-route/{token}', [BaseController::class, 'editRoute'])->name('editRoute');
//******************************************** Save *************************************************//
Route::post('/step-one', [BaseController::class, 'saveStepOne'])->name('saveStepOne');
Route::post('/saveStepTwo', [BaseController::class, 'saveStepTwo'])->name('saveStepTwo');
Route::post('/saveStepThree', [BaseController::class, 'saveStepThree'])->name('saveStepThree');
Route::get('/delete/{token}', [BaseController::class, 'deleteData'])->name('deleteData');



Route::POST('changeStatus', [BaseController::class,'changeStatus'])->name('changeStatus');
Route::POST('updateYear', [BaseController::class,'updateYear'])->name('updateYear');
Route::post('/search', [BaseController::class, 'search'])->name('search');
Route::post('/status', [BaseController::class, 'status'])->name('status');
Route::post('activeAll', [BaseController::class, 'activeAll'])->name('activeAll');
Route::post('inactiveAll', [BaseController::class, 'inactiveAll'])->name('inactiveAll');
Route::post('image', [BaseController::class, 'image'])->name('image');
Route::post('recordLimit', [BaseController::class, 'recordLimit'])->name('recordLimit');