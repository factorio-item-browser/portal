<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Session\Container;

/**
 * The session container holding some meta information.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class MetaSessionContainer extends AbstractSessionContainer
{
    /**
     * Returns the name of the session container.
     * @return string
     */
    public function getContainerName(): string
    {
        return 'meta';
    }

    /**
     * Sets the number of available mods.
     * @param int $numberOfAvailableMods
     * @return $this
     */
    public function setNumberOfAvailableMods(int $numberOfAvailableMods)
    {
        $this->data['available'] = $numberOfAvailableMods;
        return $this;
    }

    /**
     * Returns the number of available mods.
     * @return int
     */
    public function getNumberOfAvailableMods(): int
    {
        return $this->data['available'] ?? 0;
    }

    /**
     * Sets the number of enabled mods.
     * @param int $numberOfEnabledMods
     * @return $this
     */
    public function setNumberOfEnabledMods(int $numberOfEnabledMods)
    {
        $this->data['enabled'] = $numberOfEnabledMods;
        return $this;
    }

    /**
     * Returns the number of enabled mods.
     * @return int
     */
    public function getNumberOfEnabledMods(): int
    {
        return $this->data['enabled'] ?? 0;
    }
}