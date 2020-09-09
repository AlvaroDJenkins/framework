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
        $session = new Session();

        $this->app->singleton('session', function ($app) use ($session) {
            return new Session();
        });

        $this->app->instance(SessionInterface::class, $session);
    }
}
