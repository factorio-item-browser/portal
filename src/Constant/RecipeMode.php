<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Constant;

use FactorioItemBrowser\Api\Client\Constant\RecipeMode as ClientRecipeMode;

/**
 * Class holding the values of the recipe mode.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class RecipeMode
{
    /**
     * The hybrid mode is used where both variants are displayed.
     */
    const HYBRID = 'hybrid';

    /**
     * The normal mode is used, expensive variants are ignored.
     */
    const NORMAL = ClientRecipeMode::NORMAL;

    /**
     * The expensive mode is used, normal variants get overwritten by expensive ones.
     */
    const EXPENSIVE = ClientRecipeMode::EXPENSIVE;
}
