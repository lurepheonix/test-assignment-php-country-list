<?php

namespace Controllers\Api;

use Utils\Response;
use Services\RegionService;

class RegionController
{
    function getList()
    {
        $cities = (new RegionService)->getArray();
        Response::sendJson('ok', $cities);
    }

    function execute()
    {
        $this->getList();
    }
}
