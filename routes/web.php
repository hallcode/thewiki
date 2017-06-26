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

Auth::routes();

Route::get('/', 'HomeController@index');

Route::group(['prefix' => 'wiki'], function () {
    // Index
    Route::get('/', 'PageController@index');

    // Special Pages
    Route::get('/Special:home/edit', 'HomeController@edit')->name('home.edit');
    Route::post('/Special:home/edit', 'HomeController@store')->name('home.store');

    // Resources
    Route::get('/{namespace}:{reference}', 'WikiController@dispatcher')->name('resource');

    // Pages
    Route::get('/create', 'PageController@create')->name('page.create');
    Route::post('/create', 'PageController@store')->name('page.store');

    Route::get('/{reference}', 'PageController@show')->name('page.show');
    Route::get('/{reference}/create', 'PageController@create')->name('page.createWithReference');
    Route::get('/{reference}/edit', 'PageController@edit')->name('page.edit');
    Route::post('/{reference}', 'PageController@update')->name('page.update');
});

Route::group(['prefix' => 'ajax'], function () {
    Route::get('/category/search', 'Ajax\CategoryController@search');
});