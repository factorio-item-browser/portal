<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Sidebar;

use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Zend\Diactoros\Response\EmptyResponse;

/**
 * The request handler for setting the pinned entities of the sidebar.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SidebarPinnedHandler implements RequestHandlerInterface
{
    /**
     * The sidebar entity database service.
     * @var SidebarEntityService
     */
    protected $sidebarEntityService;

    /**
     * AbstractSidebarRequestHandler constructor.
     * @param SidebarEntityService $sidebarEntityService
     */
    public function __construct(SidebarEntityService $sidebarEntityService)
    {
        $this->sidebarEntityService = $sidebarEntityService;
    }

    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $pinnedEntityIds = array_map('intval', $request->getParsedBody()['pinnedEntityIds'] ?? []);
        $this->sidebarEntityService->setPinnedEntityOrder($pinnedEntityIds);

        return new EmptyResponse();
    }
}
