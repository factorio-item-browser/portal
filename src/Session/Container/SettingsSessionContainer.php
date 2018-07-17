<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Session\Container;

/**
 * The session container of the settings page.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SettingsSessionContainer extends AbstractSessionContainer
{
    /**
     * Returns the name of the session container.
     * @return string
     */
    public function getContainerName(): string
    {
        return 'settings';
    }

    /**
     * Sets whether to show the success message.
     * @param bool $showSuccessMessage
     * @return $this
     */
    public function setShowSuccessMessage(bool $showSuccessMessage)
    {
        $this->data['success'] = $showSuccessMessage;
        return $this;
    }

    /**
     * Returns whether to show the success message.
     * @return bool
     */
    public function getShowSuccessMessage(): bool
    {
        return $this->data['success'] ?? false;
    }
}
