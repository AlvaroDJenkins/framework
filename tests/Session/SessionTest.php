<?php

namespace Emberfuse\Tests\Session;

use Emberfuse\Tests\TestCase;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MockArraySessionStorage;

class SessionTest extends TestCase
{
    public function testSessionStart()
    {
        $session = $this->getSession();
        $session->start();

        $this->assertTrue($session->isStarted());
    }

    public function getSession(): SessionInterface
    {
        return new Session(new MockArraySessionStorage());
    }
}
