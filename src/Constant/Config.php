<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Constant;

/**
 * The interface holding some configuration values which may be too big of a hassle to put into the actual config.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
interface Config
{
    /**
     * The factor to use for randomly cleanup our mess.
     */
    public const CLEANUP_FACTOR = 1000;

    /**
     * The default locale to use.
     */
    public const DEFAULT_LOCALE = 'en';

    /**
     * The default mods to use for new users.
     */
    public const DEFAULT_MODS = ['base'];

    /**
     * The default recipe mode to use.
     */
    public const DEFAULT_RECIPE_MODE = RecipeMode::HYBRID;

    /**
     * The number of random items to show on the index page.
     */
    public const INDEX_RANDOM_ITEMS = 12;

    /**
     * The number of recipes per page on the item pages.
     */
    public const ITEM_RECIPE_PER_PAGE = 24;

    /**
     * The number of machines to show per page on the recipe details.
     */
    public const MACHINE_PER_PAGE = 12;

    /**
     * The number of search results to show per page.
     */
    public const SEARCH_RESULTS_PER_PAGE = 24;

    /**
     * The number of recipes per search result.
     */
    public const SEARCH_RECIPE_COUNT = 3;

    /**
     * The name of the session cookie.
     */
    public const SESSION_COOKIE_NAME = 'FIB';

    /**
     * The lifetime of the sessions.
     */
    public const SESSION_LIFETIME = 2592000; // 1 month

    /**
     * The lifetime of the sessions for users only visiting once (i.e. search bots).
     */
    public const SESSION_LIFETIME_SHORT = 3600; // 1 hour

    /**
     * The number of unpinned entities in the sidebar.
     */
    public const SIDEBAR_UNPINNED_ENTITIES = 10;

    /**
     * The number of recipes in the tooltip.
     */
    public const TOOLTIP_RECIPES = 3;
}
