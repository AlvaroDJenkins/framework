<?php

namespace Emberfuse\Routing;

use InvalidArgumentException;

class RouteAction
{
    /**
     * Parse route action string and separate into class and method.
     *
     * @param array $action
     *
     * @return array
     */
    public static function parse(array $action): array
    {
        if (count($action) <= 0) {
            throw new InvalidArgumentException('Route action is invalid.');
        }

        [$controller, $method] = $action;

        return compact('controller', 'method');
    }
}
