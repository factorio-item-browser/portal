<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Middleware;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use FactorioItemBrowser\Portal\Session\Container\MetaSessionContainer;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The factory of the meta data request middleware.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class MetaDataRequestMiddlewareFactory implements FactoryInterface
{
    /**
     * Creates the middleware.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return MetaDataRequestMiddleware
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var Client $apiClient */
        $apiClient = $container->get(Client::class);
        /* @var MetaSessionContainer $metaSessionContainer */
        $metaSessionContainer = $container->get(MetaSessionContainer::class);
        /* @var SidebarEntityService $sidebarEntityService */
        $sidebarEntityService = $container->get(SidebarEntityService::class);

        return new MetaDataRequestMiddleware($apiClient, $metaSessionContainer, $sidebarEntityService);
    }
}