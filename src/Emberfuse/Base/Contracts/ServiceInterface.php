<?php

namespace Emberfuse\Base\Contracts;

interface ServiceInterface
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void;
}
