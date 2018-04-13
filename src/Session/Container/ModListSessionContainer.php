<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Session\Container;

/**
 * The session container of the mod list page.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class ModListSessionContainer extends AbstractSessionContainer
{
    /**
     * Returns the name of the session container.
     * @return string
     */
    public function getContainerName(): string
    {
        return 'modList';
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

    /**
     * Sets the error message which occurred during upload
     * @param string $uploadErrorMessage
     * @return $this
     */
    public function setUploadErrorMessage(string $uploadErrorMessage)
    {
        $this->data['uploadError'] = $uploadErrorMessage;
        return $this;
    }

    /**
     * Returns the error message which occurred during upload
     * @return string
     */
    public function getUploadErrorMessage(): string
    {
        return $this->data['uploadError'] ?? '';
    }

    /**
     * Sets the names of the mods which are missing in the browser.
     * @param array|string $missingMods
     * @return $this
     */
    public function setMissingModNames(array $missingMods)
    {
        $this->data['missing'] = $missingMods;
        return $this;
    }

    /**
     * Returns the names of the mods which are missing in the browser.
     * @return array|string
     */
    public function getMissingModNames(): array
    {
        return $this->data['missing'] ?? [];
    }
}