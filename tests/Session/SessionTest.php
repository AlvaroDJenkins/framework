<?php

namespace Emberfuse\Tests\Session;

use Emberfuse\Tests\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class SessionTest extends TestCase
{
    /**
     * @runInSeparateProcess
     */
    public function testSessionStart()
    {
        $session = new Session();
        $session->start();

        $this->assertTrue($session->isStarted());
    }

    /**
     * @runInSeparateProcess
     */
    public function testSetRequestSession()
    {
        $request = Request::create('/', 'GET');
        $session = new Session();
        $session->start();
        $request->setSession($session);

        $this->assertTrue($session->isStarted());
        $this->assertTrue($request->hasSession());
    }
}
