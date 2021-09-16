<?php

namespace VCComponent\Laravel\Promotion\Promotions;

use Illuminate\Support\Facades\DB;
use VCComponent\Laravel\Promotion\Entities\Promotion as EntitiesPromotion;

class Promotion
{

    public $entity;

    public function __construct()
    {
        if (isset(config('promotion.models')['promotion'])) {
            $model = config('promotion.models.promotion');
            $this->entity = new $model;
        } else {
            $this->entity = new EntitiesPromotion;
        }
    }
    public function check($code)
    {
        $promocode = Promotion::findByCode($code);
        if ($promocode === null || $promocode->isExpired() || $promocode->isStart() || $promocode->status !== 1) {
            return false;
        }
        return $promocode;
    }
    public function isExist($code)
    {
        $promocode = Promotion::findByCode($code);
        if (empty($promocode)) {
            return false;
        }
        return $promocode;

    }
    public function isAvailable($code)
    {
        $promocode = Promotion::findByCode($code);
        if ($promocode->status !== 1) {
            return false;
        }
        return $promocode;

    }
    public function isExpired($code)
    {
        $promocode = Promotion::findByCode($code);
        if ($promocode->isExpired()) {
            return false;
        }
        return $promocode;

    }
    public function isStarted($code)
    {
        $promocode = Promotion::findByCode($code);
        if ($promocode->isStart()) {
            return false;
        }
        return $promocode;

    }

    public function findByCode($code)
    {
        $query = $this->entity->where('code', $code)->first();
        return $query;
    }
    public function getPromoRelation($id, $promo_type = 'users', array $where = [], $number = 10, $order_by = 'id', $order = 'desc')
    {
        $query = DB::table('promotionables')->where('promoable_id', $id)
            ->where('promoable_type', $promo_type)
            ->join('promotions', 'promo_id', '=', 'promotions.id')->select("promotions.*")
            ->orderBy($order_by, $order)
            ->where($where);
        if ($number > 0) {
            return $query->limit($number)->get();
        }
        return $query->get();
    }
    public function getPromoRelationPaginate($id, $promo_type = 'users', array $where = [], $number = 10, $order_by = 'id', $order = 'desc')
    {
        $query = DB::table('promotionables')->where('promoable_id', $id)
            ->where('promoable_type', $promo_type)
            ->join('promotions', 'promo_id', '=', 'promotions.id')->select("promotions.*")
            ->orderBy($order_by, $order)
            ->where($where);
        return $query->paginate($number);
    }
    public function withRelation($value, $column = 'code', $relations = 'products', array $where = [], $number = 10, $order_by = 'id', $order = 'desc')
    {
        switch ($relations) {
            case "products":
                $promotion = $this->entity->where($column, $value)->first();
                if ($promotion) {
                    $query = $promotion->products()->where($where)->orderBy($order_by, $order);
                    if ($number > 0) {
                        return $query->limit($number)->get();
                    }
                    return $query->get();
                }
                break;
            case "users":
                $promotion = $this->entity->where($column, $value)->first();
                if ($promotion) {
                    $query = $promotion->users()->where($where)->orderBy($order_by, $order);
                    if ($number > 0) {
                        return $query->limit($number)->get();
                    }
                    return $query->get();
                }
                break;
            default:
                return $this;
                break;
        }
    }
    public function withRelationPaginate($value, $column = 'code', $relations = 'products', array $where = [], $number = 5, $order_by = 'id', $order = 'desc')
    {
        switch ($relations) {
            case "products":
                $promotion = $this->entity->where($column, $value)->first();
                if ($promotion) {
                    return $promotion->products()->where($where)->orderBy($order_by, $order)->paginate($number);
                }
                break;
            case "users":
                $promotion = $this->entity->where($column, $value)->first();
                if ($promotion) {
                    return $promotion->users()->where($where)->orderBy($order_by, $order)->paginate($number);
                }
                break;
            default:
                return $this;
                break;
        }
    }

    public function where($column, $value)
    {
        $query = $this->entity->where($column, $value)->get();
        return $query;
    }
    public function findByWhere(array $where = [], $number = 10, $order_by = 'id', $order = 'desc')
    {
        $query = $this->entity->where($where)->orderBy($order_by, $order);
        if ($number > 0) {
            return $query->limit($number)->get();
        }
        return $query->get();
    }
    public function findByWherePaginate(array $where = [], $number = 10, $order_by = 'id', $order = 'desc')
    {
        return $this->entity->where($where)->orderBy($order_by, $order)->paginate($number);
    }
    public function getSearchResult($key_word, array $list_field = ['title'], array $where = [], $number = 10, $order_by = 'id', $order = 'desc', $columns = ['*'])
    {
        $query = $this->entity->where(function ($q) use ($list_field, $key_word) {
            foreach ($list_field as $field) {
                $q->orWhere($field, 'like', "%{$key_word}%");
            }
        });
        $query->where($where)->orderBy($order_by, $order);
        if ($number > 0) {
            return $query->limit($number)->get($columns);
        }
        return $query->get($columns);
    }
    public function getSearchResultPaginate($key_word, array $list_field = ['title'], array $where = [], $number = 10, $order_by = 'id', $order = 'desc', $columns = ['*'])
    {
        $query = $this->entity->where(function ($q) use ($list_field, $key_word) {
            foreach ($list_field as $field) {
                $q->orWhere($field, 'like', "%{$key_word}%");
            }
        });
        $query->select($columns)->where($where)->orderBy($order_by, $order);
        return $query->paginate($number);

    }

    public function findOrFail($id)
    {
        return $this->entity->findOrFail($id);
    }

    public function toSql()
    {
        return $this->entity->toSql();
    }

    public function get()
    {
        return $this->entity->get();
    }

    public function paginate($perPage)
    {
        return $this->entity->paginate($perPage);
    }

    public function limit($value)
    {
        return $this->entity->limit($value);
    }

    public function orderBy($column, $direction = 'asc')
    {
        return $this->entity->orderBy($column, $direction);
    }

    public function with($relations)
    {
        $this->entity->with($relations);

        return $this;
    }

    public function first()
    {
        return $this->entity->first();
    }

    public function create(array $attributes = [])
    {
        return $this->entity->create($attributes);
    }

    public function firstOrCreate(array $attributes, array $values = [])
    {
        return $this->entity->firstOrCreate($attributes, $values);
    }

    public function update(array $values)
    {
        return $this->entity->update($values);
    }

    public function delete()
    {
        return $this->entity->delete();
    }
}
