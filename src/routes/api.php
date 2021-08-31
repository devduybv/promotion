<?php

$api = app('Dingo\Api\Routing\Router');

$api->version('v1', function ($api) {
    $api->resource('promotions', 'VCComponent\Laravel\Promotion\Http\Controllers\Api\Admin\PromotionController');
});
