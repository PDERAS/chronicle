<?php

namespace CodyMoorhouse\Chronicle;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth']], function() {
    $namespace = 'CodyMoorhouse\\Chronicle\\';
    $controllers = $namespace . 'Controllers\\';

    Route::resource('/comments', $controllers . 'CommentsController', [ 'only' => [
        'destroy', 'store', 'update'
    ]]);
    Route::resource('/media', $controllers . 'MediaController', [
        'parameters' => [
            'media' => 'media'
        ],
        'only' => [
            'destroy', 'store', 'update'
        ]
    ]);
    Route::resource('/notes', $controllers . 'NotesController', [ 'only' => [
        'destroy', 'store', 'update'
    ]]);

    Route::group([ 'prefix' => 'sections' ], function() use ($controllers) {
        Route::get('/{section}/notes', $controllers . 'SectionsController@getNotes');
        Route::get('/{section}', $controllers . 'SectionsController@get');
        Route::get('/', $controllers . 'SectionsController@index');
    });
});
