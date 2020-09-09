<?php

namespace Emberfuse\Session;

use Emberfuse\Base\AbstractService;
use Emberfuse\Base\Contracts\ServiceInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class SessionService extends AbstractService implements ServiceInterface
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(SessionInterface::class, function ($app) {
            return new Session();
        });
    }
}
