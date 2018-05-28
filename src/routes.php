<?php

namespace CodyMoorhouse\Chronicle;

use Illuminate\Support\Facades\Route;

Route::group([ 'namespace' => 'CodyMoorhouse\Chronicle\Controllers', 'middleware' => config('chronicle.middlewares.general') ], function() {
    Route::group([ 'prefix' => 'sections' ], function() {
        Route::get('/{section}/notes', 'SectionsController@getNotes');
        Route::get('/{section}', 'SectionsController@get');
        Route::get('/', 'SectionsController@index');
    });
});

Route::group([ 'namespace' => 'CodyMoorhouse\Chronicle\Controllers', 'middleware' => config('chronicle.middlewares.auth') ], function() {
    Route::resource('/comments', 'CommentsController', [ 'only' => [
        'destroy', 'store', 'update'
    ]]);
    Route::resource('/media', 'MediaController', [
        'parameters' => [
            'media' => 'media'
        ],
        'only' => [
            'destroy', 'store', 'update'
        ]
    ]);
    Route::resource('/notes', 'NotesController', [ 'only' => [
        'destroy', 'store', 'update'
    ]]);
});
