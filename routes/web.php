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

Route::group(['middleware' => ['auth:user']], function () {
	Route::get('/', 'HomeController@home')->name('home');
	Route::get('/agents', 'HomeController@agents')->name('agents');
	Route::get('/mall', 'HomeController@mall')->name('mall');
	Route::get('/finances', 'HomeController@finances')->name('finances');
	Route::get('/service', 'HomeController@service')->name('service');

	Route::namespace('Agents')->prefix('agents')->group(function() {
		// 代理商
		Route::get('inactive', 'AgentsController@inactive')->name('agents_inactive');
		Route::get('code/sending', 'AgentsController@codeSending')->name('agents_code_sending');
		Route::get('codes', 'AgentsController@codes')->name('agents_codes');
		// 团队
		Route::get('teams/members', 'TeamsController@members')->name('teams_members');
		Route::get('teams/levels', 'TeamsController@levels')->name('teams_levels');
	});

	Route::namespace('Mall')->prefix('mall')->group(function() {
		// 商品
		Route::get('goods', 'GoodsController@index')->name('goods');
		// 订单
		Route::get('orders', 'OrdersController@index')->name('orders');
		// 购物车
		Route::get('carts', 'CartsController@index')->name('carts');
	});

	Route::namespace('Finances')->prefix('finances')->group(function() {
		// 奖金
		Route::get('bonus', 'BonusController@index')->name('finances_bouns');
		// 流水
		Route::get('flow/cost', 'FlowController@cost')->name('finances_flow_cost');
		Route::get('flow/charge', 'FlowController@charge')->name('finances_flow_charge');

		// 充值
		Route::get('charge', 'ChargeController@index')->name('finances_charge');
		Route::get('charge/records', 'ChargeController@records')->name('finances_charge_records');
	});

});

/// 邀请码
Route::get('/inviteCode', 'UserController@makeInviteCode')->name('makeInviteCode');
Route::post('/inviteCode', 'UserController@grantInviteCode')->name('grantInviteCode');
