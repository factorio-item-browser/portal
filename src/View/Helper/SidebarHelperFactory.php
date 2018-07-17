<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\View\Helper;

use FactorioItemBrowser\Portal\Database\Service\UserService;
use Interop\Container\ContainerInterface;

/**
 * The factory of the sidebar helper.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SidebarHelperFactory
{
    /**
     * Creates the view helper.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return SidebarHelper
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var UserService $userService */
        $userService = $container->get(UserService::class);

        return new SidebarHelper($userService->getCurrentUser());
    }
}
