<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\View\Helper;

use FactorioItemBrowser\Api\Client\Constant\EntityType;
use FactorioItemBrowser\Api\Client\Entity\GenericEntity;
use FactorioItemBrowser\Api\Client\Entity\Machine;
use FactorioItemBrowser\Api\Client\Entity\Recipe;
use FactorioItemBrowser\Portal\Constant\RouteNames;
use Zend\Expressive\Helper\UrlHelper;
use Zend\View\Helper\AbstractHelper;

/**
 * The view helper for the generic entities.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class GenericEntityHelper extends AbstractHelper
{
    /**
     * The URL helper.
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * Initializes the view helper.
     * @param UrlHelper $urlHelper
     */
    public function __construct(UrlHelper $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    /**
     * Returns the URL to the details page of the specified entity.
     * @param GenericEntity $entity
     * @return string
     */
    public function getDetailsUrl(GenericEntity $entity): string
    {
        return $this->urlHelper->generate(
            RouteNames::GENERIC_DETAILS,
            [
                'type' => rawurlencode($entity->getType()),
                'name' => rawurlencode($entity->getName())
            ]
        );
    }

    /**
     * Returns the URL to the details page of the specified entity.
     * @param GenericEntity $entity
     * @return string
     */
    public function getTooltip(GenericEntity $entity): string
    {
        return $this->urlHelper->generate(
            RouteNames::GENERIC_TOOLTIP,
            [
                'type' => rawurlencode($entity->getType()),
                'name' => rawurlencode($entity->getName())
            ]
        );
    }

    /**
     * Returns the icon used for the generic entity, to be handled by the icon manager.
     * @param GenericEntity $entity
     * @return string
     */
    public function getIcon(GenericEntity $entity): string
    {
        return $entity->getType() . '/' . $entity->getName();
    }

    /**
     * Returns the CSS class name to use for the icon of the specified entity.
     * @param GenericEntity $entity
     * @return string
     */
    public function getIconClass(GenericEntity $entity): string
    {
        return 'icon-' . str_replace(' ', '_', $entity->getType() . '-' . $entity->getName());
    }

    /**
     * Returns the linked entity of the specified machine.
     * @param Machine $machine
     * @return GenericEntity
     */
    public function getLinkedEntityOfMachine(Machine $machine): GenericEntity
    {
        $result = new GenericEntity();
        $result->setType(EntityType::ITEM)
               ->setName($machine->getName());
        return $result;
    }

    /**
     * Renders the specified entity to a box.
     * @param GenericEntity $entity
     * @param string $cssClass
     * @param string $tagName
     * @return string
     */
    public function renderBox(GenericEntity $entity, string $cssClass = 'item', string $tagName = 'div'): string
    {
        return $this->view->render('helper::genericEntity/box', [
            'cssClass' => $cssClass,
            'entity' => $entity,
            'tagName' => $tagName
        ]);
    }

    /**
     * Renders a recipe to a box.
     * @param Recipe $recipe
     * @param string $cssClass
     * @param string $tagName
     * @return string
     */
    public function renderRecipeBox(Recipe $recipe, string $cssClass = 'item', string $tagName = 'div'): string
    {
        return $this->view->render('helper::genericEntity/recipeBox', [
            'cssClass' => $cssClass,
            'recipe' => $recipe,
            'tagName' => $tagName
        ]);
    }

    /**
     * Renders a transparent icon of the specified entity.
     * @param GenericEntity $entity
     * @param array $additionalAttributes
     * @return string
     */
    public function renderTransparentIcon(GenericEntity $entity, array $additionalAttributes = []): string
    {
        return $this->view->render('helper::genericEntity/transparentIcon', [
            'entity' => $entity,
            'attributes' => $additionalAttributes
        ]);
    }

    /**
     * Renders a linked icon to the specified entity.
     * @param GenericEntity $entity
     * @param float $amount
     * @return string
     */
    public function renderLinkedIcon(GenericEntity $entity, float $amount = 0.): string
    {
        return $this->view->render('helper::genericEntity/linkedIcon', [
            'entity' => $entity,
            'amount' => $amount
        ]);
    }
}