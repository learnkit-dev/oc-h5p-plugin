<?php

use Illuminate\Support\Facades\App;

Route::group(['middleware' => ['web']], function () {
    if (config('laravel-h5p.use_router') == 'EDITOR' || config('laravel-h5p.use_router') == 'ALL') {
        Route::resource('h5p', "LearnKit\H5p\Http\H5pController");

        Route::group(['middleware' => ['auth']], function () {
            Route::get('library', "LearnKit\H5p\Http\LibraryController@index")->name('h5p.library.index');
            Route::get('library/show/{id}', "LearnKit\H5p\Http\LibraryController@show")->name('h5p.library.show');
            Route::post('library/store', "LearnKit\H5p\Http\LibraryController@store")->name('h5p.library.store');
            Route::delete('library/destroy', "LearnKit\H5p\Http\LibraryController@destroy")->name('h5p.library.destroy');
            Route::get('library/restrict', "LearnKit\H5p\Http\LibraryController@restrict")->name('h5p.library.restrict');
            Route::post('library/clear', "LearnKit\H5p\Http\LibraryController@clear")->name('h5p.library.clear');
        });

        // ajax
        Route::match(['GET', 'POST'], 'ajax/libraries', 'LearnKit\H5p\Http\AjaxController@libraries')->name('h5p.ajax.libraries');
        Route::get('ajax', 'LearnKit\H5p\Http\AjaxController')->name('h5p.ajax');
        Route::get('ajax/libraries', 'LearnKit\H5p\Http\AjaxController@libraries')->name('h5p.ajax.libraries');
        Route::get('ajax/single-libraries', 'LearnKit\H5p\Http\AjaxController@singleLibrary')->name('h5p.ajax.single-libraries');
        Route::post('ajax/content-type-cache', 'LearnKit\H5p\Http\AjaxController@contentTypeCache')->name('h5p.ajax.content-type-cache');
        Route::post('ajax/library-install', 'LearnKit\H5p\Http\AjaxController@libraryInstall')->name('h5p.ajax.library-install');
        Route::post('ajax/library-upload', 'LearnKit\H5p\Http\AjaxController@libraryUpload')->name('h5p.ajax.library-upload');
        Route::post('ajax/rebuild-cache', 'LearnKit\H5p\Http\AjaxController@rebuildCache')->name('h5p.ajax.rebuild-cache');
        Route::post('ajax/files', 'LearnKit\H5p\Http\AjaxController@files')->name('h5p.ajax.files');
        Route::get('ajax/finish', 'LearnKit\H5p\Http\AjaxController@finish')->name('h5p.ajax.finish');
        Route::post('ajax/content-user-data', 'LearnKit\H5p\Http\AjaxController@contentUserData')->name('h5p.ajax.content-user-data');
        Route::get('ajax/content-user-data', 'LearnKit\H5p\Http\AjaxController@contentUserData')->name('h5p.ajax.content-user-data');
    }

    Route::get('h5p/embed/{id}', 'LearnKit\H5p\Http\EmbedController');
    Route::get('h5p/export/{id}', 'LearnKit\H5p\Http\DownloadController')->name('h5p.export');
});

Route::prefix('api')->group(function () {
    Route::group(['middleware' => ['web']], function () {

        Route::get('h5p/dom/{id?}', '\LearnKit\H5p\Http\AjaxController@dom')->name('h5p.dom');

        Route::resource('h5p', "LearnKit\H5p\Http\H5pController");

        Route::match(['GET', 'POST'], 'ajax/libraries', 'LearnKit\H5p\Http\AjaxController@libraries')->name('h5p.ajax.libraries');
        Route::get('ajax', 'LearnKit\H5p\Http\AjaxController')->name('h5p.ajax');
        Route::get('ajax/libraries', 'LearnKit\H5p\Http\AjaxController@libraries')->name('h5p.ajax.libraries');
        Route::get('ajax/single-libraries', 'LearnKit\H5p\Http\AjaxController@singleLibrary')->name('h5p.ajax.single-libraries');
        Route::post('ajax/content-type-cache', 'LearnKit\H5p\Http\AjaxController@contentTypeCache')->name('h5p.ajax.content-type-cache');
        Route::post('ajax/library-install', 'LearnKit\H5p\Http\AjaxController@libraryInstall')->name('h5p.ajax.library-install');
        Route::post('ajax/library-upload', 'LearnKit\H5p\Http\AjaxController@libraryUpload')->name('h5p.ajax.library-upload');
        Route::post('ajax/rebuild-cache', 'LearnKit\H5p\Http\AjaxController@rebuildCache')->name('h5p.ajax.rebuild-cache');
        Route::post('ajax/files', 'LearnKit\H5p\Http\AjaxController@files')->name('h5p.ajax.files');
        Route::get('ajax/finish', 'LearnKit\H5p\Http\AjaxController@finish')->name('h5p.ajax.finish')->middleware('web');
        Route::post('ajax/content-user-data', 'LearnKit\H5p\Http\AjaxController@contentUserData')->name('h5p.ajax.content-user-data')->middleware('web');
        Route::get('ajax/content-user-data', 'LearnKit\H5p\Http\AjaxController@contentUserData')->name('h5p.ajax.content-user-data')->middleware('web');
        Route::post('ajax/translations', 'LearnKit\H5p\Http\AjaxController@getTranslations')->name('h5p.ajax.translation');

        Route::get('h5p/embed/{id}', 'LearnKit\H5p\Http\EmbedController')->name('h5p.embed');
        Route::get('h5p/export/{id}', 'LearnKit\H5p\Http\DownloadController')->name('h5p.export');


        Route::group(['middleware' => 'web'], function(){
            Route::post('ajax/finish', 'LearnKit\H5p\Http\AjaxController@finish')->name('h5p.ajax.finish');
        });


        Route::get('library', "LearnKit\H5p\Http\LibraryController@index")->name('h5p.library.index');
        Route::get('library/show/{id}', "LearnKit\H5p\Http\LibraryController@show")->name('h5p.library.show');
        Route::post('library/store', "LearnKit\H5p\Http\LibraryController@store")->name('h5p.library.store');
        Route::delete('library/destroy', "LearnKit\H5p\Http\LibraryController@destroy")->name('h5p.library.destroy');
        Route::get('library/restrict', "LearnKit\H5p\Http\LibraryController@restrict")->name('h5p.library.restrict');
        Route::post('library/clear', "LearnKit\H5p\Http\LibraryController@clear")->name('h5p.library.clear');

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

    return response('H5PIntegration = ' . json_encode($settings))
        ->header('Content-Type', 'application/javascript');
});

Route::get('/learnkit/h5p/embed/{id}', function ($contentId) {

});
