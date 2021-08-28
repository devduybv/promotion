<?php

namespace VCComponent\Laravel\Promotion\Entities;

use App\Entities\Customer;
use App\Entities\Product;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    //
    protected $fillable = [
        'code',
        'title',
        'content',
        'thumbnail',
        'type',
        'start_date',
        'end_date',
        'status',
        'promo_type_id',
        'promo_value',
        'quantity',
    ];
    public function products()
    {
        return $this->morphedByMany(Product::class, 'promoable', 'promotionables', 'promo_id');
    }
    public function customers()
    {
        return $this->morphedByMany(Customer::class, 'promoable', 'promotionables', 'promo_id');
    }
}
