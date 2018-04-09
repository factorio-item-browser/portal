<?php

declare(strict_types=1);

/**
 * The configuration of the dependencies.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

use Blast\BaseUrl\BasePathHelper;
use Blast\BaseUrl\BaseUrlMiddleware;
use Blast\BaseUrl\BaseUrlMiddlewareFactory;
use ContainerInteropDoctrine\EntityManagerFactory;
use Doctrine\ORM\EntityManager;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'dependencies' => [
        'factories'  => [
            Handler\HelloWorldHandler::class => Handler\HelloWorldHandlerFactory::class,

            // Dependencies of other libraries
            BasePathHelper::class => InvokableFactory::class,
            BaseUrlMiddleware::class => BaseUrlMiddlewareFactory::class,
            EntityManager::class => EntityManagerFactory::class,
        ]
    ],
];
