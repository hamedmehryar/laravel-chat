<?php

Route::group(['prefix' => 'message'], function () {
    Route::get('/', ['as' => 'message', 'uses' => 'MessagesController@index']);
    Route::get('create', ['as' => 'message.create', 'uses' => 'MessagesController@create']);
    Route::post('/', ['as' => 'message.store', 'uses' => 'MessagesController@store']);
    Route::get('{id}', ['as' => 'message.show', 'uses' => 'MessagesController@show']);
    Route::put('{id}', ['as' => 'message.update', 'uses' => 'MessagesController@update']);
});
