<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


    Route::group(['domain'=>'www.hankele.cn'], function() {
        //return view('welcome');
        Route::get('/test', 'Wxapi\IndexController@test');
    });

    Route::group(['domain'=>'api.hankele.cn'], function() {

        Route::get('/', function() {
            return view('welcome');
        });
        Route::get('/VideoIndex', 'Wxapi\VideoIndexController@index');
        Route::get('/VideoInfo/{id}', 'Wxapi\VideoInfoController@index');
    });
