<?php

namespace Nano\Http\Enums;

enum HttpMethodEnum
{
    case GET;
    case POST;
    case PUT;
    case DELETE;

    case PATCH;
    case OPTIONS;
    case HEAD;
    case TRACE;
    case CONNECT;
}
