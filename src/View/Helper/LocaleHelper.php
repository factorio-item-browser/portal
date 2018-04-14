<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * The view helper for the locales.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class LocaleHelper extends AbstractHelper
{
    /**
     * The locales.
     * @var array|string[]
     */
    protected $locales;

    /**
     * The currently used locale.
     * @var string
     */
    protected $currentLocale;

    /**
     * Initializes the locale helper.
     * @param array|string[] $locales
     * @param string $currentLocale
     */
    public function __construct(array $locales, string $currentLocale)
    {
        $this->locales = $locales;
        $this->currentLocale = $currentLocale;
    }

    /**
     * Returns the enabled locales.
     * @return array|string[]
     */
    public function getEnabledLocales()
    {
        return array_keys($this->locales);
    }

    /**
     * Returns the currently used locale.
     * @return string
     */
    public function getCurrentLocale()
    {
        return $this->currentLocale;
    }

    /**
     * Returns the translated locale.
     * @param string $locale
     * @return string
     */
    public function getTranslatedLocale(string $locale): string
    {
        return $this->locales[$locale] ?? '';
    }
}