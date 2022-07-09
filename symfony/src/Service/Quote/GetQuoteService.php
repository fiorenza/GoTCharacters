<?php

namespace App\Service\Quote;

use App\Service\BaseService;

class GetQuoteService extends BaseService
{
    public function execute(string $characterName)
    {
        // FIXME According to their docs if you not send the value at the end of URL you get all quotes, but is returning only one, I put a fixed number until the API be fixed
        return json_decode($this->api->get(sprintf('https://api.gameofthronesquotes.xyz/v1/author/%s/100', strtolower($characterName))), true);
    }
}