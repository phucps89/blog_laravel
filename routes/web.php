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
    Route::resource('farm_breed_crop', 'FarmBreedCropController');
    Route::resource('category', 'CategoryController');

    Route::resource('farm', 'FarmController');
    Route::get('farm/add-breed-crop/{id}', [
        'as' => 'farm.add.breed-crop',
        'uses' => 'FarmController@addBreedCrop'
    ]);
    Route::post('farm/add-breed-crop/{id}', [
        'as' => 'farm.add.breed-crop',
        'uses' => 'FarmController@postAddBreedCrop'
    ]);
    Route::get('farm/store-breed-crop/{id}', [
        'as' => 'farm.store.breed-crop',
        'uses' => 'FarmController@storeBreedCrop'
    ]);

    Route::resource('breed_crop', 'BreedCropController');
    Route::resource('group_breed_crop', 'GroupBreedCropController');
    Route::post('group_breed_crop/generate-breed-crop', [
        'as' => 'group.generate.breed-crop',
        'uses' => 'GroupBreedCropController@autoGenerateBreedCrop'
    ]);
    Route::get('/generate/qrcode/{code}', [
        'as' => 'generate.qrcode',
        'uses' => 'BarcodeController@adminGenerateQrCode'
    ]);
});

Route::get('/state-country/{id}', [
    'as' => 'state.from.country',
    'uses' => 'CountryStateController@getStateFromCountry'
])->where('id', '[0-9]+');


Route::get('/', function () {
    return view('front.home');
});


