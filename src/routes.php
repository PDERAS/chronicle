<?php

namespace CodyMoorhouse\Chronicle;

use Illuminate\Support\Facades\Route;

Route::group([ 'namespace' => 'CodyMoorhouse\Chronicle\Controllers' ], function() {
    $routes = function () {
        Route::resource('/comments', 'CommentsController',
            [ 'only' => [ 'destroy', 'store', 'update' ]
        ]);

        Route::get('/media/{media}/download', 'MediaController@download');
        Route::resource('/media/{media}', 'MediaController', [
            'only' => ['destroy', 'store', 'update']
        ]);


        Route::get('/notes/{note}/files', 'NotesController@getMedia');
        Route::resource('/notes', 'NotesController',
            [ 'only' => [ 'destroy', 'store', 'update' ]
        ]);

        Route::group([ 'prefix' => 'sections' ], function() {
            Route::get('/{section_tag}/notes', 'SectionsController@getNotes');
            Route::get('/{section_tag}', 'SectionsController@get');
            Route::get('/', 'SectionsController@index');
        });
    };

    // API Routes
    Route::group([
        'middleware' => config('chronicle.middlewares.api'),
        'prefix' => config('chronicle.api_prefix')
    ], $routes);

    // Web Routes
    Route::group([
        'middleware' => config('chronicle.middlewares.auth')
    ], $routes);
});
