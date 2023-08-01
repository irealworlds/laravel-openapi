<?php

namespace IrealWorlds\OpenApi\Enums;

enum RouteParameterLocation: string
{
    case Path = 'path';
    case Query = 'query';
    case Header = 'header';
    case Cookie = 'cookie';
}
