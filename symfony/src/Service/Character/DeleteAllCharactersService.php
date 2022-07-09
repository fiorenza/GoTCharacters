<?php

namespace App\Service\Character;

use App\Service\BaseService;

class DeleteAllCharactersService extends BaseService
{
    public function execute()
    {
        $json = <<<JSON
            {"query":"mutation DeleteAll {  delete_Character(where: {id: {_gt: 0}}) {    affected_rows  }}","operationName":"DeleteAll"}
        JSON;
        $headers = [
          'x-hasura-admin-secret' => 'uALQXDLUu4D9BC8jAfXgDBWm1PMpbp0pl5SQs4chhz2GG14gAVx5bfMs4I553keV',
        ];
        return json_decode($this->api->post('https://backend-challenge.hasura.app/v1/graphql', $json, [], $headers), true);
    }
}