<?php

namespace VCComponent\Laravel\Promotion\Http\Controllers\Api\FrontEnd;

use Exception;
use Illuminate\Http\Request;
use VCComponent\Laravel\Promotion\Repositories\PromotionInterface;
use VCComponent\Laravel\Promotion\Transformers\PromotionTransformer;
use VCComponent\Laravel\Promotion\Validators\PromotionValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;

class PromotionController extends ApiController
{
    protected $repository;
    protected $validator;
    protected $entity;
    public function __construct(PromotionInterface $repository, PromotionValidator $validator, Request $request)
    {
        $this->repository = $repository;
        $this->validator = $validator;

        $this->entity = $repository->getEntity();
        if (!empty(config('promotion.auth_middleware.frontend'))) {
            $this->middleware(
                config('promotion.auth_middleware.frontend.middleware'),
                ['except' => config('promotion.auth_middleware.frontend.except')]
            );

        } else {
            throw new Exception("FrontEnd middleware configuration is required");
        }

        if (isset(config('promotion.transformers')['promotion'])) {
            $this->transformer = config('promotion.transformers.promotion');
        } else {
            $this->transformer = PromotionTransformer::class;
        }
    }

    public function index(Request $request)
    {
        $query = $this->entity;
        if ($request->has('type')) {
            $query = $this->repository->applyQueryScope($query, 'type', $request->type);
        }
        $query = $this->repository->getFromDate($request, $query);
        $query = $this->repository->getToDate($request, $query);
        $query = $this->repository->getStatus($request, $query);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['code', 'title', 'content'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);
        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }
        if ($request->has('page')) {
            $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
            $promotions = $query->paginate($per_page);
            return $this->response->paginator($promotions, $transformer);
        }
        $promotions = $query->get();
        return $this->response->collection($promotions, $transformer);

    }
    public function getPromoRelation($type, $id, Request $request)
    {
        $query = $this->repository->getPromoRelation($id, $type);
        $query = $this->repository->getFromDate($request, $query);
        $query = $this->repository->getToDate($request, $query);
        $query = $this->repository->getStatus($request, $query);
        $query = $this->applyConstraintsFromRequest($query, $request);
        $query = $this->applySearchFromRequest($query, ['code', 'title', 'content'], $request);
        $query = $this->applyOrderByFromRequest($query, $request);
        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }
        if ($request->has('page')) {
            $per_page = $request->has('per_page') ? (int) $request->get('per_page') : 15;
            $promotions = $query->paginate($per_page);
            return $this->response->paginator($promotions, $transformer);
        }
        $promotions = $query->get();
        return $this->response->collection($promotions, $transformer);
    }
    public function checkCode(Request $request)
    {
        $this->validator->isValid($request, 'RULE_CODE');
        $promotion = $this->repository->check($request->code);
        return $this->response->item($promotion, new $this->transformer());
    }
}
