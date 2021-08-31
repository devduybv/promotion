<?php

namespace VCComponent\Laravel\Promotion\Events;

use Illuminate\Queue\SerializesModels;

class PromotionDeletedEvent
{
    use SerializesModels;

    public function __construct()
    {

    }
}
