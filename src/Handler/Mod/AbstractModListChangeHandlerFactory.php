<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Mod;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use FactorioItemBrowser\Portal\Database\Service\UserService;
use FactorioItemBrowser\Portal\Session\Container\MetaSessionContainer;
use FactorioItemBrowser\Portal\Session\Container\ModListSessionContainer;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Helper\UrlHelper;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The abstract factory of the mod list change handlers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class AbstractModListChangeHandlerFactory implements FactoryInterface
{
    /**
     * Creates the request handler client.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return AbstractModListChangeHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var Client $apiClient */
        $apiClient = $container->get(Client::class);
        /* @var UserService $userService */
        $userService = $container->get(UserService::class);
        /* @var MetaSessionContainer $metaSessionContainer */
        $metaSessionContainer = $container->get(MetaSessionContainer::class);
        /* @var ModListSessionContainer $modListSessionContainer */
        $modListSessionContainer = $container->get(ModListSessionContainer::class);
        /* @var SidebarEntityService $sidebarEntityService */
        $sidebarEntityService = $container->get(SidebarEntityService::class);
        /* @var UrlHelper $urlHelper */
        $urlHelper = $container->get(UrlHelper::class);

        return new $requestedName(
            $apiClient,
            $userService->getCurrentUser(),
            $metaSessionContainer,
            $modListSessionContainer,
            $sidebarEntityService,
            $urlHelper
        );
    }
}