<?php

declare(strict_types=1);

/**
 * The configuration of the Doctrine module.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Api\Server;

use Doctrine\Common\Persistence\Mapping\Driver\MappingDriverChain;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
    'doctrine' => [
        'configuration' => [
            'orm_default' => [
                'metadata_cache' => 'filesystem',
                'query_cache' => 'filesystem',
            ],
        ],
        'driver' => [
            'orm_default' => [
                'class' => MappingDriverChain::class,
                'drivers' => [
                    'FactorioItemBrowser\Portal\Database\Entity' => 'fib-portal',
                ],
            ],

            'fib-portal' => [
                'class' => AnnotationDriver::class,
                'cache' => 'filesystem',
                'paths' => [
                    __DIR__ . '/../../src/Database/Entity',
                ],
            ],
        ],
    ],
];
