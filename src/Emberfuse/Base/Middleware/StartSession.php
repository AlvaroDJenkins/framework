<?php

namespace Emberfuse\Base\Middleware;

use Symfony\Component\HttpFoundation\Request;
use Emberfuse\Base\Contracts\MiddlewareInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class StartSession implements MiddlewareInterface
{
    /**
     * Instance of session manager.
     *
     * @var \Symfony\Component\HttpFoundation\Session\SessionInterface
     */
    protected $session;

    /**
     * Create a new session middleware.
     *
     * @param \Symfony\Component\HttpFoundation\Session\SessionInterface $session
     *
     * @return void
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Handle incoming request instance.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return mixed
     */
    public function handle(Request $request)
    {
        $this->session->start();

        $request->setSession($this->session);

        $this->storeCurrentUrl($request);

        return $request;
    }

    /**
     * Store the current URL for the request if necessary.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return void
     */
    protected function storeCurrentUrl(Request $request): void
    {
        if ($request->getMethod() === 'GET' && ! $request->isXmlHttpRequest()) {
            $this->session->set('_previous.url', $request->getUri());
        }
    }
}
