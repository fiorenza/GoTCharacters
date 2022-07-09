<?php

namespace App\Service\Quote;

use App\Service\BaseService;

class SendQuoteService extends BaseService
{
    public function execute(string $quote, string $characterId)
    {
        $json = <<<JSON
            {"query":"mutation CreateQuote {  insert_Quote(objects: {text: \"%quote_text%\", character_id: %character_id%}) {    returning {      id      text    }  }}","operationName":"CreateQuote"}
        JSON;

        $json = str_replace('%quote_text%', $quote, $json);
        $json = str_replace('%character_id%', $characterId, $json);
        $headers = [
          'x-hasura-admin-secret' => 'uALQXDLUu4D9BC8jAfXgDBWm1PMpbp0pl5SQs4chhz2GG14gAVx5bfMs4I553keV',
        ];
        return json_decode($this->api->post('https://backend-challenge.hasura.app/v1/graphql', $json, [], $headers), true);
    }
}