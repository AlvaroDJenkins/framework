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
        $this->registerSessionManager();
    }

    /**
     * Register session manager.
     *
     * @return void
     */
    protected function registerSessionManager(): void
    {
        $this->app->singleton('session', function ($app) {
            return new Session();
        });

        $this->app->instance(SessionInterface::class, $this->app['session']);
    }
}
