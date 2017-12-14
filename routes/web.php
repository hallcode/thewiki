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

Route::get('/', 'HomeController@index')->name('home');

Route::group(['prefix' => 'wiki'], function () {
    // Index
    Route::get('/', 'PageController@index')->name('page.list');

    // Special Pages
    Route::post('/Special:home', 'HomeController@index')->name('home.store');
    Route::get('/Special:home/edit', 'HomeController@edit')->name('home.edit');
    Route::post('/Special:home/edit', 'HomeController@store')->name('home.store');

    Route::get('/Special:random', 'SpecialController@random')->name('special.random');
    Route::get('/Special:needed', 'SpecialController@needed')->name('special.needed');
    Route::get('/Special:recent', 'SpecialController@recent')->name('special.recent');
    Route::get('/Special:all', 'PageController@index')->name('special.all');


    Route::get('/InfoBox:{reference}', 'InfoboxController@edit')->name('infobox.edit');
    Route::post('/InfoBox:{reference}', 'InfoboxController@save')->name('infobox.save');

    // Pages
    Route::get('/create', 'PageController@create')->name('page.create');
    Route::post('/create', 'PageController@store')->name('page.store');

    Route::get('/{reference}', 'PageController@show')->name('page.show');
    Route::get('/{reference}/create', 'PageController@create')->name('page.createWithReference');
    Route::get('/{reference}/edit', 'PageController@edit')->name('page.edit');
    Route::post('/{reference}', 'PageController@update')->name('page.update');
});

Route::group(['prefix' => 'settings', 'middleware' => 'auth'], function () {
    Route::get('/users', 'UserController@index')->name('set.users');
});

Route::group(['prefix' => 'ajax'], function () {
    // Infobox preview
    Route::post('/infobox', 'InfoboxController@preview');

    // Category
    Route::get('/category/search', 'Ajax\CategoryController@search');

    // Page Redirects
    Route::get('/redirect/{page}', 'Ajax\RedirectController@get');
    Route::post('/redirect/delete/{redirect}', 'Ajax\RedirectController@delete');
    Route::post('/redirect/{page}/{title}', 'Ajax\RedirectController@add');
});