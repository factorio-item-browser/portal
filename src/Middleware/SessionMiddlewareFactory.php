<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use Blast\BaseUrl\BasePathHelper;
use FactorioItemBrowser\Portal\Database\Service\UserService;
use FactorioItemBrowser\Portal\Session\SessionManager;
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
        /* @var BasePathHelper $basePathHelper */
        $basePathHelper = $container->get(BasePathHelper::class);
        /* @var SessionManager $sessionManager */
        $sessionManager = $container->get(SessionManager::class);
        /* @var UserService $userService */
        $userService = $container->get(UserService::class);

        return new SessionMiddleware($basePathHelper, $sessionManager, $userService);
    }
}
