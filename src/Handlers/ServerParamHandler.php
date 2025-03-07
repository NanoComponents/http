<?php

namespace NanoLibs\Http\Handlers;

use NanoLibs\Http\Exceptions\InvalidServerValueException;
use NanoLibs\Http\Interfaces\ParamHandler\ServerHandlerInterface;
use NanoLibs\Http\Traits\ParamSanitizationTrait;

class ServerParamHandler extends BaseHandler implements ServerHandlerInterface
{
    use ParamSanitizationTrait;

    #[\Override]
    public function getUserAgent(): ?string
    {
        return $this->getEvaluateValue('HTTP_USER_AGENT');
    }

    #[\Override]
    public function getMethod(): ?string
    {
        return $this->getEvaluateValue('REQUEST_METHOD') 
        ? strtoupper($this->getEvaluateValue('REQUEST_METHOD')) 
        : null;
    }

    #[\Override]
    public function getHost(): ?string
    {
        return $this->getEvaluateValue('HTTP_HOST');
    }

    #[\Override]
    public function getClientIp(): ?string
    {
        return $this->getEvaluateValue('REMOTE_ADDR');
    }

    #[\Override]
    public function getRequestUri(): ?string
    {
        return $this->getEvaluateValue('REQUEST_URI');
    }

    #[\Override]
    public function getQueryString(): ?string
    {
        return $this->getEvaluateValue('QUERY_STRING');
    }

    #[\Override]
    public function getScriptName(): ?string
    {
        return $this->getEvaluateValue('SCRIPT_NAME');
    }

    #[\Override]
    public function getProtocol(): ?string
    {
        return $this->getEvaluateValue('SERVER_PROTOCOL');
    }

    #[\Override]
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

    #[\Override]
    public function getReferer(): ?string
    {
        return $this->getEvaluateValue('HTTP_REFERER');
    }

    #[\Override]
    public function getAcceptLanguage(): ?string
    {
        return $this->getEvaluateValue('HTTP_ACCEPT_LANGUAGE');
    }

    #[\Override]
    public function getAuthorizationHeader(): ?string
    {
        return $this->getEvaluateValue('HTTP_AUTHORIZATION');
    }

    #[\Override]
    public function getServerName(): ?string
    {
        return $this->getEvaluateValue('SERVER_NAME');
    }

    #[\Override]
    public function getPort(): null|int|string
    {
        return is_numeric($this->paramInterface->get('SERVER_PORT'))
        ? (int)$this->paramInterface->get('SERVER_PORT')
        : null;
    }

    #[\Override]
    public function getContentType(): ?string
    {
        return $this->getEvaluateValue('CONTENT_TYPE');
    }

    #[\Override]
    public function getAccept(): ?string
    {
        return $this->getEvaluateValue('HTTP_ACCEPT');
    }

    #[\Override]
    public function getForwardedFor(): ?string
    {
        return $this->getEvaluateValue('HTTP_X_FORWARDED_FOR');
    }

    #[\Override]
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
