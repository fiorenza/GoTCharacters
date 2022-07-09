<?php

namespace App\Service\Character;

use App\Service\BaseService;

class GetCharacterService extends BaseService
{
    public function execute()
    {
        return json_decode($this->api->get('https://thronesapi.com/api/v2/Characters'), true);
    }
}