<?php

namespace Utils;

class Response
{
    public static function sendJson($status, $data)
    {
        $json = json_encode($data);
        header("Content-Type", "application/json");
        header(sprintf('HTTP/1.1 %s %s', 200, $status), true, 200);

        echo ($json);
    }
}
