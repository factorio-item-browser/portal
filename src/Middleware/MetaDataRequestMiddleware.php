<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Api\Client\Request\Mod\ModMetaRequest;
use FactorioItemBrowser\Api\Client\Response\Mod\ModMetaResponse;
use FactorioItemBrowser\Portal\Constant\Attribute;
use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
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
     * The database sidebar entity service.
     * @var SidebarEntityService
     */
    protected $sidebarEntityService;

    /**
     * Initializes the middleware.
     * @param Client $apiClient
     * @param MetaSessionContainer $metaSessionContainer
     * @param SidebarEntityService $sidebarEntityService
     */
    public function __construct(
        Client $apiClient,
        MetaSessionContainer $metaSessionContainer,
        SidebarEntityService $sidebarEntityService
    )
    {
        $this->apiClient = $apiClient;
        $this->metaSessionContainer = $metaSessionContainer;
        $this->sidebarEntityService = $sidebarEntityService;
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
        if ($request->getAttribute(Attribute::LOCALE_CHANGED, false)) {
            $this->sidebarEntityService->refresh();
        }

        return $handler->handle($request);
    }
}