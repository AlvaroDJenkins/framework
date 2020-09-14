<?php

namespace Emberfuse\Tests\Base\Stubs;

use Emberfuse\Base\Contracts\MiddlewareInterface;

class MiddlewareStub implements MiddlewareInterface
{
    /**
     * Handle incoming request instance.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        return $request;
    }
}
