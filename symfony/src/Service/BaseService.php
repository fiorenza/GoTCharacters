<?php

namespace App\Service;

use App\Helper\ApiHelper;

class BaseService
{
    protected ApiHelper $api;

    public function __construct(ApiHelper $api)
    {
        $this->api = $api;
    }

}