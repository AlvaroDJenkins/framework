<?php

namespace Emberfuse\Routing\Exceptions;

use Exception;

class UrlGenerationException extends Exception
{
    /**
     * Create a new exception for missing route parameters.
     *
     * @param \Illuminate\Routing\Route $route
     *
     * @return \Emberfuse\Routing\Exceptions\UrlGenerationException
     */
    public static function forMissingParameters($route): UrlGenerationException
    {
        return new static("Missing required parameters for [Route: {$route->getName()}] [URI: {$route->uri()}].");
    }
}
