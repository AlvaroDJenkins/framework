<?php

namespace Emberfuse\Tests\Base;

use Mockery as m;
use RuntimeException;
use Psr\Log\LoggerInterface;
use Emberfuse\Tests\TestCase;
use Emberfuse\Base\ExceptionHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ExceptionHandlerTest extends TestCase
{
    public function tearDown(): void
    {
        m::close();
    }

    public function testExceptionReporting()
    {
        $logger = m::mock(LoggerInterface::class);
        $logger->shouldReceive('error')->withArgs(['Exception message', m::hasKey('exception')]);
        $handler = new ExceptionHandler($logger);
        $handler->report(new RuntimeException('Exception message'));
    }

    public function testExceptionRendering()
    {
        putenv('APP_DEBUG=' . true);
        $request = m::mock(Request::class);
        $logger = m::mock(LoggerInterface::class);
        $handler = new ExceptionHandler($logger);

        $response = $handler->render($request, new RuntimeException('Exception message'));

        $this->assertStringContainsString('Exception message', $response->getContent());
        $this->assertEquals(500, $response->getStatusCode());
    }

    public function testHttpExceptionRendering()
    {
        putenv('APP_DEBUG=' . true);
        $request = m::mock(Request::class);
        $logger = m::mock(LoggerInterface::class);
        $handler = new ExceptionHandler($logger);

        $response = $handler->render($request, new HttpException(403, 'Http exception message'));

        $this->assertStringContainsString('Http exception message', $response->getContent());
        $this->assertEquals(403, $response->getStatusCode());
    }
}
