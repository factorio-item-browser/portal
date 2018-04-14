<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Portal\Database\Service\UserService;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The factory of the cleanup middleware.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class CleanupMiddlewareFactory implements FactoryInterface
{
    /**
     * Creates the cleanup middleware.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return CleanupMiddleware
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var UserService $userService */
        $userService = $container->get(UserService::class);

        return new CleanupMiddleware($userService);
    }
}