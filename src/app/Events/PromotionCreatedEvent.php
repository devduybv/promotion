<?php

namespace VCComponent\Laravel\Promotion\Events;

use Illuminate\Queue\SerializesModels;

class PromotionCreatedEvent
{
    use SerializesModels;
    public $promotion;
    public function __construct($promotion)
    {
        $this->promotion = $promotion;

    }
}
