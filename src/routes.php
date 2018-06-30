<?php

namespace CodyMoorhouse\Chronicle;

use Illuminate\Support\Facades\Route;

Route::group([ 'namespace' => 'CodyMoorhouse\Chronicle\Controllers' ], function() {
    Route::group([ 'prefix' => 'sections' ], function() {
        Route::get('/{section_tag}/notes', 'SectionsController@getNotes');
        Route::get('/{section_tag}', 'SectionsController@get');
        Route::get('/', 'SectionsController@index');
    });
});

Route::group([ 'namespace' => 'CodyMoorhouse\Chronicle\Controllers' ], function() {
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

    Route::get('/notes/{note}/files', 'NotesController@getMedia');
    Route::resource('/notes', 'NotesController', [ 'only' => [
        'destroy', 'store', 'update'
    ]]);
});
