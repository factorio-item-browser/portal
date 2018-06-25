<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\View\Helper;

use FactorioItemBrowser\Api\Client\Entity\Recipe;
use FactorioItemBrowser\Api\Client\Entity\RecipeWithExpensiveVersion;
use FactorioItemBrowser\Portal\Constant\RecipeMode;
use FactorioItemBrowser\Portal\Database\Entity\User;
use Zend\View\Helper\AbstractHelper;

/**
 * The view helper for providing some settings to recipes.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class RecipeHelper extends AbstractHelper
{
    /**
     * The currently logged in user.
     * @var User
     */
    protected $currentUser;

    /**
     * Initializes the view helper.
     * @param User $currentUser
     */
    public function __construct(User $currentUser)
    {
        $this->currentUser = $currentUser;
    }

    /**
     * Returns the mode of the recipe to use.
     * @param Recipe $recipe
     * @return string
     */
    public function getRecipeMode(Recipe $recipe): string
    {
        return $this->currentUser->getRecipeMode() === RecipeMode::HYBRID ? $recipe->getMode() : RecipeMode::NORMAL;
    }

    /**
     * Returns whether the expensive recipe must be highlighted.
     * @param Recipe $recipe
     * @return bool
     */
    public function highlightExpensiveRecipe(Recipe $recipe): bool
    {
        return $this->currentUser->getRecipeMode() === RecipeMode::HYBRID
            && $recipe->getMode() === RecipeMode::EXPENSIVE;
    }

    /**
     * Returns the primary recipe to show.
     * @param Recipe $recipe
     * @return Recipe
     */
    public function getPrimaryRecipe(Recipe $recipe): Recipe
    {
        $result = $recipe;
        if ($this->currentUser->getRecipeMode() === RecipeMode::EXPENSIVE
            && $recipe instanceof RecipeWithExpensiveVersion
            && $recipe->hasExpensiveVersion()
        ) {
            $result = $recipe->getExpensiveVersion();
        }
        return $result;
    }

    /**
     * Returns whether the recipe has a secondary variant to show.
     * @param Recipe $recipe
     * @return bool
     */
    public function hasSecondaryRecipe(Recipe $recipe): bool
    {
        return $this->currentUser->getRecipeMode() === RecipeMode::HYBRID
            && $recipe instanceof RecipeWithExpensiveVersion
            && $recipe->hasExpensiveVersion();
    }

    /**
     * Returns the secondary recipe to show.
     * @param Recipe $recipe
     * @return Recipe|null
     */
    public function getSecondaryRecipe(Recipe $recipe): ?Recipe
    {
        $result = null;
        if ($this->currentUser->getRecipeMode() === RecipeMode::HYBRID
            && $recipe instanceof RecipeWithExpensiveVersion
            && $recipe->hasExpensiveVersion()
        ) {
            $result = $recipe->getExpensiveVersion();
        }
        return $result;
    }
}