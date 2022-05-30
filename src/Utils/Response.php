<?php

namespace PerfectWPWCO\Utils;

class Response
{
    public static function json(array $data): void
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        die();
    }
}