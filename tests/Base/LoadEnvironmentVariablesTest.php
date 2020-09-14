<?php

namespace Emberfuse\Tests\Base;

use Mockery as m;
use Emberfuse\Tests\TestCase;
use Emberfuse\Base\Application;
use Emberfuse\Base\Contracts\ApplicationInterface;
use Emberfuse\Base\Bootstrap\LoadEnvironmentVariables;

class LoadEnvironmentVariablesTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($_ENV['FOO'], $_SERVER['FOO']);

        putenv('FOO');

        m::close();
    }

    public function testLoadEnvironmentVariablesFromBasePath()
    {
        $this->expectOutputString('');

        (new LoadEnvironmentVariables())->bootstrap($this->getMockApp());

        $this->assertSame('bar', getenv('FOO'));
        $this->assertSame('bar', $_ENV['FOO']);
        $this->assertSame('bar', $_SERVER['FOO']);
    }

    /**
     * Get mocked Emberfuse application instance.
     *
     * @return \Emberfuse\Base\Contracts\ApplicationInterface
     */
    protected function getMockApp(): ApplicationInterface
    {
        $app = m::mock(Application::class);

        $app->shouldReceive('basePath')
            ->once()
            ->with()
            ->andReturn(__DIR__ . '/fixtures');

        $app->shouldReceive('instance')
            ->once()
            ->with('env', 'local')
            ->andReturn();

        return $app;
    }
}
