<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Sidebar;

use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use Interop\Container\ContainerInterface;

/**
 * The factory of the sidebar pinned handler.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SidebarPinnedHandlerFactory
{
    /**
     * Creates the request handler.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return SidebarPinnedHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var SidebarEntityService $sidebarEntityService */
        $sidebarEntityService = $container->get(SidebarEntityService::class);

        return new SidebarPinnedHandler($sidebarEntityService);
    }
}
