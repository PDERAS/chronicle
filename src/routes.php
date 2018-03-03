<?php

namespace CodyMoorhouse\Secretary;

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth']], function() {
    $namespace = 'CodyMoorhouse\\Secretary\\';
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
        Route::get('/{section}', $controllers . 'SectionsController@get');
        Route::get('/', $controllers . 'SectionsController@index');
    });
});
