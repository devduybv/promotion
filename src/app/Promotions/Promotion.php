<?php

namespace VCComponent\Laravel\Promotion\Promotions;

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
    public function findByCode($code)
    {
        $query = $this->entity->where('code', $code)->first();
        return $query;
    }

    public function withRelationPaginate($column = '', $value = '', $relations = 'products', $perPage = 5)
    {
        switch ($relations) {
            case "products":
                $post = $this->entity->where($column, $value)->first();
                if ($post) {
                    return $post->products()->paginate($perPage);
                }
                break;
            case "customers":
                $product = $this->entity->where($column, $value)->first();
                if ($product) {
                    return $product->customers()->paginate($perPage);
                }
                break;
            default:
                return $this;
                break;
        }
    }
    public function clearRedundant()
    {
        EntitiesPromotion::all()->each(function (EntitiesPromotion $promocode) {
            if ($promocode->isExpired() || $promocode->isStart()) {
                $promocode->customers()->detach();
                $promocode->products()->detach();
                $promocode->delete();
            }
        });
    }

    public function where($column, $value)
    {
        $query = $this->entity->where($column, $value)->get();
        return $query;
    }
    public function findByWhere(array $where, $number = 10, $order_by = 'id', $order = 'desc')
    {
        $query = $this->entity->where($where)->orderBy($order_by, $order);
        if ($number > 0) {
            return $query->limit($number)->get();
        }
        return $query->get();
    }
    public function findByWherePaginate(array $where, $number = 10, $order_by = 'id', $order = 'desc')
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
