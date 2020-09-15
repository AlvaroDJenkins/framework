<?php

namespace Emberfuse\Tests\Routing;

use Emberfuse\Tests\TestCase;
use Emberfuse\Routing\RouteAction;
use Emberfuse\Tests\Routing\Stubs\MockController;

class RouteActionTest extends TestCase
{
    public function testParseRouteAction()
    {
        $this->assertEquals(
            [
                'controller' => 'Emberfuse\Tests\Routing\Stubs\MockController',
                'method' => 'bar',
            ],
            RouteAction::parse([MockController::class, 'bar'])
        );
    }
}
