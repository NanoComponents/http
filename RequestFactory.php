<?php

namespace NanoLibs\Http;

class RequestFactory
{
    public function createRequest(
        ?array $get = null,
        ?array $post = null,
        ?array $files = null,
        ?array $cookies = null,
        ?array $server = null,
        ?string $session = null
    ): Request
    {
        return new Request();
    }
}