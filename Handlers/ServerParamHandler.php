<?php

namespace Nano\Http\Handlers;

use Nano\Http\Interfaces\ParamHandler\ServerHandlerInterface;
use Nano\Http\Traits\ParamSanitizationTrait;

class ServerParamHandler extends BaseHandler implements ServerHandlerInterface
{
    use ParamSanitizationTrait;
    public function getAll(): array
    {
        return $this->paramInterface->getAll();
    }

    public function getUserAgent()           : ?string
    {
        return $this->paramInterface->get('HTTP_USER_AGENT');
    }

    public function getMethod()              : ?string
    {
        if (!$this->paramInterface->get('REQUEST_METHOD')) {
            return null;
        }
        return strtoupper($this->paramInterface->get('REQUEST_METHOD'));
    }

    public function getHost()                : ?string
    {
        return $this->paramInterface->get('HTTP_HOST');
    }

    public function getClientIp()            : ?string
    {
        return $this->paramInterface->get('REMOTE_ADDR');
    }

    public function getRequestUri()          : ?string
    {
        return $this->paramInterface->get('REQUEST_URI');
    }

    public function getQueryString()         : ?string
    {
        return $this->paramInterface->get('QUERY_STRING');
    }

    public function getScriptName()          : ?string
    {
        return $this->paramInterface->get('SCRIPT_NAME');
    }

    public function getProtocol()            : ?string
    {
        return $this->paramInterface->get('SERVER_PROTOCOL');
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

    public function getReferer()             : ?string
    {
        return $this->paramInterface->get('HTTP_REFERER');
    }

    public function getAcceptLanguage()      : ?string
    {
        return $this->paramInterface->get('HTTP_ACCEPT_LANGUAGE');
    }

    public function getAuthorizationHeader() : ?string
    {
        return $this->paramInterface->get('HTTP_AUTHORIZATION');
    }
    
    public function getServerName()          : ?string
    {
        return $this->paramInterface->get('SERVER_NAME');
    }

    public function getPort()                : null|int|string
    {
        return is_numeric($this->paramInterface->get('SERVER_PORT'))
        ? (int)$this->paramInterface->get('SERVER_PORT')
        : null;
    }

    public function getContentType()         : ?string
    {
        return $this->paramInterface->get('CONTENT_TYPE');
    }
    
    public function getAccept()              : ?string
    {
        return $this->paramInterface->get('HTTP_ACCEPT');
    }

    public function getForwardedFor()        : ?string
    {
        return $this->paramInterface->get('HTTP_X_FORWARDED_FOR');
    }

    public function isXmlHttpRequest()       : ?bool
    {
        return $this->paramInterface->get('HTTP_X_REQUESTED_WITH') === 'XMLHttpRequest';
    }
}