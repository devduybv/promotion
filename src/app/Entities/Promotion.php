<?php

namespace VCComponent\Laravel\Promotion\Entities;

use App\Entities\Product;
use App\Entities\User;
use Carbon\Carbon;
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
        'promo_type',
        'promo_value',
        'quantity',
    ];
    public function products()
    {
        return $this->morphedByMany(Product::class, 'promoable', 'promotionables', 'promo_id');
    }
    public function users()
    {
        return $this->morphedByMany(User::class, 'promoable', 'promotionables', 'promo_id');
    }
    public function isExpired()
    {
        return $this->end_date ? Carbon::now()->gte($this->end_date) : false;
    }
    public function isStart()
    {
        return $this->start_date ? Carbon::now()->lte($this->start_date) : false;
    }
    public function isUser($user_id)
    {
        if (!empty($user_id)) {
            return $this->users()->where('promoable_id', $user_id)->exists();
        } else {
            return false;
        }
    }
    public function isProduct($id)
    {
        return $this->products()->where('promoable_id', $id)->exists();
    }
    public function isApplicableProduct()
    {
        return $this->products()->exists();
    }
    public function getType()
    {
        return $this->type;
    }
    public function getPromoType()
    {
        return $this->promo_type;
    }
    public function getPromoValue()
    {
        return $this->promo_value;
    }
}
