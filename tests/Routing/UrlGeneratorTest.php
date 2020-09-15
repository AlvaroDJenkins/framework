<?php

namespace Emberfuse\Tests\Routing;

use Emberfuse\Routing\Route;
use Emberfuse\Tests\TestCase;
use Emberfuse\Routing\UrlGenerator;
use Emberfuse\Routing\RouteCollection;
use Symfony\Component\HttpFoundation\Request;
use Emberfuse\Tests\Routing\Stubs\MockController;

class UrlGeneratorTest extends TestCase
{
    public function testBasicUrlGeneration()
    {
        $routeCollection = new RouteCollection();
        $routeA = new Route('GET', 'foo/{bar}', [MockController::class, 'show']);
        $routeA->name('foo');
        $routeB = new Route('GET', 'foo/{bar}/{baz}', [MockController::class, 'show']);
        $routeB->name('boo');
        $routeCollection->add($routeA);
        $routeCollection->add($routeB);

        $request = Request::create('/', 'GET');
        $uri = new UrlGenerator($routeCollection, $request);
        $this->assertSame('/foo/baz', $uri->generate('foo', ['bar' => 'baz']));
        $this->assertSame('/foo/baz/boo', $uri->generate('boo', ['bar' => 'baz', 'baz' => 'boo']));
    }
}
