<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Sidebar;

use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\JsonResponse;

/**
 * The request handler for pinning an entity to the sidebar.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SidebarPinHandler extends AbstractSidebarRequestHandler
{
    /**
     * Handle the request and return a response.
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $sidebarEntity = $this->getSidebarEntityById(intval($request->getAttribute('id')));
        if ($sidebarEntity instanceof SidebarEntity) {
            $this->sidebarEntityService->pin($sidebarEntity);
        }
        return new JsonResponse([]);
    }
}