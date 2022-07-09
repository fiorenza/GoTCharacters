<?php

namespace App\Service\Character;

use App\Service\BaseService;

class SendCharacterService extends BaseService
{
    public function execute(array $characteres)
    {
        $json = <<<JSON
            {"query":"mutation CreateCharacter {  insert_Character(objects: [%characters%]) {    returning {      id, name    }  }}","operationName":"CreateCharacter"}
        JSON;
        $charactersArray = array_map(fn ($character) => sprintf('{name: \"%s\",image_url: \"%s\"}', $character['name'], $character['image_url']), $characteres);
        $json = str_replace('%characters%', implode(',', $charactersArray), $json);
        $headers = [
          'x-hasura-admin-secret' => 'uALQXDLUu4D9BC8jAfXgDBWm1PMpbp0pl5SQs4chhz2GG14gAVx5bfMs4I553keV',
        ];
        return json_decode($this->api->post('https://backend-challenge.hasura.app/v1/graphql', $json, [], $headers), true);
    }
}