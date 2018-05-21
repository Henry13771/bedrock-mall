<?php
/**
 * Created by PhpStorm.
 * User: wangzhen
 * Date: 2018/5/17
 * Time: 15:03
 */
Route::group(['prefix'=>'admin'], function(){
    Route::get('/order', '\App\Admin\Controllers\OrderController@index');
});