<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->group(['prefix' => 'admin'], function ($api) {
        $api->get('promotions/check', 'VCComponent\Laravel\Promotion\Http\Controllers\Api\Admin\PromotionController@checkCode');
        $api->resource('promotions', 'VCComponent\Laravel\Promotion\Http\Controllers\Api\Admin\PromotionController');
    });
    $api->get('promotions', 'VCComponent\Laravel\Promotion\Http\Controllers\Api\FrontEnd\PromotionController@index');
    $api->get('promotions/{type}/{id}', 'VCComponent\Laravel\Promotion\Http\Controllers\Api\FrontEnd\PromotionController@getPromoRelation');
    $api->get('promotions/check', 'VCComponent\Laravel\Promotion\Http\Controllers\Api\FrontEnd\PromotionController@checkCode');

});
