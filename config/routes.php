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

    $app->route('/icons', Handler\Icon\IconHandler::class, ['POST', 'GET'], RouteNames::ICONS);

    $app->route('/mods', Handler\Mod\ModListHandler::class, ['GET', 'POST'], RouteNames::MOD_LIST);
    $app->route('/mods/save', Handler\Mod\ModListSaveHandler::class, ['POST'], RouteNames::MOD_LIST_SAVE);
    $app->route('/mods/upload', Handler\Mod\ModListUploadHandler::class, ['POST'], RouteNames::MOD_LIST_UPLOAD);

    $app->route('/recipe/{name}', Handler\Recipe\RecipeDetailsHandler::class, ['GET', 'POST'], RouteNames::RECIPE_DETAILS);
    $app->route('/recipe/{name}/machine/page/{page}', Handler\Recipe\RecipeMachinePageHandler::class, ['POST'], RouteNames::RECIPE_MACHINE_PAGE);
    $app->route('/recipe/{name}/tooltip', Handler\Recipe\RecipeTooltipHandler::class, ['GET', 'POST'], RouteNames::RECIPE_TOOLTIP);

    $app->route('/search/page/{page:\d+}/{query:.*}', Handler\Search\SearchQueryPageHandler::class, ['GET', 'POST'], RouteNames::SEARCH_QUERY_PAGE);
    $app->route('/search/{query:.*}', Handler\Search\SearchQueryHandler::class, ['GET', 'POST'], RouteNames::SEARCH_QUERY);

    $app->route('/settings', Handler\Settings\SettingsHandler::class, ['GET', 'POST'], RouteNames::SETTINGS);
    $app->route('/settings/save', Handler\Settings\SettingsSaveHandler::class, ['POST'], RouteNames::SETTINGS_SAVE);

    $app->route('/sidebar/pinned', Handler\Sidebar\SidebarPinnedHandler::class, ['POST'], RouteNames::SIDEBAR_PINNED);

    // Generic routes for items and fluids, but also used to abstract from the recipe routes.
    $app->route('/{type:fluid|item|recipe}/{name}', Handler\Item\ItemDetailsHandler::class, ['GET', 'POST'], RouteNames::ITEM_DETAILS);
    $app->route('/{type:fluid|item|recipe}/{name}/tooltip', Handler\Item\ItemTooltipHandler::class, ['GET', 'POST'], RouteNames::ITEM_TOOLTIP);

    $app->route('/{type:fluid|item}/{name}/{recipeType:ingredient|product}/page/{page}', Handler\Item\ItemRecipePageHandler::class, ['POST'], RouteNames::ITEM_RECIPE_PAGE);
};
