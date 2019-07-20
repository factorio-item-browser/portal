<?php

declare(strict_types=1);

/**
 * The development config for Zend Expressive.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

use Zend\ConfigAggregator\ConfigAggregator;

return [
    ConfigAggregator::ENABLE_CACHE => false,
    'debug' => true,

    'router' => [
        'fastroute' => [
            'cache_enabled' => false,
        ],
    ],
];
