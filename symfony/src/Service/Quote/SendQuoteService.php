<?php

namespace App\Service\Quote;

use App\Service\BaseService;

class SendQuoteService extends BaseService
{
    public function execute(array $characters)
    {
        $json = <<<JSON
            {"query":"mutation CreateQuote {  insert_Quote(objects: [%quotes%]) {    returning {      id      text    }  }}","operationName":"CreateQuote"}
        JSON;

        $quotesArray = [];
        foreach ($characters as $character) {
            foreach ($character['quotes'] as $quote) {
                $quotesArray[] = sprintf('{text: \"%s\", character_id: %s}', str_replace('"', '\\\\\"', $quote), $character['id']);
            }
        }

        $json = str_replace('%quotes%', implode(',', $quotesArray), $json);
        $headers = [
          'x-hasura-admin-secret' => 'uALQXDLUu4D9BC8jAfXgDBWm1PMpbp0pl5SQs4chhz2GG14gAVx5bfMs4I553keV',
        ];

        return json_decode($this->api->post('https://backend-challenge.hasura.app/v1/graphql', $json, [], $headers), true);
    }
}