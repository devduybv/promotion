<?php

namespace VCComponent\Laravel\Promotion\Entities;

use Illuminate\Database\Eloquent\Model;

class PromotionHistory extends Model
{
    //
    protected $table = 'promotion_history';
    protected $fillable = [
        'promo_id',
        'customer_id',
        'order_id',
    ];
}
