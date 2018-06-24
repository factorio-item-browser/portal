<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Constant;

/**
 * Class holding some configuration values which may be too big of a hassle to put into the actual config.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class Config
{
    /**
     * The default locale to use.
     */
    const DEFAULT_LOCALE = 'en';

    /**
     * The default recipe mode to use.
     */
    const DEFAULT_RECIPE_MODE = RecipeMode::HYBRID;

    /**
     * The number of random items to show on the index page.
     */
    const INDEX_RANDOM_ITEMS = 12;

    /**
     * The number of recipes per page on the item pages.
     */
    const ITEM_RECIPE_PER_PAGE = 24;

    /**
     * The number of machines to show per page on the recipe details.
     */
    const MACHINE_PER_PAGE = 12;

    /**
     * The number of search results to show per page.
     */
    const SEARCH_RESULTS_PER_PAGE = 24;

    /**
     * The number of recipes per search result.
     */
    const SEARCH_RECIPE_COUNT = 3;

    /**
     * The name of the session cookie.
     */
    const SESSION_COOKIE_NAME = 'FIB';

    /**
     * The lifetime of the sessions.
     */
    const SESSION_LIFETIME = 2592000; // 1 month

    /**
     * The lifetime of the sessions for users only visiting once (i.e. search bots).
     */
    const SESSION_LIFETIME_SHORT = 3600; // 1 hour

    /**
     * The number of unpinned entities in the sidebar.
     */
    const SIDEBAR_UNPINNED_ENTITIES = 10;

    /**
     * The number of recipes in the tooltip.
     */
    const TOOLTIP_RECIPES = 3;
}