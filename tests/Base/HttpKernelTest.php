<?php

namespace Emberfuse\Tests\Base;

use Mockery as m;
use Emberfuse\Base\Kernel;
use Emberfuse\Tests\TestCase;
use Emberfuse\Base\Application;
use Symfony\Component\HttpFoundation\Request;
use Emberfuse\Tests\Base\Stubs\MiddlewareStub;
use Symfony\Component\HttpFoundation\Response;
use Emberfuse\Tests\Routing\Stubs\MockController;
use Emberfuse\Base\Contracts\ApplicationInterface;

class HttpKernelTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function testBootstrapsApplication()
    {
        $app = $this->getApplication();
        $kernel = new Kernel($app);
        $kernel->bootstrapApplication();

        $this->assertTrue($this->setAccessibleProperty($app, 'hasBeenBootstrapped'));
    }

    public function testHandleRequests()
    {
        $app = $this->getApplication();
        $router = $app->getRouter();
        $router->get('foo/bar', [MockController::class, 'index']);
        $kernel = new Kernel($app);
        $kernel->shouldSkipMiddleware(true);
        $response = $kernel->handle(Request::create('foo/bar', 'GET'), 1, false);

        $this->assertCount(1, $router->getRouteCollection());
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('bar', $response->getContent());
        $this->assertTrue($app->isBooted());
    }

    public function testGetDefaultAndPostRegisteredMiddleware()
    {
        $app = $this->getApplication();
        $kernel = new Kernel($app);
        $kernel->bootstrapApplication();
        $app['config']->set('middleware', [MiddlewareStub::class]);

        $this->assertCount(2, $kernel->getMiddleware());
    }

    /**
     * Get Emberfuse base application instance.
     *
     * @param string|null $basePath
     *
     * @return \Emberfuse\Base\Contracts\ApplicationInterface
     */
    protected function getApplication(?string $basePath = null): ApplicationInterface
    {
        return new Application($basePath ?? __DIR__ . '/fixtures');
    }
}
