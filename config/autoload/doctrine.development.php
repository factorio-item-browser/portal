<?php

declare(strict_types=1);

/**
 * The development config for doctrine.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

use Doctrine\DBAL\Driver\PDOMySql\Driver as PDOMySqlDriver;

return [
    'doctrine' => [
        'connection' => [
            'orm_default' => [
                'driverClass' => PDOMySqlDriver::class,
                'params' => [
                    'host'     => 'fib-po-mysql',
                    'port'     => '3306',
                    'user'     => 'docker',
                    'password' => 'docker',
                    'dbname'   => 'docker',
                    'driverOptions' => [
                        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    ],
                ],
            ],
        ],
    ],
];