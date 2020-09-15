<?php

namespace Emberfuse\Tests\Routing;

use Emberfuse\Routing\Route;
use Emberfuse\Tests\TestCase;
use Symfony\Component\Routing\CompiledRoute;
use Symfony\Component\HttpFoundation\Request;
use Emberfuse\Tests\Routing\Stubs\MockController;

class RouteTest extends TestCase
{
    public function testNormalizesGivenUri()
    {
        $route = new Route('GET', '/foo/bar', [MockController::class, 'bar']);

        $this->assertEquals('foo/bar', $route->prefixUri('/foo/bar'));
        $this->assertEquals('foo/bar', $route->uri());
    }

    public function testParseRouteAction()
    {
        $route = new Route('GET', 'foo/bar', [MockController::class, 'bar']);

        $this->assertEquals(
            [
                'controller' => 'Emberfuse\Tests\Routing\Stubs\MockController',
                'method' => 'bar',
            ],
            $route->getAction()
        );
    }

    public function testCompileRoute()
    {
        $route = new Route('GET', 'foo/bar', [MockController::class, 'bar']);

        $this->assertNull($route->getCompiled());

        $route->compile();

        $this->assertInstanceOf(CompiledRoute::class, $route->getCompiled());
    }

    public function testMatchesRouteWithGivenRequest()
    {
        $routeMain = new Route('GET', '/', [MockController::class, 'bar']);
        $routeFooBar = new Route('GET', 'foo/bar', [MockController::class, 'bar']);

        $this->assertTrue($routeMain->matches(Request::create('/', 'GET')));
        $this->assertTrue($routeFooBar->matches(Request::create('foo/bar', 'GET')));
    }

    public function testRouteNaming()
    {
        $route = new Route('GET', '/foo/bar', [MockController::class, 'bar']);
        $route->name('mock-route');

        $this->assertEquals('foo/bar', $route->prefixUri('/foo/bar'));
        $this->assertEquals('foo/bar', $route->uri());
        $this->assertEquals('mock-route', $route->getName());
        $this->assertTrue($route->isNamed());
        $this->assertTrue($route->isNamed('mock-route'));
        $this->assertFalse($route->isNamed('nonmock-route'));
    }
}
