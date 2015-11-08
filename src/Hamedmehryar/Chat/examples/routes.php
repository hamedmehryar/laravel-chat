<?php

Route::group(['prefix' => 'message'], function () {
    Route::get('/', ['as' => 'message', 'uses' => 'MessageController@index']);
    Route::get('create', ['as' => 'message.create', 'uses' => 'MessageController@create']);
    Route::post('/', ['as' => 'message.store', 'uses' => 'MessageController@store']);
    Route::get('{id}', ['as' => 'message.show', 'uses' => 'MessageController@show']);
    Route::put('{id}', ['as' => 'message.update', 'uses' => 'MessageController@update']);
});
