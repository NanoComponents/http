<?php

namespace NanoLibs\Http;

use NanoLibs\Http\Interfaces\ParamHandler\CookieHandlerInterface;
use NanoLibs\Http\Interfaces\ParamHandler\FileHandlerInterface;
use NanoLibs\Http\Interfaces\ParamHandler\FormHandlerInterface;
use NanoLibs\Http\Interfaces\ParamHandler\QueryHandlerInterface;
use NanoLibs\Http\Interfaces\ParamHandler\ServerHandlerInterface;
use NanoLibs\Http\Interfaces\ParamHandler\SessionHandlerInterface;
use NanoLibs\Http\Param\QueryParam;
use NanoLibs\Http\Param\FormParam;
use NanoLibs\Http\Param\ServerParam;
use NanoLibs\Http\Param\CookieParam;
use NanoLibs\Http\Param\FileParam;
use NanoLibs\Http\Param\SessionParam;
use NanoLibs\Http\Services\StreamInput\StreamInputService;

readonly class Request
{
    public function __construct(
        private QueryParam          $queryParam,
        private FormParam           $formParam,
        private ServerParam         $serverParam,
        private CookieParam         $cookieParam,
        private FileParam           $fileParam,
        private SessionParam        $sessionParam,
        private StreamInputService  $streamInputService,
    ) {
    }

    /**
     * Return the $_GET handler
     * @return QueryHandlerInterface
     */
    public function getQuery(): QueryHandlerInterface
    {
        return $this->queryParam->getHandler();
    }

    /**
     * Return the $_POST handler
     * @return FormHandlerInterface
     */
    public function getForm(): FormHandlerInterface
    {
        return $this->formParam->getHandler();
    }

    public function getServer(): ServerHandlerInterface
    {
        return $this->serverParam->getHandler();
    }

    public function getCookie(): CookieHandlerInterface
    {
        return $this->cookieParam->getHandler();
    }

    public function getFile(?string $fileName = null): FileHandlerInterface
    {
        return $this->fileParam->getHandler($fileName);
    }

    public function getSession(): SessionHandlerInterface
    {
        return $this->sessionParam->getHandler();
    }

    public function getStreamInput(): StreamInputService
    {
        return $this->streamInputService;
    }
}
