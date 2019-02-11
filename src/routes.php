<?php

namespace CodyMoorhouse\Chronicle;

use Illuminate\Support\Facades\Route;

Route::group([ 'namespace' => 'CodyMoorhouse\Chronicle\Controllers' ], function() {

    // API Routes
    Route::middleware(config('chronicle.middlewares.api'))->group(function () {
        Route::resource('/comments', 'CommentsController', [ 'only' => [
            'destroy', 'store', 'update'
        ]]);


        Route::get('/notes/{note}/files', 'NotesController@getMedia');
        Route::resource('/notes', 'NotesController', [ 'only' => [
            'destroy', 'store', 'update'
        ]]);


        Route::get('/api/media/{media}/download', 'MediaController@download');
        Route::resource('/media', 'MediaController', [
            'parameters' => [
                'media' => 'media'
            ],
            'only' => [
                'destroy', 'store', 'update'
            ]
        ]);
    });

    // Web Routes
    Route::middleware(config('chronicle.middlewares.auth'))->group(function () {
        Route::get('/media/{media}/download', 'MediaController@download');
    });

    // General Routes
    Route::middleware(config('chronicle.middlewares.general'))->group(function () {
        Route::group([ 'prefix' => 'sections' ], function() {
            Route::get('/{section_tag}/notes', 'SectionsController@getNotes');
            Route::get('/{section_tag}', 'SectionsController@get');
            Route::get('/', 'SectionsController@index');
        });
    });
});
