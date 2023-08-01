<?php

namespace IrealWorlds\OpenApi\Enums;

enum HttpMethod: string
{
    case Get = "get";
    case Post = "post";
    case Put = "put";
    case Patch = "patch";
    case Delete = "delete";
}
