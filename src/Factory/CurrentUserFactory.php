<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Factory;

use FactorioItemBrowser\Portal\Database\Entity\User;
use FactorioItemBrowser\Portal\Service\UserService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The factory for fetching the current user of the portal.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class CurrentUserFactory implements FactoryInterface
{
    /**
     * Returns the current user of the portal.
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param null|array $options
     * @return User
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var UserService $userService */
        $userService = $container->get(UserService::class);

        return $userService->getCurrentUser();
    }
}
