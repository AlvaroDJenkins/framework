<?php

namespace Emberfuse\Base;

use Emberfuse\Base\Contracts\ApplicationInterface;

abstract class AbstractService
{
    /**
     * Instance of Emberfuse application.
     *
     * @var \Emberfuse\Base\Contracts\ApplicationInterface
     */
    protected $app;

    /**
     * Create new instance of abstract service class.
     *
     * @param \Emberfuse\Base\Contracts\ApplicationInterface $app
     */
    public function __construct(ApplicationInterface $app)
    {
        $this->app = $app;
    }
}
