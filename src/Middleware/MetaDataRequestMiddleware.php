<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Request\Mod\ModMetaRequest;
use FactorioItemBrowser\Api\Client\Response\Mod\ModMetaResponse;
use FactorioItemBrowser\Portal\Session\Container\MetaSessionContainer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * The middleware for requesting some meta data if they are missing.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class MetaDataRequestMiddleware implements MiddlewareInterface
{
    /**
     * The API client.
     * @var Client
     */
    protected $apiClient;

    /**
     * The meta session container.
     * @var MetaSessionContainer
     */
    protected $metaSessionContainer;

    /**
     * Initializes the middleware.
     * @param Client $apiClient
     * @param MetaSessionContainer $metaSessionContainer
     */
    public function __construct(
        Client $apiClient,
        MetaSessionContainer $metaSessionContainer
    ) {
        $this->apiClient = $apiClient;
        $this->metaSessionContainer = $metaSessionContainer;
    }


    /**
     * Process an incoming server request and return a response, optionally delegating response creation to a handler.
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($this->metaSessionContainer->getNumberOfAvailableMods() === 0) {
            /* @var ModMetaResponse $modMetaResponse */
            $modMetaResponse = $this->apiClient->send(new ModMetaRequest());
            $this->metaSessionContainer->setNumberOfAvailableMods($modMetaResponse->getNumberOfAvailableMods())
                                       ->setNumberOfEnabledMods($modMetaResponse->getNumberOfEnabledMods());
        }

        return $handler->handle($request);
    }
}