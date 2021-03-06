<?php

use Illuminate\Support\Facades\Route;

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Auth::routes();

/*
|------------------------------------------------------------------------------------
| Admin
|------------------------------------------------------------------------------------
*/
Route::group(['prefix' => ADMIN, 'as' => ADMIN . '.', 'middleware'=>['auth', 'Role:0']], function() {
    Route::get('/', 'DashboardController@index')->name('dash');
    Route::resource('users', 'UserController');
    Route::resource('category', 'CategoryController');
    Route::resource('article', 'ArticleController');

});

Route::get('/state-country/{id}', [
    'as' => 'state.from.country',
    'uses' => 'CountryStateController@getStateFromCountry'
])->where('id', '[0-9]+');


Route::get('/', [
    'as' => 'home',
    'uses' => 'HomeController@index'
]);

Route::get('/elfinder/tinymce4', [
    'uses' => 'ElfinderController@showTinyMCE4'
]);

Route::get('/elfinder/ckeditor', [
    'as' => 'elfinder.ckeditor',
    'uses' => 'ElfinderController@showCKeditor4'
]);
