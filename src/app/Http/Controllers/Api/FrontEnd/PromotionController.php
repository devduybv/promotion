<?php

namespace VCComponent\Laravel\Promotion\Http\Controllers\FrontEnd;

use Illuminate\Routing\Controller as BaseController;

class PromotionController extends BaseController
{
    public function __construct()
    {
        $this->middleware('example.middleware', ['except' => []]);
    }
}
