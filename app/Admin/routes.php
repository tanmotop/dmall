<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    // 基本设置
    $router->resource('/base/couriers', Base\CourierController::class);
    $router->resource('/base/regions', Base\RegionController::class);
    $router->resource('/base/freights', Base\FreightController::class);
    $router->resource('/base/protocol', Base\ProtocolController::class);

    // 商品
	$router->resource('/goods/sale', Goods\SaleController::class);
	$router->resource('/goods/down', Goods\DownController::class);
	$router->resource('/goods/soldout', Goods\SoldoutController::class);
	$router->resource('/goods/cats', Goods\CatController::class);
    
    // 用户/代理商
    $router->resource('/users/levels', Users\LevelController::class);
    $router->resource('/users/agents', Users\AgentController::class);
    $router->resource('/users/tree', Users\TreeController::class);

    // 财务
    $router->resource('/finances/recharge', Finances\RechargeController::class);
    $router->resource('/finances/refund', Finances\RefundController::class);
});
