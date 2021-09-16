<?php

namespace VCComponent\Laravel\Promotion\Validators;

use VCComponent\Laravel\Vicoders\Core\Validators\AbstractValidator;
use VCComponent\Laravel\Vicoders\Core\Validators\ValidatorInterface;

class PromotionValidator extends AbstractValidator
{
    protected $rules = [
        ValidatorInterface::RULE_CREATE => [
            'code' => ['required', 'unique:promotions'],
            'title' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date' => ['date', 'after_or_equal:start_date'],
            'status' => ['required', 'numeric'],
            'promo_type' => ['required', 'numeric', 'in:1,2'],
            'promo_value' => ['numeric'],
            'quantity' => ['numeric'],
        ],
        ValidatorInterface::RULE_UPDATE => [
            'code' => ['required'],
            'title' => ['required'],
            'start_date' => ['required', 'date'],
            'end_date' => ['date', 'after_or_equal:start_date'],
            'status' => ['required', 'numeric'],
            'promo_type' => ['required', 'numeric', 'in:1,2'],
            'promo_value' => ['numeric'],
            'quantity' => ['numeric'],
        ],
        'RULE_CODE' => [
            'code' => ['required'],
        ],
    ];
}
