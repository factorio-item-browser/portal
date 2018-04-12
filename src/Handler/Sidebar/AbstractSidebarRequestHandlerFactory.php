<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Sidebar;

use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use FactorioItemBrowser\Portal\Database\Service\UserService;
use Interop\Container\ContainerInterface;

/**
 * The abstract factory of the sidebar request handlers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class AbstractSidebarRequestHandlerFactory
{
    /**
     * Creates the request handler.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return AbstractSidebarRequestHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var SidebarEntityService $sidebarEntityService */
        $sidebarEntityService = $container->get(SidebarEntityService::class);
        /* @var UserService $userService */
        $userService = $container->get(UserService::class);

        return new $requestedName($sidebarEntityService, $userService->getCurrentUser());
    }
}