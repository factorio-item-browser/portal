<?php

declare(strict_types=1);

/**
 * The configuration file only used during development.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;
use PDO;
use Zend\ConfigAggregator\ConfigAggregator;

return [
    ConfigAggregator::ENABLE_CACHE => false,
    'debug' => true,
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => 'mysql',
                    'port'     => '3306',
                    'user'     => 'factorio-item-browser',
                    'password' => 'factorio-item-browser',
                    'dbname'   => 'factorio-item-browser',
                    'driverOptions' => [
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
                    ]
                ]
            ]
        ],
    ],
    'router' => [
        'fastroute' => [
            'cache_enabled' => false
        ]
    ],
];