<?php
namespace VCComponent\Laravel\Promotion\Repositories;

use Prettus\Repository\Contracts\RepositoryInterface;

interface PromotionInterface extends RepositoryInterface
{
    public function model();
    public function getEntity();
    public function applyQueryScope($query, $field, $value);
    public function getStatus($request, $query);
    public function fomatDate($date);
    public function field($request);
    public function getFromDate($request, $query);
    public function getToDate($request, $query);
    public function findById($request, $id);
}
