<?php

namespace FactorioItemBrowser\Portal\Constant;

/**
 * The interface holding the route names as constants.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
interface RouteNames
{
    public const GENERIC_DETAILS = 'item.details'; // Used to build links to any generic entity.
    public const GENERIC_TOOLTIP = 'item.tooltip'; // Used to build tooltip routes to any generic entity.

    public const ICONS = 'icons';

    public const INDEX = 'index';

    public const ITEM_DETAILS = 'item.details';
    public const ITEM_RECIPE_PAGE = 'item.recipe.page';
    public const ITEM_TOOLTIP = 'item.tooltip';

    public const MOD_LIST = 'mod.list';
    public const MOD_LIST_SAVE = 'mod.list.save';
    public const MOD_LIST_UPLOAD = 'mod.list.upload';

    public const RECIPE_DETAILS = 'recipe.details';
    public const RECIPE_MACHINE_PAGE = 'recipe.machine.page';
    public const RECIPE_TOOLTIP = 'recipe.tooltip';

    public const SEARCH_QUERY  = 'search.query';
    public const SEARCH_QUERY_PAGE  = 'search.query.page';

    public const SETTINGS = 'settings';
    public const SETTINGS_SAVE = 'settings.save';

    public const SIDEBAR_PINNED = 'sidebar.pinned';
}
