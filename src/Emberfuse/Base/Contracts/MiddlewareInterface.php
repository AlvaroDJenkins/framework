<?php

namespace Emberfuse\Base\Contracts;

use Symfony\Component\HttpFoundation\Request;

interface MiddlewareInterface
{
    /**
     * Handle incoming request instance.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function handle(Request $request);
}
