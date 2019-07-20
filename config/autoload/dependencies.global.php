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
use function BluePsyduck\ZendAutoWireFactory\readConfig;
use ContainerInteropDoctrine\EntityManagerFactory;
use Doctrine\ORM\EntityManagerInterface;
use FactorioItemBrowser\Portal\Constant\ConfigKey;
use Zend\Stratigility\Middleware\ErrorHandler;

return [
    'dependencies' => [
        'abstract_factories' => [
            Factory\AbstractViewHelperFactory::class,
        ],
        'factories'  => [
            Database\Service\SidebarEntityService::class => AutoWireFactory::class,
            Database\Service\UserService::class          => AutoWireFactory::class,

            Handler\Icon\IconHandler::class                => AutoWireFactory::class,
            Handler\Index\IndexHandler::class              => AutoWireFactory::class,
            Handler\Item\ItemDetailsHandler::class         => AutoWireFactory::class,
            Handler\Item\ItemRecipePageHandler::class      => AutoWireFactory::class,
            Handler\Item\ItemTooltipHandler::class         => AutoWireFactory::class,
            Handler\Mod\ModListHandler::class              => AutoWireFactory::class,
            Handler\Mod\ModListSaveHandler::class          => AutoWireFactory::class,
            Handler\Mod\ModListUploadHandler::class        => AutoWireFactory::class,
            Handler\Recipe\RecipeMachinePageHandler::class => AutoWireFactory::class,
            Handler\Recipe\RecipeDetailsHandler::class     => AutoWireFactory::class,
            Handler\Recipe\RecipeTooltipHandler::class     => AutoWireFactory::class,
            Handler\Search\SearchQueryHandler::class       => AutoWireFactory::class,
            Handler\Search\SearchQueryPageHandler::class   => AutoWireFactory::class,
            Handler\Settings\SettingsHandler::class        => AutoWireFactory::class,
            Handler\Settings\SettingsSaveHandler::class    => AutoWireFactory::class,
            Handler\Sidebar\SidebarPinnedHandler::class    => AutoWireFactory::class,

            Middleware\ApiClientMiddleware::class       => AutoWireFactory::class,
            Middleware\CleanupMiddleware::class         => AutoWireFactory::class,
            Middleware\LayoutMiddleware::class          => AutoWireFactory::class,
            Middleware\LocaleMiddleware::class          => AutoWireFactory::class,
            Middleware\MetaDataRequestMiddleware::class => AutoWireFactory::class,
            Middleware\SessionMiddleware::class         => AutoWireFactory::class,

            Session\Container\MetaSessionContainer::class     => Session\Container\AbstractSessionContainerFactory::class,
            Session\Container\ModListSessionContainer::class  => Session\Container\AbstractSessionContainerFactory::class,
            Session\Container\SettingsSessionContainer::class => Session\Container\AbstractSessionContainerFactory::class,
            Session\SessionManager::class                     => AutoWireFactory::class,

            // Dependencies of other libraries
            EntityManagerInterface::class => EntityManagerFactory::class,

            // Auto-wire helpers
            Database\Entity\User::class . ' $currentUser' => Factory\CurrentUserFactory::class,

            'array $locales' => readConfig(ConfigKey::PROJECT, ConfigKey::PORTAL, ConfigKey::LOCALES),
            'string $version' => readConfig(ConfigKey::PROJECT, ConfigKey::PORTAL, ConfigKey::VERSION),
        ],
        'delegators' => [
            ErrorHandler::class => [
                ErrorListener\LoggingErrorListenerDelegatorFactory::class,
            ],
        ],
    ],
];
