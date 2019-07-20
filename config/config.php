<?php

declare(strict_types=1);

/**
 * The file loading the actual configuration of the project.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

use Blast;
use FactorioItemBrowser;
use Zend\ConfigAggregator\ArrayProvider;
use Zend\ConfigAggregator\ConfigAggregator;
use Zend\ConfigAggregator\PhpFileProvider;
use Zend;

$cacheConfig = [
    'config_cache_path' => 'data/cache/config-cache.php',
];

$aggregator = new ConfigAggregator([
    // Include cache configuration
    new ArrayProvider($cacheConfig),

    Zend\Expressive\Helper\ConfigProvider::class,
    Zend\Expressive\ConfigProvider::class,
    Zend\Expressive\Router\ConfigProvider::class,
    Zend\Expressive\Router\FastRouteRouter\ConfigProvider::class,
    Zend\Expressive\ZendView\ConfigProvider::class,
    Zend\HttpHandlerRunner\ConfigProvider::class,
    Zend\I18n\ConfigProvider::class,
    Zend\Log\ConfigProvider::class,
    Blast\BaseUrl\ConfigProvider::class,

    FactorioItemBrowser\Api\Client\ConfigProvider::class,

    // Load application config in a pre-defined order in such a way that local settings
    // overwrite global settings. (Loaded as first to last):
    //   - `global.php`
    //   - `*.global.php`
    //   - `local.php`
    //   - `*.local.php`
    //   - `[FIB_ENV].php`
    //   - `*.[FIB_ENV].php`
    new PhpFileProvider(
        realpath(__DIR__) . sprintf('/autoload/{{,*.}global,{,*.}local,{,*.}%s}.php', getenv('FIB_ENV') ?: 'production')
    ),
], $cacheConfig['config_cache_path']);

return $aggregator->getMergedConfig();
