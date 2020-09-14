<?php

namespace Emberfuse\Tests\Support;

use BadMethodCallException;
use Emberfuse\Tests\TestCase;
use Emberfuse\Support\Pipeline;
use Emberfuse\Container\Container;
use Emberfuse\Tests\Support\Stubs\PipeOne;
use Emberfuse\Tests\Support\Stubs\PipeTwo;
use Emberfuse\Tests\Support\Stubs\InvalidPipe;

class PipelineTest extends TestCase
{
    public function testBasicPipingProcess()
    {
        $data = [
            'foo' => 'bar',
            'bar' => 'baz',
        ];

        $result = (new Pipeline(new Container()))
            ->send($data)
            ->through([PipeOne::class, PipeTwo::class])
            ->then(function ($data) {
                return $data;
            });

        $this->assertNotSame($data, $result);
    }

    public function testSendingThroughEmptyPipes()
    {
        $data = [
            'foo' => 'bar',
            'bar' => 'baz',
        ];

        $result = (new Pipeline(new Container()))
            ->send($data)
            ->through([])
            ->then(function ($data) {
                return $data;
            });

        $this->assertSame($data, $result);
    }

    public function testSendingThroughInvalidPipe()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Method [handle] does not exist in class.');

        $result = (new Pipeline(new Container()))
            ->send([])
            ->through([PipeOne::class, PipeTwo::class, InvalidPipe::class])
            ->then(function ($data) {
                return $data;
            });
    }
}
