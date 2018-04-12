<?php

declare(strict_types=1);

/**
 * The configuration of the view helpers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

use Blast\BaseUrl\BasePathViewHelper;
use Blast\BaseUrl\BasePathViewHelperFactory;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'view_helpers' => [
        'aliases' => [
            'assetPath' => View\Helper\AssetPathHelper::class,
            'footLink' => View\Helper\FootLinkHelper::class,
            'genericEntity' => View\Helper\GenericEntityHelper::class,
            'headLink' => View\Helper\HeadLinkHelper::class,
            'javascriptConfig' => View\Helper\JavascriptConfig::class,
            'locale' => View\Helper\LocaleHelper::class,
            'layoutParams' => View\Helper\LayoutParamsHelper::class,
            'replace' => View\Helper\ReplaceHelper::class,
            'sidebar' => View\Helper\SidebarHelper::class,

            // 3rd-party helpers
            'basePath' => BasePathViewHelper::class,
        ],
        'factories' => [
            View\Helper\AssetPathHelper::class => View\Helper\AssetPathHelperFactory::class,
            View\Helper\FootLinkHelper::class => InvokableFactory::class,
            View\Helper\GenericEntityHelper::class => View\Helper\GenericEntityHelperFactory::class,
            View\Helper\HeadLinkHelper::class => InvokableFactory::class,
            View\Helper\JavascriptConfig::class => View\Helper\JavascriptConfigFactory::class,
            View\Helper\LayoutParamsHelper::class => InvokableFactory::class,
            View\Helper\LocaleHelper::class => View\Helper\LocaleHelperFactory::class,
            View\Helper\ReplaceHelper::class => InvokableFactory::class,
            View\Helper\SidebarHelper::class => View\Helper\SidebarHelperFactory::class,

            // 3rd-party helpers
            BasePathViewHelper::class => BasePathViewHelperFactory::class,
        ],
    ]
];