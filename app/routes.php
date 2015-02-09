<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('payform');
});
Route::get('/adm', function()
{
	return View::make('adm');
});
Route::get('/adm/edit', function()
{
	return View::make('editform');
});
Route::post('/adm/edit','PayController@update');
Route::post('/','PayController@commit');
Route::post('/adm/delete','PayController@delete');
Route::post('/adm/undelete','PayController@undelete');
