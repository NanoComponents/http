<?php

namespace Nano\Http\Interfaces\ParamHandler;

interface ServerHandlerInterface extends ParamHandlerInterface
{
    public function get(string $value): mixed;

    public function getUserAgent(): ?string;
    public function getMethod(): ?string;
    public function getHost(): ?string;
    public function getClientIp(): ?string;
    public function getRequestUri(): ?string;
    public function getQueryString(): ?string;
    public function getScriptName(): ?string;
    public function getProtocol(): ?string;
    public function isSecure(): ?bool;
    public function getReferer(): ?string;
    public function getAcceptLanguage(): ?string;
    public function getAuthorizationHeader(): ?string;
    public function getServerName(): ?string;
    public function getPort(): null|int|string;
    public function getContentType(): ?string;
    public function getAccept(): ?string;
    public function getForwardedFor(): ?string;
    public function isXmlHttpRequest(): ?bool;

}
