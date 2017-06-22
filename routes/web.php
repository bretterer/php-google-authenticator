<?php

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

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/profile', 'ProfileController@index')->name('profile')->middleware('auth');

Route::get('/profile/enroll', 'ProfileController@enroll')->name('profile.enroll')->middleware('auth');
Route::post('/profile/enroll', 'ProfileController@enrollVerify')->name('profile.enroll.verify')->middleware('auth');

Route::get('/login/challenge', 'Auth\LoginController@challenge');
Route::post('/login/challenge', ['uses'=>'Auth\LoginController@validateChallenge', 'as' => 'login.challenge']);