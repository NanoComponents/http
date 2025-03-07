<?php

namespace NanoLibs\Http\Handlers;

use NanoLibs\Http\Exceptions\InvalidServerValueException;
use NanoLibs\Http\Interfaces\ParamHandler\ServerHandlerInterface;
use NanoLibs\Http\Traits\ParamSanitizationTrait;

class ServerParamHandler extends BaseHandler implements ServerHandlerInterface
{
    use ParamSanitizationTrait;

    public function getUserAgent(): ?string
    {
        return $this->getEvaluateValue('HTTP_USER_AGENT');
    }

    public function getMethod(): ?string
    {
        return $this->getEvaluateValue('REQUEST_METHOD') 
        ? strtoupper($this->getEvaluateValue('REQUEST_METHOD')) 
        : null;
    }

    public function getHost(): ?string
    {
        return $this->getEvaluateValue('HTTP_HOST');
    }

    public function getClientIp(): ?string
    {
        return $this->getEvaluateValue('REMOTE_ADDR');
    }

    public function getRequestUri(): ?string
    {
        return $this->getEvaluateValue('REQUEST_URI');
    }

    public function getQueryString(): ?string
    {
        return $this->getEvaluateValue('QUERY_STRING');
    }

    public function getScriptName(): ?string
    {
        return $this->getEvaluateValue('SCRIPT_NAME');
    }

    public function getProtocol(): ?string
    {
        return $this->getEvaluateValue('SERVER_PROTOCOL');
    }

    public function isSecure(): bool
    {
        // Check HTTPS header first
        $https = $this->paramInterface->get('HTTPS');
        if (in_array($https, ['on', '1'], true)) {
            return true;
        }

        // Fallback to port check
        return $this->getPort() === 443;
    }

    public function getReferer(): ?string
    {
        return $this->getEvaluateValue('HTTP_REFERER');
    }

    public function getAcceptLanguage(): ?string
    {
        return $this->getEvaluateValue('HTTP_ACCEPT_LANGUAGE');
    }

    public function getAuthorizationHeader(): ?string
    {
        return $this->getEvaluateValue('HTTP_AUTHORIZATION');
    }

    public function getServerName(): ?string
    {
        return $this->getEvaluateValue('SERVER_NAME');
    }

    public function getPort(): null|int|string
    {
        return is_numeric($this->paramInterface->get('SERVER_PORT'))
        ? (int)$this->paramInterface->get('SERVER_PORT')
        : null;
    }

    public function getContentType(): ?string
    {
        return $this->getEvaluateValue('CONTENT_TYPE');
    }

    public function getAccept(): ?string
    {
        return $this->getEvaluateValue('HTTP_ACCEPT');
    }

    public function getForwardedFor(): ?string
    {
        return $this->getEvaluateValue('HTTP_X_FORWARDED_FOR');
    }

    public function isXmlHttpRequest(): ?bool
    {
        return $this->paramInterface->get('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }

    /**
     * Summary of typeEvaluate
     * @param mixed $value
     * @throws \NanoLibs\Http\Exceptions\InvalidServerValueException
     * @return void
     */
    protected function typeEvaluate($value): void
    {
        if ($value !== null && !is_string($value)) {
            throw new InvalidServerValueException("Expected string|null, got " . gettype($value));
        }
    }

    protected function getEvaluateValue(string $key): ?string
    {
        /** @var string|null $value */
        $value = $this->paramInterface->get($key);
        $this->typeEvaluate($value);
        return $value;
    }
}
