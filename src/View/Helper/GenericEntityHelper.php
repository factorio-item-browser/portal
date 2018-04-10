<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\View\Helper;

use FactorioItemBrowser\Api\Client\Entity\GenericEntity;
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
     * Formats and returns the amount of an item.
     * @param float $amount
     * @return string
     */
    public function formatAmount(float $amount): string
    {
        if ($amount == 0) {
            $result = '';
        } elseif ($amount > 1000) {
            $result = round($amount / 1000, 1) . 'k';
        } elseif ($amount < 1) {
            $result = round($amount * 100, 1) . '%';
        } else {
            $result = $amount . 'x';
        }
        return $result;
    }

    /**
     * Formats and returns the specified crafting time.
     * @param float $craftingTime
     * @return string
     */
    public function formatCraftingTime(float $craftingTime): string
    {
        return round($craftingTime, 2) . 's';
    }

    /**
     * Renders the specified entity to a box.
     * @param GenericEntity $entity
     * @return string
     */
    public function renderBox(GenericEntity $entity): string
    {
        return $this->view->render('helper::genericEntity/box', ['entity' => $entity]);
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