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
     * The number of recipes per page on the item pages.
     */
    const ITEM_RECIPE_PER_PAGE = 12;

    /**
     * The number of unpinned entities in the sidebar.
     */
    const SIDEBAR_UNPINNED_ENTITIES = 10;

    /**
     * The number of recipes in the tooltip.
     */
    const TOOLTIP_RECIPES = 3;
}