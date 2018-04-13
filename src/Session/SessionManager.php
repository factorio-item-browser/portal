<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Session;

use FactorioItemBrowser\Portal\Session\Container\AbstractSessionContainer;

/**
 * The manager of the session and its containers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
class SessionManager
{
    /**
     * The containers used in the session.
     * @var array|AbstractSessionContainer[]
     */
    protected $containers = [];

    /**
     * The actual session data of the current user.
     * @var array
     */
    protected $sessionData = [];

    /**
     * Sets the actual session data of the current user.
     * @param array $sessionData
     * @return $this
     */
    public function setSessionData(array $sessionData)
    {
        $this->sessionData = $sessionData;
        foreach ($this->containers as $container) {
            $this->initializeContainer($container);
        }
        return $this;
    }

    /**
     * Initializes the specified container.
     * @param AbstractSessionContainer $container
     * @return $this
     */
    public function initializeContainer(AbstractSessionContainer $container)
    {
        $name = $container->getContainerName();
        $this->containers[$name] = $container;
        $container->setData($this->sessionData[$name] ?? []);
        return $this;
    }

    /**
     * Returns the actual session data of the current user.
     * @return array
     */
    public function getSessionData(): array
    {
        $data = $this->sessionData;
        foreach ($this->containers as $container) {
            $data[$container->getContainerName()] = $container->getData();
        }
        return array_filter($data);
    }
}