<?php

namespace Nano\Http\Param;

use Nano\Http\Handlers\ServerParamHandler;
use Nano\Http\Interfaces\ParamInterface;
use Nano\Http\Traits\ParamGetterTrait;

class ServerParam extends BaseParameter implements ParamInterface
{
    use ParamGetterTrait;


    /** Requests related Methods */
    public function  requestMethod(): string
    {
        return $this->serverParam['REQUEST_METHOD'];
    }

    public function  requestUri(): string 
    {
        return $this->serverParam['REQUEST_URI'];
    }

    public function  requestArrayQueries(): array
    {
        return \parse_str($this->serverParam['QUERY_STRING'], $arr) 
        ?? $arr;
    }

    public function  requestQueries(): string
    {
        return $this->serverParam['QUERY_STRING'];
    }

    public function  requestTime(): string 
    {
        return $this->serverParam['REQUEST_TIME'];
    }

    public function  requestServerProtocol(): string
    {
        return $this->serverParam['SERVER_PROTOCOL'];
    }

    /** Header related methods */
    public function  headerHost(): string 
    {
        return $this->serverParam['HTTP_HOST'];
    }

    public function  headerUserAgent(): string
    {
        return $this->serverParam['HTTP_USER_AGENT'];
    }

    public function  headerAccept(): string
    {
        return $this->serverParam['HTTP_ACCEPT'];
    }

    public function  headerReferer(): string
    {
        return $this->serverParam['HTTP_REFERER'];
    }

    public function  headerConnection(): string
    {
        return $this->serverParam['HTTP_CONNECTION'];
    }

    public function  headerAuth(): string 
    {
        return $this->serverParam['HTTP_AUTHORIZATION'];
    }

    /** Server related methods */
    public function  serverName(): string
    {
        return $this->serverParam['SERVER_NAME'];
    }

    public function  serverPort(): string
    {
        return $this->serverParam['SERVER_PORT'];
    }

    public function  serverRemoteAddress(): string
    {
        return $this->serverParam['REMOTE_ADDR'];
    }

    public function  serverRemotePort(): string
    {
        return $this->serverParam['REMOTE_PORT'];
    }

    public function  serverScriptFileName(): string
    {
        return $this->serverParam['SCRIPT_FILENAME'];
    }

    public function  serverDocumentRoot(): string 
    {
        return $this->serverParam['DOCUMENT_ROOT'];
    }

    /** Http/s related method */
    public function  forwardedFor(): string 
    {
        return $this->serverParam['HTTP_X_FORWARDED_FOR'];
    }

    public function  forwardedForProto(): string 
    {
        return $this->serverParam['HTTP_X_FORWARDED_PROTO'];
    }

    public function  isHttps(): bool 
    {
        return 'on' === $this->serverParam['HTTPS'];
    }

    public function __get($name): ?string
    {
        if (\array_key_exists($name, $this->parameters)) {
            return $this->parameters[$name];
        }
        return null;
    }

    public function getHandler(): ServerParamHandler
    {
        return $this->handler ??= new ServerParamHandler($this);
    }
}
