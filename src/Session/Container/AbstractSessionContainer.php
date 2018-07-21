<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Session\Container;

/**
 * The abstract class of the session containers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
abstract class AbstractSessionContainer
{
    /**
     * The actual data of the session container.
     * @var array
     */
    protected $data = [];

    /**
     * Returns the name of the session container.
     * @return string
     */
    abstract public function getContainerName(): string;

    /**
     * Sets the actual data of the session container.
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        $this->data = $data;
        return $this;
    }

    /**
     * Returns the actual data of the session container.
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Resets the session container, clearing all data.
     * @return $this
     */
    public function reset()
    {
        $this->data = [];
        return $this;
    }
}
