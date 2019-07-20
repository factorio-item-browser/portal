<?php

declare(strict_types=1);

namespace FactorioItemBrowser\Portal\Constant;

/**
 * The interface holding the keys of the config.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */
interface ConfigKey
{
    /**
     * The key holding the name of the project.
     */
    public const PROJECT = 'factorio-item-browser';

    /**
     * The key of the portal itself.
     */
    public const PORTAL = 'portal';

    /**
     * The key holding the locales available in the settings.
     */
    public const LOCALES = 'locales';

    /**
     * The key holding the current version of the portal.
     */
    public const VERSION = 'version';
}
