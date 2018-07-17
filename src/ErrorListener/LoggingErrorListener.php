<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\ErrorListener;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;
use Zend\Log\LoggerInterface;

/**
 * The error listener logging the errors.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class LoggingErrorListener
{
    /**
     * The logger to use.
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Initializes the error listener.
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * Invokes the error listener.
     * @param Throwable $error
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function __invoke(Throwable $error, ServerRequestInterface $request, ResponseInterface $response): void
    {
        $this->logger->err($error, [
            'requestUri' => $request->getUri(),
            'responseStatus' => $response->getStatusCode()
        ]);
    }
}
