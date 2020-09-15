<?php

namespace Emberfuse\Routing\Contracts;

use Emberfuse\Routing\Route;

interface RouterInterface
{
    /**
     * Register a new GET route with the router.
     *
     * @param string $uri
     * @param array  $action
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function get(string $uri, array $action): Route;

    /**
     * Register a new POST route with the router.
     *
     * @param string $uri
     * @param array  $action
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function post(string $uri, array $action): Route;

    /**
     * Register a new PUT route with the router.
     *
     * @param string $uri
     * @param array  $action
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function put(string $uri, array $action): Route;

    /**
     * Register a new PATCH route with the router.
     *
     * @param string $uri
     * @param array  $action
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function patch(string $uri, array $action): Route;

    /**
     * Register a new DELETE route with the router.
     *
     * @param string $uri
     * @param array  $action
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function delete(string $uri, array $action): Route;

    /**
     * Register a new OPTIONS route with the router.
     *
     * @param string $uri
     * @param array  $action
     *
     * @return \Symfony\Component\Routing\Route
     */
    public function options(string $uri, array $action): Route;

    /**
     * Get all registered routes.
     *
     * @return \Emberfuse\Routing\Contracts\RouteCollectionInterface
     */
    public function getRouteCollection(): RouteCollectionInterface;
}
