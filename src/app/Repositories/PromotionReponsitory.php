<?php
namespace VCComponent\Laravel\Promotion\Repositories;

use Exception;
use Illuminate\Support\Carbon;
use Prettus\Repository\Eloquent\BaseRepository;
use VCComponent\Laravel\Promotion\Entities\Promotion;
use VCComponent\Laravel\Promotion\Repositories\PromotionInterface;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

class PromotionReponsitory extends BaseRepository implements PromotionInterface
{
    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        if (isset(config('promotion.models')['promotion'])) {
            return config('promotion.models.promotion');
        } else {
            return Promotion::class;
        }
    }

    public function getEntity()
    {
        return $this->model;
    }
    public function applyQueryScope($query, $field, $value)
    {
        $query = $query->where($field, $value);
        return $query;
    }
    public function getStatus($request, $query)
    {
        if ($request->has('status')) {
            $pattern = '/^\d$/';

            if (!preg_match($pattern, $request->status)) {
                throw new Exception('The input status is incorrect');
            }

            $query = $query->where('status', $request->status);
        }
        return $query;
    }

    public function fomatDate($date)
    {

        $fomatDate = Carbon::createFromFormat('Y-m-d', $date);

        return $fomatDate;
    }

    public function field($request)
    {
        if ($request->has('field')) {
            if ($request->field === 'updated') {
                $field = 'updated_at';
            } elseif ($request->field === 'published') {
                $field = 'published_date';
            } elseif ($request->field === 'created') {
                $field = 'created_at';
            } elseif ($request->field === 'start') {
                $field = 'start_date';
            } elseif ($request->field === 'end') {
                $field = 'end_date';
            }
            return $field;
        } else {
            throw new Exception('field requied');
        }
    }

    public function getFromDate($request, $query)
    {
        if ($request->has('from')) {
            $field = $this->field($request);
            $form_date = $this->fomatDate($request->from);
            $query = $query->whereDate($field, '>=', $form_date);
        }
        return $query;
    }

    public function getToDate($request, $query)
    {
        if ($request->has('to')) {
            $field = $this->field($request);
            $to_date = $this->fomatDate($request->to);
            $query = $query->whereDate($field, '<=', $to_date);
        }
        return $query;
    }
    public function findById($request, $id)
    {
        if (!Promotion::find($id)) {
            throw new NotFoundException('Promotions');
        }
        $promotion = $this->find($id);
        return $promotion;
    }
}
