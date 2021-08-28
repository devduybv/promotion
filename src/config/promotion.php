<?php

return [

    'namespace' => env('PROMOTION_COMPONENT_NAMESPACE', 'promotion-management'),

    'models' => [
        'promotion' => VCComponent\Laravel\Promotion\Entities\Promotion::class,
    ],

    'transformers' => [
        'promotion' => VCComponent\Laravel\Promotion\Transformers\PromotionTransformer::class,
    ],

    'auth_middleware' => [
        'admin' => [
            'middleware' => '',
            'except' => [],
        ],
        'frontend' => [
            'middleware' => '',
            'except' => [],
        ],
    ],

];
