<?php

declare(strict_types=1);

/**
 * The file setting up the routes.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

use FactorioItemBrowser\Portal\Constant\RouteNames;
use Psr\Container\ContainerInterface;
use Zend\Expressive\Application;
use Zend\Expressive\MiddlewareFactory;

return function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->route('/', Handler\Index\IndexHandler::class, ['GET', 'POST'], RouteNames::INDEX);

    $app->route('/sidebar/pin/{id:\d+}', Handler\Sidebar\SidebarPinHandler::class, ['GET', 'POST'], RouteNames::SIDEBAR_PIN);
    $app->route('/sidebar/unpin/{id:\d+}', Handler\Sidebar\SidebarUnpinHandler::class, ['GET', 'POST'], RouteNames::SIDEBAR_UNPIN);

    $app->route('/{type}/{name}', Handler\Item\ItemDetailsHandler::class, ['GET', 'POST'], RouteNames::ITEM_DETAILS);
    $app->route('/{type}/{name}/tooltip', Handler\Item\ItemTooltipHandler::class, ['GET', 'POST'], RouteNames::ITEM_TOOLTIP);
};
