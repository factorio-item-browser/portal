<?php

declare(strict_types=1);

/**
 * The configuration of the API client.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

use FactorioItemBrowser\Api\Client\Constant\ConfigKey;

return [
    ConfigKey::PROJECT => [
        ConfigKey::API_CLIENT => [
            ConfigKey::CACHE_DIR => 'data/cache/api-client',
            ConfigKey::OPTIONS => [
                ConfigKey::OPTION_API_URL => 'http://fib-as-php',
                ConfigKey::OPTION_AGENT => 'debug',
                ConfigKey::OPTION_ACCESS_KEY => 'factorio-item-browser',
                ConfigKey::OPTION_TIMEOUT => 10,
            ],
        ],
    ],
];