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
Route::namespace('Auth')->prefix('auth')->group(function() {
	Route::get('login', 'LoginController@index')->name('login');
	Route::post('login/submit', 'LoginController@submit')->name('login_submit');
	Route::get('logout', 'LoginController@cancel')->name('logout');
	Route::get('register', 'RegisterController@index')->name('register');
	Route::get('contract', 'RegisterController@contract')->name('contract');
	Route::post('code/check', 'RegisterController@checkInvitationCode')->name('auth_check_code');
	Route::post('register/submit', 'RegisterController@submit')->name('register_submit');
});

Route::group(['middleware' => ['auth:user']], function () {
	Route::get('/', 'HomeController@home')->name('home');
	Route::get('/unactive', 'HomeController@unactive')->name('home_unactive');
	Route::get('/agents', 'HomeController@agents')->name('agents');
	Route::get('/mall', 'HomeController@mall')->name('mall');
	Route::get('/finances', 'HomeController@finances')->name('finances');
	Route::get('/service', 'HomeController@service')->name('service');

	Route::namespace('User')->prefix('user')->group(function () {
        Route::get('/ucenter', 'UserController@center')->name('ucenter');
        Route::post('/avatar', 'UserController@avatar')->name('ucenter.edit_avatar');
        Route::get('/info', 'UserController@info')->name('info');
        Route::put('/info', 'UserController@update')->name('edit_user');

        // 客户资料
        Route::get('/customer', 'CustomerController@index')->name('customer.list');
        Route::put('/customer', 'CustomerController@update')->name('customer.update');
        Route::delete('/customer', 'CustomerController@delete')->name('customer.delete');
        Route::get('/customer/region', 'CustomerController@region')->name('customer.region');
    });

	Route::namespace('Agents')->prefix('agents')->group(function() {
		// 代理商
		Route::get('inactive', 'AgentsController@inactive')->name('agents_inactive');
		Route::get('code/sending', 'AgentsController@codeSending')->name('agents_code_sending');
		Route::get('codes', 'AgentsController@codes')->name('agents_codes');

		Route::post('code/generation', 'AgentsController@generateCode')->name('agents_code_generation');
		Route::post('code/issue', 'AgentsController@issueCode')->name('agents_code_issue');

		// 团队
		Route::get('teams/members', 'TeamsController@members')->name('teams_members');
		Route::get('teams/levels/{id?}', 'TeamsController@levels')->where('id', '[0-9]+')->name('teams_levels');
	});

	Route::namespace('Mall')->prefix('mall')->group(function() {
		// 商品
		Route::get('goods', 'GoodsController@index')->name('goods');
		Route::any('goods/addToCart', 'GoodsController@addToCart')->name('goods_add_to_cart');
		
		// 订单
		Route::get('orders', 'OrdersController@index')->name('orders');
		Route::get('orders/prepare', 'OrdersController@prepare')->name('orders_prepare');
		Route::get('orders/detail', 'OrdersController@detail')->name('orders_detail');
		Route::post('orders/submit', 'OrdersController@submit')->name('orders_submit');
		Route::post('orders/cancel', 'OrdersController@cancel')->name('orders_cancel');
		Route::post('orders/confirm', 'OrdersController@confirm')->name('orders_confirm');

		// 购物车
		Route::get('carts', 'CartsController@index')->name('carts');
		Route::post('carts/del', 'CartsController@del')->name('carts_del');
		Route::post('carts/prepare', 'CartsController@prepare')->name('carts_prepare');

		Route::get('address/regions', 'AddressController@regions')->name('address_regions');
		Route::get('address/freights', 'AddressController@freights')->name('address_freights');
		Route::get('address/customers', 'AddressController@customers')->name('address_customers');
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

		// 团队
        Route::get('team', 'TeamController@index')->name('finances_team');
	});

	// 客服中心
	Route::namespace('Service')->prefix('service')->group(function() {
		Route::get('online', 'OnlineController@index')->name('service_online');
		Route::get('message', 'OnlineController@message')->name("service_message");
		Route::post('message', 'OnlineController@submitMessage')->name('service_message_submit');
		Route::get('material', 'MaterialController@index')->name('service_material');
		Route::get('material/detail', 'MaterialController@detail')->name('service_material_detail');
		Route::get('notice', 'NoticeController@index')->name('service_notice');
		Route::get('notice/detail', 'NoticeController@detail')->name('service_notice_detail');
	});

});

/// 邀请码
Route::get('/inviteCode', 'UserController@makeInviteCode')->name('makeInviteCode');
Route::post('/inviteCode', 'UserController@grantInviteCode')->name('grantInviteCode');

//Route::get('/showMoney','UserController@showMoney')->name('showMoney');
