<?php

namespace FactorioItemBrowser\Portal\View\Helper;

use FactorioItemBrowser\Portal\Database\Entity\SidebarEntity;
use Zend\View\Helper\AbstractHelper;

/**
 * A view helper to pass parameters to the layout, or the JSON output, of pages.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class LayoutParamsHelper extends AbstractHelper
{
    /**
     * The CSS class to use on the body.
     * @var string
     */
    protected $bodyClass = '';

    /**
     * The new sidebar entity which has been added.
     * @var SidebarEntity|null
     */
    protected $newSidebarEntity = null;

    /**
     * The number of available mods.
     * @var int
     */
    protected $numberOfAvailableMods = 0;

    /**
     * The number of enabled mods.
     * @var int
     */
    protected $numberOfEnabledMods = 0;

    /**
     * The search query.
     * @var string
     */
    protected $searchQuery = '';

    /**
     * The hash of the current user's settings.
     * @var string
     */
    protected $settingsHash = '';

    /**
     * Sets the CSS class to use on the body.
     * @param string $bodyClass
     * @return $this Implementing fluent interface.
     */
    public function setBodyClass($bodyClass)
    {
        $this->bodyClass = $bodyClass;
        return $this;
    }

    /**
     * Returns the CSS class to use on the body.
     * @return string
     */
    public function getBodyClass()
    {
        return $this->bodyClass;
    }

    /**
     * Sets the new sidebar entity which has been added.
     * @param SidebarEntity|null $newSidebarEntity
     * @return $this Implementing fluent interface.
     */
    public function setNewSidebarEntity(SidebarEntity $newSidebarEntity)
    {
        $this->newSidebarEntity = $newSidebarEntity;
        return $this;
    }

    /**
     * Returns the new sidebar entity which has been added.
     * @return SidebarEntity|null
     */
    public function getNewSidebarEntity()
    {
        return $this->newSidebarEntity;
    }

    /**
     * Sets the number of available mods.
     * @param int $numberOfAvailableMods
     * @return $this Implementing fluent interface.
     */
    public function setNumberOfAvailableMods(int $numberOfAvailableMods)
    {
        $this->numberOfAvailableMods = $numberOfAvailableMods;
        return $this;
    }

    /**
     * Returns the number of available mods.
     * @return int
     */
    public function getNumberOfAvailableMods()
    {
        return $this->numberOfAvailableMods;
    }

    /**
     * Sets the number of enabled mods.
     * @param int $numberOfEnabledMods
     * @return $this Implementing fluent interface.
     */
    public function setNumberOfEnabledMods(int $numberOfEnabledMods)
    {
        $this->numberOfEnabledMods = $numberOfEnabledMods;
        return $this;
    }

    /**
     * Returns the number of enabled mods.
     * @return int
     */
    public function getNumberOfEnabledMods()
    {
        return $this->numberOfEnabledMods;
    }

    /**
     * Sets the search query.
     * @param string $searchQuery
     * @return $this Implementing fluent interface.
     */
    public function setSearchQuery(string $searchQuery)
    {
        $this->searchQuery = $searchQuery;
        return $this;
    }

    /**
     * Returns the search query.
     * @return string
     */
    public function getSearchQuery()
    {
        return $this->searchQuery;
    }

    /**
     * Sets the hash of the current user's settings.
     * @param string $settingsHash
     * @return $this
     */
    public function setSettingsHash(string $settingsHash)
    {
        $this->settingsHash = $settingsHash;
        return $this;
    }

    /**
     * Returns the hash of the current user's settings.
     * @return string
     */
    public function getSettingsHash(): string
    {
        return $this->settingsHash;
    }
}