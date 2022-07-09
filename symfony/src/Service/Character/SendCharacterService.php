<?php

namespace App\Service\Character;

use App\Service\BaseService;

class SendCharacterService extends BaseService
{
    public function execute(string $name, string $imageUrl)
    {
        $json = <<<JSON
            {"query":"mutation CreateCharacter {  insert_Character(objects: {name: \"%character_name%\",image_url: \"%image_url%\"}) {    returning {      id    }  }}","operationName":"CreateCharacter"}
        JSON;

        $json = str_replace('%character_name%', $name, $json);
        $json = str_replace('%image_url%', $imageUrl, $json);
        $headers = [
          'x-hasura-admin-secret' => 'uALQXDLUu4D9BC8jAfXgDBWm1PMpbp0pl5SQs4chhz2GG14gAVx5bfMs4I553keV',
        ];
        return json_decode($this->api->post('https://backend-challenge.hasura.app/v1/graphql', $json, [], $headers), true);
    }
}