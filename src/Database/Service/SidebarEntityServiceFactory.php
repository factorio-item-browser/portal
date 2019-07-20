<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Database\Service;

use Doctrine\ORM\EntityManager;
use FactorioItemBrowser\Api\Client\ApiClientInterface;
use FactorioItemBrowser\Portal\View\Helper\LayoutParamsHelper;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Zend\View\HelperPluginManager;

/**
 * The factory of the sidebar entity service.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SidebarEntityServiceFactory implements FactoryInterface
{
    /**
     * Creates the database service.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return SidebarEntityService
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var EntityManager $entityManager */
        $entityManager = $container->get(EntityManager::class);
        /* @var ApiClientInterface $apiClient */
        $apiClient = $container->get(ApiClientInterface::class);
        /* @var UserService $userService */
        $userService = $container->get(UserService::class);

        /* @var HelperPluginManager $helperPluginManager */
        $helperPluginManager = $container->get(HelperPluginManager::class);
        /* @var LayoutParamsHelper $layoutParamsHelper */
        $layoutParamsHelper = $helperPluginManager->get(LayoutParamsHelper::class);

        return new SidebarEntityService($entityManager, $apiClient, $layoutParamsHelper, $userService);
    }
}
