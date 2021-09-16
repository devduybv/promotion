<?php

namespace VCComponent\Laravel\Promotion\Http\Controllers\Api\Admin;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use VCComponent\Laravel\Promotion\Events\PromotionCreatedEvent;
use VCComponent\Laravel\Promotion\Events\PromotionDeletedEvent;
use VCComponent\Laravel\Promotion\Events\PromotionUpdatedEvent;
use VCComponent\Laravel\Promotion\Repositories\PromotionInterface;
use VCComponent\Laravel\Promotion\Transformers\PromotionTransformer;
use VCComponent\Laravel\Promotion\Validators\PromotionValidator;
use VCComponent\Laravel\Vicoders\Core\Controllers\ApiController;
use VCComponent\Laravel\Vicoders\Core\Exceptions\NotFoundException;

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
        if (!empty(config('promotion.auth_middleware.admin'))) {
            $user = $this->getAuthenticatedUser();
            $this->middleware(
                config('promotion.auth_middleware.admin.middleware'),
                ['except' => config('promotion.auth_middleware.admin.except')]
            );

        } else {
            throw new Exception("Admin middleware configuration is required");
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
    public function store(Request $request)
    {
        $data = $request->all();
        $this->validator->isValid($data, 'RULE_CREATE');
        $promotion = $this->repository->create($data);
        event(new PromotionCreatedEvent($promotion));

        return $this->response->item($promotion, new $this->transformer());
    }
    public function show(Request $request, $id)
    {
        if ($request->has('includes')) {
            $transformer = new $this->transformer(explode(',', $request->get('includes')));
        } else {
            $transformer = new $this->transformer;
        }

        $promotion = $this->repository->findById($request, $id);
        return $this->response->item($promotion, $transformer);
    }
    public function destroy($id)
    {
        $promotion = $this->entity->find($id);
        if (!$promotion) {
            throw new NotFoundException('promotion');
        }

        $this->repository->delete($id);

        event(new PromotionDeletedEvent($promotion));

        return $this->success();
    }
    public function update(Request $request, $id)
    {
        $promotion = $this->entity->find($id);
        if (!$promotion) {
            throw new NotFoundException('promotion');
        }
        $request->validate([
            'code' => [
                Rule::unique('promotions')->ignore($id),
            ],
        ]);
        $data = $request->all();

        $this->validator->isValid($data, 'RULE_UPDATE');
        $promotion = $this->repository->update($data, $id);

        event(new PromotionUpdatedEvent($promotion));

        return $this->response->item($promotion, new $this->transformer());
    }
    public function checkCode(Request $request)
    {
        $this->validator->isValid($request, 'RULE_CODE');
        $promotion = $this->repository->check($request->code);
        return $this->response->item($promotion, new $this->transformer());
    }
}
