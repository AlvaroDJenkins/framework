<?php

namespace Emberfuse\Routing;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RequestContext;
use Emberfuse\Routing\Exceptions\UrlGenerationException;
use Emberfuse\Routing\Contracts\RouteCollectionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UrlGenerator implements UrlGeneratorInterface
{
    /**
     * Collection of all registered routes.
     *
     * @var \Emberfuse\Routing\Contracts\RouteCollectionInterface
     */
    protected $routes;

    /**
     * Instance of HTTP request.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * Instance of request context.
     *
     * @var \Symfony\Component\Routing\RequestContext
     */
    protected $context;

    /**
     * Characters that should not be URL encoded.
     *
     * @var array
     */
    public $dontEncode = [
        '%2F' => '/',
        '%40' => '@',
        '%3A' => ':',
        '%3B' => ';',
        '%2C' => ',',
        '%3D' => '=',
        '%2B' => '+',
        '%21' => '!',
        '%2A' => '*',
        '%7C' => '|',
        '%3F' => '?',
        '%26' => '&',
        '%23' => '#',
        '%25' => '%',
    ];

    /**
     * Create new instance of UrlGenerator.
     *
     * @param \Emberfuse\Routing\Contracts\RouteCollectionInterface $routes
     * @param \Symfony\Component\HttpFoundation\Request             $request
     * @param \Symfony\Component\Routing\RequestContext|null        $context
     *
     * @return void
     */
    public function __construct(RouteCollectionInterface $routes, Request $request, ?RequestContext $context = null)
    {
        $this->routes = $routes;
        $this->request = $request;
        $this->context = $context;
    }

    /**
     * Generate a URL for the given route.
     *
     * @param string $name
     * @param array  $parameters
     * @param bool   $absolute
     *
     * @return string
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     * @throws \Emberfuse\Routing\Exceptions\UrlGenerationException
     */
    public function to(string $name, array $parameters = [], $absolute = false)
    {
        $type = $absolute === false
            ? UrlGeneratorInterface::RELATIVE_PATH
            : UrlGeneratorInterface::ABSOLUTE_PATH;

        return $this->generate($name, $parameters, $type);
    }

    /**
     * {@inheritdoc}
     */
    public function generate(string $name, array $parameters = [], int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        try {
            $route = $this->routes->matchRouteByName($name, $this->request);
        } catch (RouteNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        $uri = $this->constructPath(
            $route->uri,
            $this->replaceRouteParameters($route, $parameters)
        );

        $uri = strtr(rawurlencode($uri), $this->dontEncode);

        if ($referenceType !== UrlGeneratorInterface::ABSOLUTE_PATH) {
            $uri = preg_replace('#^(//|[^/?])+#', '', $uri);

            if ($base = $this->request->getBaseUrl()) {
                $uri = preg_replace('#^' . $base . '#i', '', $uri);
            }
        }

        return '/' . ltrim($uri, '/');
    }

    /**
     * Replace route parameter placeholders with actual values.
     *
     * @param \Emberfuse\Routing\Route $route
     * @param array                    $parameters
     *
     * @return array
     */
    protected function replaceRouteParameters(Route $route, array $parameters = []): array
    {
        $routeParameters = array_flip($route->getParameterNames());

        foreach ($parameters as $name => $value) {
            if (! array_key_exists($name, $routeParameters)) {
                throw UrlGenerationException::forMissingParameters($route);
            }

            $routeParameters[$name] = $value;
        }

        return $routeParameters;
    }

    /**
     * Construct path section of the uri.
     *
     * @param string $uri
     * @param array  $parameters
     *
     * @return string
     */
    protected function constructPath(string $uri, array $parameters): string
    {
        foreach ($parameters as $key => $value) {
            $uri = preg_replace("/\{($key*?)\}/", $value, $uri);
        }

        return $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function setContext(RequestContext $context)
    {
        $this->context = $context;
    }

    /**
     * {@inheritdoc}
     */
    public function getContext()
    {
        return $this->context;
    }
}
