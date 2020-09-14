<?php

namespace Emberfuse\Base\Bootstrap;

use Emberfuse\Base\Contracts\ApplicationInterface;
use Emberfuse\Base\Contracts\BootstrapperInterface;

class LoadServices implements BootstrapperInterface
{
    /**
     * Bootstrap application.
     *
     * @param \Emberfuse\Base\Contracts\ApplicationInterface
     *
     * @return void
     */
    public function bootstrap(ApplicationInterface $app): void
    {
        foreach ($this->getServices($app) as $service) {
            $app->registerService($service);
        }
    }

    /**
     * Get all service registered to the application.
     *
     * @param \Emberfuse\Base\Contracts\ApplicationInterface
     *
     * @return array
     */
    protected function getServices(ApplicationInterface $app): array
    {
        if ($app['config']->has('services')) {
            return $app->services();
        }

        return $app->services();
    }
}
