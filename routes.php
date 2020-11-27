<?php

use Illuminate\Support\Facades\App;

Route::group(['middleware' => ['web']], function () {
    if (config('laravel-h5p.use_router') == 'EDITOR' || config('laravel-h5p.use_router') == 'ALL') {
        Route::resource('h5p', "Kloos\H5p\Http\H5pController");
        Route::group(['middleware' => ['auth']], function () {
            //            Route::get('h5p/export', 'Djoudi\LaravelH5p\Http\H5pController@export')->name("h5p.export");

            Route::get('library', "Kloos\H5p\Http\LibraryController@index")->name('h5p.library.index');
            Route::get('library/show/{id}', "Kloos\H5p\Http\LibraryController@show")->name('h5p.library.show');
            Route::post('library/store', "Kloos\H5p\Http\LibraryController@store")->name('h5p.library.store');
            Route::delete('library/destroy', "Kloos\H5p\Http\LibraryController@destroy")->name('h5p.library.destroy');
            Route::get('library/restrict', "Kloos\H5p\Http\LibraryController@restrict")->name('h5p.library.restrict');
            Route::post('library/clear', "Kloos\H5p\Http\LibraryController@clear")->name('h5p.library.clear');
        });

        // ajax
        Route::match(['GET', 'POST'], 'ajax/libraries', 'Kloos\H5p\Http\AjaxController@libraries')->name('h5p.ajax.libraries');
        Route::get('ajax', 'Kloos\H5p\Http\AjaxController')->name('h5p.ajax');
        Route::get('ajax/libraries', 'Kloos\H5p\Http\AjaxController@libraries')->name('h5p.ajax.libraries');
        Route::get('ajax/single-libraries', 'Kloos\H5p\Http\AjaxController@singleLibrary')->name('h5p.ajax.single-libraries');
        Route::post('ajax/content-type-cache', 'Kloos\H5p\Http\AjaxController@contentTypeCache')->name('h5p.ajax.content-type-cache');
        Route::post('ajax/library-install', 'Kloos\H5p\Http\AjaxController@libraryInstall')->name('h5p.ajax.library-install');
        Route::post('ajax/library-upload', 'Kloos\H5p\Http\AjaxController@libraryUpload')->name('h5p.ajax.library-upload');
        Route::post('ajax/rebuild-cache', 'Kloos\H5p\Http\AjaxController@rebuildCache')->name('h5p.ajax.rebuild-cache');
        Route::post('ajax/files', 'Kloos\H5p\Http\AjaxController@files')->name('h5p.ajax.files');
        Route::get('ajax/finish', 'Kloos\H5p\Http\AjaxController@finish')->name('h5p.ajax.finish');
        Route::post('ajax/content-user-data', 'Kloos\H5p\Http\AjaxController@contentUserData')->name('h5p.ajax.content-user-data');
        Route::get('ajax/content-user-data', 'Kloos\H5p\Http\AjaxController@contentUserData')->name('h5p.ajax.content-user-data');
    }

    // export
    //    if (config('laravel-h5p.use_router') == 'EXPORT' || config('laravel-h5p.use_router') == 'ALL') {
    Route::get('h5p/embed/{id}', 'Kloos\H5p\Http\EmbedController');
    Route::get('h5p/export/{id}', 'Kloos\H5p\Http\DownloadController')->name('h5p.export');
    //    }
});

Route::prefix('api')->group(function () {
    Route::group(['middleware' => ['api']], function () {

        Route::get('h5p/dom/{id?}', '\Kloos\H5p\Http\AjaxController@dom')->name('h5p.dom');

        Route::resource('h5p', "Kloos\H5p\Http\H5pController");

        Route::match(['GET', 'POST'], 'ajax/libraries', 'Kloos\H5p\Http\AjaxController@libraries')->name('h5p.ajax.libraries');
        Route::get('ajax', 'Kloos\H5p\Http\AjaxController')->name('h5p.ajax');
        Route::get('ajax/libraries', 'Kloos\H5p\Http\AjaxController@libraries')->name('h5p.ajax.libraries');
        Route::get('ajax/single-libraries', 'Kloos\H5p\Http\AjaxController@singleLibrary')->name('h5p.ajax.single-libraries');
        Route::post('ajax/content-type-cache', 'Kloos\H5p\Http\AjaxController@contentTypeCache')->name('h5p.ajax.content-type-cache');
        Route::post('ajax/library-install', 'Kloos\H5p\Http\AjaxController@libraryInstall')->name('h5p.ajax.library-install');
        Route::post('ajax/library-upload', 'Kloos\H5p\Http\AjaxController@libraryUpload')->name('h5p.ajax.library-upload');
        Route::post('ajax/rebuild-cache', 'Kloos\H5p\Http\AjaxController@rebuildCache')->name('h5p.ajax.rebuild-cache');
        Route::post('ajax/files', 'Kloos\H5p\Http\AjaxController@files')->name('h5p.ajax.files');
        Route::get('ajax/finish', 'Kloos\H5p\Http\AjaxController@finish')->name('h5p.ajax.finish');
        Route::post('ajax/content-user-data', 'Kloos\H5p\Http\AjaxController@contentUserData')->name('h5p.ajax.content-user-data');
        Route::get('ajax/content-user-data', 'Kloos\H5p\Http\AjaxController@contentUserData')->name('h5p.ajax.content-user-data');
        Route::post('ajax/translations', 'Kloos\H5p\Http\AjaxController@getTranslations')->name('h5p.ajax.translation');

        Route::get('h5p/embed/{id}', 'Kloos\H5p\Http\EmbedController')->name('h5p.embed');
        Route::get('h5p/export/{id}', 'Kloos\H5p\Http\DownloadController')->name('h5p.export');

        Route::group(['middleware' => 'auth:api'], function(){
            Route::post('ajax/finish', 'Kloos\H5p\Http\AjaxController@finish')->name('h5p.ajax.finish');
        });

        Route::get('library', "Djoudi\LaravelH5p\Http\LibraryController@index")->name('h5p.library.index');
        Route::get('library/show/{id}', "Djoudi\LaravelH5p\Http\LibraryController@show")->name('h5p.library.show');
        Route::post('library/store', "Djoudi\LaravelH5p\Http\LibraryController@store")->name('h5p.library.store');
        Route::delete('library/destroy', "Djoudi\LaravelH5p\Http\LibraryController@destroy")->name('h5p.library.destroy');
        Route::get('library/restrict', "Djoudi\LaravelH5p\Http\LibraryController@restrict")->name('h5p.library.restrict');
        Route::post('library/clear', "Djoudi\LaravelH5p\Http\LibraryController@clear")->name('h5p.library.clear');

    });
});

Route::get('/h5pintegration-settings.js', function () {
    $h5p = App::make('LaravelH5p');
    $core = $h5p::$core;

    // Prepare form
    $library = 0;
    $parameters = '{}';

    $display_options = $core->getDisplayOptionsForEdit(null);

    // view Get the file and settings to print from
    $settings = $h5p::get_editor();

    return 'H5PIntegration = ' . json_encode($settings);
});
