<?php

declare(strict_types=1);

/**
 * The configuration of the dependencies.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

use Blast\BaseUrl\BasePathHelper;
use Blast\BaseUrl\BaseUrlMiddleware;
use Blast\BaseUrl\BaseUrlMiddlewareFactory;
use ContainerInteropDoctrine\EntityManagerFactory;
use Doctrine\ORM\EntityManager;
use FactorioItemBrowser\Api\Client\Client\Client;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'dependencies' => [
        'factories'  => [
            Database\Service\SidebarEntityService::class => Database\Service\SidebarEntityServiceFactory::class,
            Database\Service\UserService::class => Database\Service\UserServiceFactory::class,

            Handler\Index\IndexHandler::class => Handler\Index\IndexHandlerFactory::class,
            Handler\Item\ItemDetailsHandler::class => Handler\Item\ItemDetailsHandlerFactory::class,
            Handler\Item\ItemTooltipHandler::class => Handler\Item\ItemTooltipHandlerFactory::class,
            Handler\Mod\ModListHandler::class => Handler\Mod\ModListHandlerFactory::class,
            Handler\Mod\ModListSaveHandler::class => Handler\Mod\ModListSaveHandlerFactory::class,
            Handler\Recipe\RecipeDetailsHandler::class => Handler\Recipe\RecipeDetailsHandlerFactory::class,
            Handler\Recipe\RecipeTooltipHandler::class => Handler\Recipe\RecipeTooltipHandlerFactory::class,
            Handler\Search\SearchQueryHandler::class => Handler\Search\SearchQueryHandlerFactory::class,
            Handler\Search\SearchQueryPageHandler::class => Handler\Search\SearchQueryPageHandlerFactory::class,
            Handler\Sidebar\SidebarPinHandler::class => Handler\Sidebar\AbstractSidebarRequestHandlerFactory::class,
            Handler\Sidebar\SidebarUnpinHandler::class => Handler\Sidebar\AbstractSidebarRequestHandlerFactory::class,

            Middleware\ApiClientMiddleware::class => Middleware\ApiClientMiddlewareFactory::class,
            Middleware\LayoutMiddleware::class => Middleware\LayoutMiddlewareFactory::class,
            Middleware\LocaleMiddleware::class => Middleware\LocaleMiddlewareFactory::class,
            Middleware\MetaDataRequestMiddleware::class => Middleware\MetaDataRequestMiddlewareFactory::class,
            Middleware\SessionMiddleware::class => Middleware\SessionMiddlewareFactory::class,

            Session\Container\MetaSessionContainer::class => Session\Container\AbstractSessionContainerFactory::class,
            Session\Container\ModListSessionContainer::class => Session\Container\AbstractSessionContainerFactory::class,
            Session\SessionManager::class => InvokableFactory::class,

            // Dependencies of other libraries
            BasePathHelper::class => InvokableFactory::class,
            BaseUrlMiddleware::class => BaseUrlMiddlewareFactory::class,
            Client::class => Api\ClientFactory::class,
            EntityManager::class => EntityManagerFactory::class,
        ]
    ],
];
