<?php

declare(strict_types=1);

/**
 * The configuration of the dependencies.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

use BluePsyduck\ZendAutoWireFactory\AutoWireFactory;
use ContainerInteropDoctrine\EntityManagerFactory;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\Factory\InvokableFactory;
use Zend\Stratigility\Middleware\ErrorHandler;

return [
    'dependencies' => [
        'factories'  => [
            Database\Service\SidebarEntityService::class => Database\Service\SidebarEntityServiceFactory::class,
            Database\Service\UserService::class => Database\Service\UserServiceFactory::class,

            Handler\Icon\IconHandler::class => AutoWireFactory::class,
            Handler\Index\IndexHandler::class => AutoWireFactory::class,
            Handler\Item\ItemDetailsHandler::class => AutoWireFactory::class,
            Handler\Item\ItemRecipePageHandler::class => AutoWireFactory::class,
            Handler\Item\ItemTooltipHandler::class => AutoWireFactory::class,
            Handler\Mod\ModListHandler::class => AutoWireFactory::class,
            Handler\Mod\ModListSaveHandler::class => Handler\Mod\AbstractModListChangeHandlerFactory::class,
            Handler\Mod\ModListUploadHandler::class => Handler\Mod\AbstractModListChangeHandlerFactory::class,
            Handler\Recipe\RecipeMachinePageHandler::class => AutoWireFactory::class,
            Handler\Recipe\RecipeDetailsHandler::class => AutoWireFactory::class,
            Handler\Recipe\RecipeTooltipHandler::class => AutoWireFactory::class,
            Handler\Search\SearchQueryHandler::class => Handler\Search\SearchQueryHandlerFactory::class,
            Handler\Search\SearchQueryPageHandler::class => AutoWireFactory::class,
            Handler\Settings\SettingsHandler::class => AutoWireFactory::class,
            Handler\Settings\SettingsSaveHandler::class => Handler\Settings\SettingsSaveHandlerFactory::class,
            Handler\Sidebar\SidebarPinnedHandler::class => AutoWireFactory::class,

            Middleware\ApiClientMiddleware::class => AutoWireFactory::class,
            Middleware\CleanupMiddleware::class => AutoWireFactory::class,
            Middleware\LayoutMiddleware::class => Middleware\LayoutMiddlewareFactory::class,
            Middleware\LocaleMiddleware::class => Middleware\LocaleMiddlewareFactory::class,
            Middleware\MetaDataRequestMiddleware::class => AutoWireFactory::class,
            Middleware\SessionMiddleware::class => AutoWireFactory::class,

            Session\Container\MetaSessionContainer::class => Session\Container\AbstractSessionContainerFactory::class,
            Session\Container\ModListSessionContainer::class => Session\Container\AbstractSessionContainerFactory::class,
            Session\Container\SettingsSessionContainer::class => Session\Container\AbstractSessionContainerFactory::class,
            Session\SessionManager::class => InvokableFactory::class,

            // Dependencies of other libraries
            EntityManager::class => EntityManagerFactory::class,
        ],
        'delegators' => [
            ErrorHandler::class => [
                ErrorListener\LoggingErrorListenerDelegatorFactory::class,
            ],
        ],
    ],
];
