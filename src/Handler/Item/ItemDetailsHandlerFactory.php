<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Handler\Item;

use FactorioItemBrowser\Api\Client\Client\Client;
use FactorioItemBrowser\Portal\Database\Service\SidebarEntityService;
use Interop\Container\ContainerInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * The factory of the item details handler.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ItemDetailsHandlerFactory implements FactoryInterface
{
    /**
     * Creates the request handler.
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return ItemDetailsHandler
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /* @var Client $apiClient */
        $apiClient = $container->get(Client::class);
        /* @var SidebarEntityService $sidebarEntityService */
        $sidebarEntityService = $container->get(SidebarEntityService::class);
        /* @var TemplateRendererInterface $templateRenderer */
        $templateRenderer = $container->get(TemplateRendererInterface::class);

        return new ItemDetailsHandler($apiClient, $sidebarEntityService, $templateRenderer);
    }
}
