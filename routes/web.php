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


Route::get('/', function () {
    $a = \App\Libraries\Helpers::getStorage()->getDriver()->getAdapter()->getAdapter();
    $contents = collect(\App\Libraries\Helpers::getStorage()->listContents('/', true));
    $file = $contents
        ->where('type', '=', 'file')
        ->where('filename', '=', 'dump')
        ->where('extension', '=', 'jpg')
        ->first(); // có thể bị trùng tên file với nhau!
    print_r(\App\Libraries\Helpers::getStorage()->getMetadata('1hp-qJd4UUvt6k-Jy56em-ki4-x7aKVC7/1LqL4paEfUvWg1MFrvtXGIxYGOP4m0vsd'));exit;
//    print_r(\App\Libraries\Helpers::getStorage()->delete($file['basename']));exit;
    if($a instanceof \Hypweb\Flysystem\GoogleDrive\GoogleDriveAdapter){
        echo $a->delete('dmp.jpg');exit;
    }

    echo \App\Libraries\Helpers::getStorage()->getDriver()->getUrl('5b990cbfd5171_IMG_7889.jpg');exit;
    return view('front.home');
});

Route::get('/elfinder/tinymce4', [
    'uses' => 'ElfinderController@showTinyMCE4'
]);

Route::get('/elfinder/ckeditor', [
    'as' => 'elfinder.ckeditor',
    'uses' => 'ElfinderController@showCKeditor4'
]);
