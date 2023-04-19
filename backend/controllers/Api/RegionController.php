<?php

namespace Controllers\Api;

use Utils\Response;
use Services\RegionService;

class RegionController
{
    function getList()
    {
        $filterOptions = [];

        if (isset($_GET['offset'])) {
            $filterOptions['offset'] = (int) $_GET['offset'];
        } else {
            $filterOptions['offset'] = 0;
        }

        if (isset($_GET['limit'])) {
            $filterOptions['limit'] = (int) $_GET['limit'];
        }

        if (isset($_GET['sort_direction']) && isset($_GET['sort_field'])) {
            $filterOptions['orderBy'] = [
                'order' => $_GET['sort_direction'],
                'field' => $_GET['sort_field']
            ];
        }

        $cities = (new RegionService)->getArray($filterOptions);
        Response::sendJson('ok', $cities);
    }

    function execute()
    {
        $this->getList();
    }
}
