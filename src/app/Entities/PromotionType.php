<?php

namespace VCComponent\Laravel\Promotion\Entities;

use Illuminate\Database\Eloquent\Model;

class PromotionType extends Model
{
    //
    protected $table = 'promotion_type';
    protected $fillable = [
        'name',
        'content',
    ];
}
