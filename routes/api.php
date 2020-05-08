<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function($router){
    Route::post('login','AuthController@login');
    Route::post('logout','AuthController@logout');
    Route::post('refresh','AuthController@refresh');
    Route::post('me','AuthController@me');
    Route::post('register','AuthController@register');
});

Route::group(['middleware' => ['jwt.auth','admin']], function($router){

    //Customer
    Route::get('customers/all','CustomerController@all');
    Route::get('customers','CustomerController@list');
    Route::get('customers/{id}','CustomerController@index');
    Route::post('customers','CustomerController@edit');
    Route::post('customers/add','CustomerController@add');
    Route::post('customers/remove','CustomerController@remove');
    Route::post('customers/find','CustomerController@find');

    //Category
    Route::get('categories/all','CategoryController@all');
    Route::get('categories','CategoryController@list');
    Route::get('categories/{id}','CategoryController@index');
    Route::post('categories/add','CategoryController@add');
    Route::post('categories','CategoryController@edit');
    Route::post('categories/find','CategoryController@find');
    Route::post('categories/remove','CategoryController@remove');

    //Units
    Route::get('units/all','UnitController@all');
    Route::get('units','UnitController@list');
    Route::get('units/{id}','UnitController@index');
    Route::post('units/add','UnitController@add');
    Route::post('units','UnitController@edit');
    Route::post('units/find','UnitController@find');
    Route::post('units/remove','UnitController@remove');


    //Product
    Route::get('products/all','ProductController@all');
    Route::post('products/add','ProductController@add');
    Route::get('products','ProductController@list');
    Route::get('products/{id}','ProductController@index');
    Route::post('products','ProductController@edit');
    Route::post('products/find','ProductController@find');
    Route::post('products/remove','ProductController@remove');
    Route::post('products/import','ProductController@import');
    Route::get('products_quantity','ProductController@getQuantity');

    //Order
    Route::get('orders/{id}','OrderController@index');
    Route::post('orders/add1','OrderController@addWithCustomer');
    Route::post('orders/add2','OrderController@addWithNewCustomer');
    Route::post('orders/add3','OrderController@addWithoutCreateNewCustomer');
    Route::post('orders/edit1','OrderController@editWithCustomer');
    Route::post('orders/edit2','OrderController@editWithNewCustomer');
    Route::post('orders/edit3','OrderController@editWithoutCreateNewCustomer');
    Route::post('orders/find','OrderController@find');
    Route::post('orders/remove','OrderController@remove');

    //Statistic
    Route::get('statistics','StatisticController@profitAndLossStatement');
    Route::post('statistics/products','StatisticController@statisticProducts');

    //User
    Route::post('users','AuthController@edit');
});
