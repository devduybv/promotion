<?php

namespace VCComponent\Laravel\Promotion\Transformers;

use League\Fractal\TransformerAbstract;
use VCComponent\Laravel\Product\Transformers\ProductTransformer;

class PromotionTransformer extends TransformerAbstract
{
    protected $availableIncludes = [
        'products',
    ];

    public function __construct($includes = [])
    {
        $this->setDefaultIncludes($includes);
    }

    public function transform($model)
    {
        return [
            'id' => (int) $model->id,
            'code' => $model->code,
            'title' => $model->title,
            'content' => $model->content,
            'thumbnail' => $model->thumbnail,
            'type' => $model->type,
            'start_date' => $model->start_date,
            'end_date' => $model->end_date,
            'status' => (int) $model->status,
            'promo_type' => (int) $model->promo_type,
            'promo_value' => (int) $model->promo_value,
            'quantity' => (int) $model->quantity,
            'timestamps' => [
                'created_at' => $model->created_at,
                'updated_at' => $model->updated_at,
            ],
        ];
    }
    public function includeProducts($model)
    {
        return $this->collection($model->products, new ProductTransformer());
    }
}
