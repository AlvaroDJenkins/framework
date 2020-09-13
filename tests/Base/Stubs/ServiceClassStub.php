<?php

namespace Emberfuse\Tests\Base\Stubs;

use stdClass;
use Emberfuse\Base\AbstractService;
use Emberfuse\Base\Contracts\ServiceInterface;

class ServiceClassStub extends AbstractService implements ServiceInterface
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->instance('stdService', stdClass::class);
    }
}
