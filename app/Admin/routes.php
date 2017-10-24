<?php

use Illuminate\Routing\Router;

Admin::registerAuthRoutes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
], function (Router $router) {

    $router->get('/', 'HomeController@index');

    // 商品
	$router->resource('/goods/sale', Goods\SaleController::class);
	$router->resource('/goods/down', Goods\DownController::class);
	$router->resource('/goods/soldout', Goods\SoldoutController::class);
	$router->resource('/goods/cats', Goods\CatController::class);
    
    // 用户/代理商
    $router->resource('/users/levels', Users\LevelController::class);
    $router->resource('/users/agents', Users\AgentController::class);
});
