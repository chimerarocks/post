<?php

Route::group([
	'prefix' => 'admin/posts', 
	'namespace' => 'ChimeraRocks\Post\Controllers',
	'as' => 'admin.posts.',
	'middleware' => ['web']
	], function() {
	Route::get('/', ['uses' => 'AdminPostController@index', 'as' => 'index']);
	Route::get('/create', ['uses' => 'AdminPostController@create', 'as' => 'create']);
	Route::post('/store', ['uses' => 'AdminPostController@store', 'as' => 'store']);
	Route::get('/edit/{id}', ['uses' => 'AdminPostController@edit', 'as' => 'edit']);
	Route::post('/update/{id}', ['uses' => 'AdminPostController@update', 'as' => 'update']);
});