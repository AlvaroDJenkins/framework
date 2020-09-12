<?php

namespace Emberfuse\Tests\Base;

use Mockery as m;
use Emberfuse\Tests\TestCase;
use Emberfuse\Base\Application;
use Emberfuse\Support\Repository;
use Emberfuse\Base\Bootstrap\LoadConfigurations;
use Emberfuse\Base\Contracts\ApplicationInterface;
use Emberfuse\Support\Contracts\RepositoryInterface;

class LoadConfigurationsTest extends TestCase
{
    protected function tearDown(): void
    {
        m::close();
    }

    public function testBootstrapConfigurations()
    {
        $expectedData = [
            'foo' => [
                'bar' => 'baz',
            ],
        ];
        $configLoader = new LoadConfigurations();
        $configLoader->bootstrap($app = $this->getMockApp($expectedData));

        $this->assertTrue($app->has('config'));
        $this->assertEquals($expectedData, $app['config']->all());
    }

    /**
     * Get mocked Emberfuse application instance.
     *
     * @param array $expectedData
     *
     * @return \Emberfuse\Base\Contracts\ApplicationInterface
     */
    protected function getMockApp(array $expectedData): ApplicationInterface
    {
        $app = m::mock(Application::class);

        $app->shouldReceive('instance')
            ->once()
            ->withAnyArgs()
            ->andReturn();

        $app->shouldReceive('basePath')
            ->once()
            ->with('config.yaml')
            ->andReturn(__DIR__ . '/fixtures/config.yaml');

        $app->shouldReceive('has')
            ->once()
            ->with('config')
            ->andReturn(true);

        $app->shouldReceive('offsetGet')
            ->once()
            ->with('config')
            ->andReturn(new Repository($expectedData));

        return $app;
    }

    /**
     * Get instance of configurations repository.
     *
     * @return \Emberfuse\Support\Contracts\RepositoryInterface
     */
    protected function getConfigRepo(): RepositoryInterface
    {
        return new Repository([]);
    }
}
