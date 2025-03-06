<?php

namespace NanoLibs\Http;

use NanoLibs\Http\Param\CookieParam;
use NanoLibs\Http\Param\FileParam;
use NanoLibs\Http\Param\FormParam;
use NanoLibs\Http\Param\QueryParam;
use NanoLibs\Http\Param\ServerParam;
use NanoLibs\Http\Param\SessionParam;
use NanoLibs\Http\Services\StreamInput\StreamInputService;

class RequestFactory
{
    public static function create(
        ?array $get = null,
        ?array $post = null,
        ?array $files = null,
        ?array $cookies = null,
        ?array $server = null,
        ?array $session = null
    ): Request
    {
        return new Request(
            queryParam: new QueryParam($get ?? $_GET),
            formParam: new FormParam($post ?? $_POST),
            serverParam: new ServerParam($server ?? $_SERVER),
            cookieParam: new CookieParam($cookies ?? $_COOKIE),
            fileParam: new FileParam($files ?? $_FILES),
            sessionParam: new SessionParam($session ?? $_SESSION ?? []),
            streamInputService: new StreamInputService('php://input'),
        );
    }
}