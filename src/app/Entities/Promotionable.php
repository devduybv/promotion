<?php

namespace VCComponent\Laravel\Promotion\Entities;

use Illuminate\Database\Eloquent\Model;

class Promotionable extends Model
{
    //
    protected $fillable = [
        'promo_id',
        'promoable_type',
        'promoable_id',
        'quantity',
    ];
}
