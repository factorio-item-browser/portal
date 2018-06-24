<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\View\Helper;

use FactorioItemBrowser\Portal\Database\Entity\User;
use Zend\View\Helper\AbstractHelper;

/**
 * The view helper for the settings.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SettingsHelper extends AbstractHelper
{
    /**
     * The user currently logged in.
     * @var User
     */
    protected $currentUser;

    /**
     * The locales available for the browser.
     * @var array|string[]
     */
    protected $locales;

    /**
     * Initializes the view helper.
     * @param User $currentUser
     * @param array|string[] $locales
     */
    public function __construct(User $currentUser, array $locales)
    {
        $this->currentUser = $currentUser;
        $this->locales = $locales;
    }

    /**
     * Returns the locales available for the browser.
     * @return array|string[]
     */
    public function getLocales(): array
    {
        return array_keys($this->locales);
    }

    /**
     * Returns the label of the specified locale.
     * @param string $locale
     * @return string
     */
    public function getLocaleLabel(string $locale): string
    {
        return $this->locales[$locale] ?? '';
    }

    /**
     * Returns the locale currently selected by the user.
     * @return string
     */
    public function getCurrentLocale(): string
    {
        return $this->currentUser->getLocale();
    }
}