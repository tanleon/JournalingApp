<?php

use App\Http\Controllers\LabelController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [EntryController::class, 'index'])->name('entries.index')->middleware('auth');

Route::get('/login', [LoginController::class, 'index'])->name('login.index');
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.index');

Route::get('/register', [LoginController::class, 'register'])->name('login.register');
Route::post('/register', [LoginController::class, 'registerUser'])->name('login.register');

//User must be authenticated
Route::middleware('auth')->group(function () {

     Route::get('/logout', [LoginController::class, 'logout'])->name('login.logout');

     // Labels routes
     Route::post('labels', [LabelController::class, 'store'])->name('labels.store');
     Route::put('labels', [LabelController::class, 'update'])->name('labels.update');
     Route::resource('labels', LabelController::class)->only(['show']);
     Route::put('entries/{entry}/add_label', [LabelController::class, 'addLabel'])->name('entries.add_label');

     // Trash routes
     Route::get('trash', [EntryController::class, 'trash'])->name('entries.trash');
     Route::delete('send_trash/{entry}', [EntryController::class, 'sendTrash'])->name('entries.sendTrash');
     Route::post('/send_trash/{entry}', [EntryController::class, 'sendTrash'])->name('entries.sendTrash');
     Route::put('restore/{entry}', [EntryController::class, 'restore'])->name('entries.restore');
     Route::delete('empty_trash/', [EntryController::class, 'emptyTrash'])->name('entries.empty_trash');
     Route::get('entries/{entry}/trash', [EntryController::class, 'showReadOnly'])->name('entries.read_only');

     // Entries routes
     Route::post('entries/search', [EntryController::class, 'search'])->name('entries.search');
     Route::get('entries/search/query={search}', [EntryController::class, 'searchView'])->name('entries.searchView');
     Route::get('entries/{entry}/labels', [EntryController::class, 'showLabelsEdit'])->name('entries.show_labels');
     Route::get('entries/{entry}/make_copy', [EntryController::class, 'makeCopy'])->name('entries.make_copy');
     Route::post('/entries/{entry}/make-copy', [EntryController::class, 'makeCopy'])->name('entries.makeCopy');
     Route::post('/entries', [EntryController::class, 'store'])->name('entries.store');
     Route::resource('entries', EntryController::class)->except(["index", "edit"]);

     // User routes
     Route::get('profile', [UserController::class, 'profile'])->name('user.profile');
     Route::post('profile', [UserController::class, 'update'])->name('user.update');
});