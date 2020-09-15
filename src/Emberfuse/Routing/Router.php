<?php

namespace Emberfuse\Routing;

use Closure;
use ArrayObject;
use JsonSerializable;
use Emberfuse\Container\Container;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Emberfuse\Routing\Contracts\RouterInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Emberfuse\Routing\Contracts\RouteCollectionInterface;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Router implements RouterInterface
{
    /**
     * All registered routes.
     *
     * @var \Emberfuse\Routing\Contracts\RouterInterface
     */
    protected $routes;

    /**
     * Create new emberfuse router instance.
     *
     * @param \Psr\Container\ContainerInterface|null     $container
     * @param \Psr\Routing\RouteCollectionInterface|null $container
     *
     * @return void
     */
    public function __construct(?ContainerInterface $container = null, ?RouteCollectionInterface $routes = null)
    {
        $this->container = $container ?: new Container();
        $this->routes = $routes ?: new RouteCollection();
    }

    /**
     * Register a new GET route with the router.
     *
     * @param string $uri
     * @param array  $action
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function get(string $uri, array $action): Route
    {
        return $this->addRoute('GET', $uri, $action);
    }

    /**
     * Register a new POST route with the router.
     *
     * @param string $uri
     * @param array  $action
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function post(string $uri, array $action): Route
    {
        return $this->addRoute('POST', $uri, $action);
    }

    /**
     * Register a new PUT route with the router.
     *
     * @param string $uri
     * @param array  $action
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function put(string $uri, array $action): Route
    {
        return $this->addRoute('PUT', $uri, $action);
    }

    /**
     * Register a new PATCH route with the router.
     *
     * @param string $uri
     * @param array  $action
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function patch(string $uri, array $action): Route
    {
        return $this->addRoute('PATCH', $uri, $action);
    }

    /**
     * Register a new DELETE route with the router.
     *
     * @param string $uri
     * @param array  $action
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function delete(string $uri, array $action): Route
    {
        return $this->addRoute('DELETE', $uri, $action);
    }

    /**
     * Register a new OPTIONS route with the router.
     *
     * @param string $uri
     * @param array  $action
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function options(string $uri, array $action): Route
    {
        return $this->addRoute('OPTIONS', $uri, $action);
    }

    /**
     * Register/add a given route to collection.
     *
     * @param string $method
     * @param string $uri
     * @param array  $action
     *
     * @return \Emberfuse\Routing\Route
     */
    public function addRoute(string $method, string $uri, array $action): Route
    {
        return $this->routes->add($this->createRoute($method, $uri, $action));
    }

    /**
     * Create new Emberfuse route instance.
     *
     * @param string $method
     * @param string $uri
     * @param array  $action
     *
     * @return \Emberfuse\Routing\Route
     */
    protected function createRoute(string $method, string $uri, array $action): Route
    {
        $route = new Route($method, $uri, $action);

        $route->setRouter($this)
            ->setContainer($this->container)
            ->compile();

        return $route;
    }

    /**
     * Load router from given call back.
     *
     * @param \Closure $callback
     *
     * @return void
     */
    public function loadRoutes(Closure $callback): void
    {
        call_user_func_array($callback, [$this]);
    }

    /**
     * Dispatch the request to the application.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function dispatch(Request $request): Response
    {
        try {
            $route = $this->findRoute($request);
        } catch (RouteNotFoundException $e) {
            throw new NotFoundHttpException();
        }

        return $this->prepareResponse($request, $route->run($request));
    }

    /**
     * Find the route matching a given request.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Emberfuse\Routing\Route
     */
    protected function findRoute(Request $request): Route
    {
        $route = $this->routes->match($request);

        $this->container->instance(Route::class, $route);

        return $route;
    }

    /**
     * Create a response instance from the given value.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param mixed                                     $response
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function prepareResponse(Request $request, $response): Response
    {
        if ($this->isSerializable($response)) {
            $response = new JsonResponse($response);
        } elseif (! $response instanceof Response) {
            $response = new Response($response, 200, ['Content-Type' => 'text/html']);
        }

        if ($response->getStatusCode() === Response::HTTP_NOT_MODIFIED) {
            $response->setNotModified();
        }

        return $response->prepare($request);
    }

    /**
     * Determine if the route response is array type.
     *
     * @param mixed $response
     *
     * @return bool
     */
    protected function isSerializable($response): bool
    {
        return $response instanceof ArrayObject ||
            $response instanceof JsonSerializable ||
            is_array($response);
    }

    /**
     * Get all registered routes.
     *
     * @return \Emberfuse\Routing\Contracts\RouteCollectionInterface
     */
    public function getRouteCollection(): RouteCollectionInterface
    {
        return $this->routes;
    }
}
