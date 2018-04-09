<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * The view helper for the locales.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-2.0 GPL v2
 */
class LocaleHelper extends AbstractHelper
{
    /**
     * The enabled locales.
     * @var array|string[]
     */
    protected $enabledLocales;

    /**
     * The currently used locale.
     * @var string
     */
    protected $currentLocale;

    /**
     * Initializes the locale helper.
     * @param array|string[] $enabledLocales
     * @param string $currentLocale
     */
    public function __construct(array $enabledLocales, string $currentLocale)
    {
        $this->enabledLocales = $enabledLocales;
        $this->currentLocale = $currentLocale;
    }

    /**
     * Returns the enabled locales.
     * @return array|string[]
     */
    public function getEnabledLocales()
    {
        return $this->enabledLocales;
    }

    /**
     * Returns the currently used locale.
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }
}