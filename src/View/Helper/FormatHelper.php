<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\View\Helper;

use Zend\I18n\Translator\TranslatorInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * The view helper for formatting numbers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class FormatHelper extends AbstractHelper
{
    /**
     * The translator interface.
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * Initializes the view helper.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Formats and returns the amount of an item.
     * @param float $amount
     * @return string
     */
    public function amount(float $amount): string
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
     * Formats and returns the specified crafting speed.
     * @param float $craftingSpeed
     * @return string
     */
    public function craftingSpeed(float $craftingSpeed): string
    {
        return round($craftingSpeed, 2) . 'x';
    }

    /**
     * Formats and returns the specified crafting time.
     * @param float $craftingTime
     * @return string
     */
    public function craftingTime(float $craftingTime): string
    {
        return round($craftingTime, 2) . 's';
    }

    /**
     * Formats and returns the specified energy usage.
     * @param float $energyUsage
     * @param string $energyUsageUnit
     * @return string
     */
    public function energyUsage(float $energyUsage, string $energyUsageUnit): string
    {
        return round($energyUsage, 3) . $energyUsageUnit;
    }

    /**
     * Formats and returns a simple number.
     * @param float $number
     * @return string
     */
    public function number(float $number): string
    {
        return (string) $number;
    }

    /**
     * Formats and returns the number of slots of a machine.
     * @param int $numberOfSlots
     * @return string
     */
    public function machineSlots(int $numberOfSlots): string
    {
        if ($numberOfSlots === -1) {
            $result = $this->translator->translate('recipe-details machine unlimited');
        } elseif ($numberOfSlots === 0) {
            $result = $this->translator->translate('recipe-details machine none');
        } else {
            $result = $this->number($numberOfSlots);
        }
        return $result;
    }
}