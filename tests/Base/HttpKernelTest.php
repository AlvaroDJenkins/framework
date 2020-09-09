<?php

namespace Emberfuse\Tests\Base;

use Emberfuse\Base\Kernel;
use Emberfuse\Tests\TestCase;
use Emberfuse\Base\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Emberfuse\Base\Contracts\ApplicationInterface;

class HttpKernelTest extends TestCase
{
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
        $router->get('foo/bar', '\Emberfuse\Tests\Routing\Stubs\MockController@index');
        $kernel = new Kernel($app);
        $kernel->shouldSkipMiddleware(true);
        $response = $kernel->handle(Request::create('foo/bar', 'GET'), 1, false);

        $this->assertCount(1, $router->getRouteCollection());
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('bar', $response->getContent());
        $this->assertTrue($app->isBooted());
    }

    /**
     * @runInSeparateProcess
     */
    public function testHandleRequestsWithMiddeware()
    {
        $app = $this->getApplication();
        $router = $app->getRouter();
        $router->get('foo/bar', '\Emberfuse\Tests\Routing\Stubs\MockController@index');
        $kernel = new Kernel($app);
        $response = $kernel->handle(Request::create('foo/bar', 'GET'), 1, false);

        $this->assertCount(1, $router->getRouteCollection());
        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals('bar', $response->getContent());
        $this->assertTrue($app->isBooted());
        $processedRequest = $app->make('request');
        $this->assertTrue($processedRequest->hasSession());
        $this->assertTrue($processedRequest->getSession()->isStarted());
    }

    /**
     * @runInSeparateProcess
     */
    public function testPreviousUrlIsSavedInSessionData()
    {
        $app = $this->getApplication();
        $router = $app->getRouter();
        $router->get('foo/bar', '\Emberfuse\Tests\Routing\Stubs\MockController@index');
        $kernel = new Kernel($app);
        $response = $kernel->handle(
            $request = Request::create('foo/bar', 'GET'),
        );

        $session = $app['request']->getSession();
        $this->assertTrue($session->has('_previous.url'));
        $this->assertEquals($request->getUri(), $session->get('_previous.url'));
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
