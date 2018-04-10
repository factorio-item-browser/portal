<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Portal\Database\Service\UserService;
use Interop\Container\ContainerInterface;

/**
 * The factory of the session middleware.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SessionMiddlewareFactory
{
    /**
     * Creates the middleware.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return SessionMiddleware
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var UserService $userService */
        $userService = $container->get(UserService::class);

        return new SessionMiddleware($userService);
    }
}