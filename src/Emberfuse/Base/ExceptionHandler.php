<?php

namespace Emberfuse\Base;

use Exception;
use Throwable;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Emberfuse\Base\Contracts\ExceptionHandlerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\ErrorHandler\ErrorRenderer\HtmlErrorRenderer;

class ExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * Instance of logger implementation.
     *
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * Create new exception handler instance.
     *
     * @param \Psr\Log\LoggerInterface $logger
     *
     * @return void
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Report or log an exception.
     *
     * @param \Throwable $e
     *
     * @return void
     */
    public function report(Throwable $e): void
    {
        $this->logger->error($e->getMessage(), ['exception' => $e]);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Throwable                                $e
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render(Request $request, Throwable $e): Response
    {
        $content = $this->renderExceptionWithSymfony($e, (bool) getenv('APP_DEBUG'));

        $headers = $this->isHttpException($e) ? $e->getHeaders() : [];
        $statusCode = $this->isHttpException($e) ? $e->getStatusCode() : 500;

        return new Response($content, $statusCode, $headers);
    }

    /**
     * Render an exception to a string using Symfony.
     *
     * @param \Throwable $e
     * @param bool       $debug
     *
     * @return string
     */
    protected function renderExceptionWithSymfony(Throwable $e, $debug)
    {
        $renderer = new HtmlErrorRenderer($debug);

        return $renderer->render($e)->getAsString();
    }

    /**
     * Determine if the given exception is an HTTP exception.
     *
     * @param \Throwable $e
     *
     * @return bool
     */
    protected function isHttpException(Throwable $e)
    {
        return $e instanceof HttpException;
    }
}
