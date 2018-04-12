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

            Handler\Index\IndexHandler::class => Handler\AbstractRequestHandlerFactory::class,
            Handler\Item\ItemDetailsHandler::class => Handler\AbstractRequestHandlerFactory::class,
            Handler\Item\ItemTooltipHandler::class => Handler\AbstractRequestHandlerFactory::class,
            Handler\Sidebar\SidebarPinHandler::class => Handler\Sidebar\AbstractSidebarRequestHandlerFactory::class,
            Handler\Sidebar\SidebarUnpinHandler::class => Handler\Sidebar\AbstractSidebarRequestHandlerFactory::class,

            Middleware\ApiClientMiddleware::class => Middleware\ApiClientMiddlewareFactory::class,
            Middleware\LayoutMiddleware::class => Middleware\LayoutMiddlewareFactory::class,
            Middleware\LocaleMiddleware::class => Middleware\LocaleMiddlewareFactory::class,
            Middleware\SessionMiddleware::class => Middleware\SessionMiddlewareFactory::class,

            // Dependencies of other libraries
            BasePathHelper::class => InvokableFactory::class,
            BaseUrlMiddleware::class => BaseUrlMiddlewareFactory::class,
            Client::class => Api\ClientFactory::class,
            EntityManager::class => EntityManagerFactory::class,
        ]
    ],
];
