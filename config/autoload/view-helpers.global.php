<?php

declare(strict_types=1);

/**
 * The configuration of the view helpers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'view_helpers' => [
        'aliases' => [
            'assetPath' => View\Helper\AssetPathHelper::class,
            'footLink' => View\Helper\FootLinkHelper::class,
            'format' => View\Helper\FormatHelper::class,
            'genericEntity' => View\Helper\GenericEntityHelper::class,
            'headLink' => View\Helper\HeadLinkHelper::class,
            'javascriptConfig' => View\Helper\JavascriptConfigHelper::class,
            'locale' => View\Helper\LocaleHelper::class,
            'layoutParams' => View\Helper\LayoutParamsHelper::class,
            'replace' => View\Helper\ReplaceHelper::class,
            'settings' => View\Helper\SettingsHelper::class,
            'sidebar' => View\Helper\SidebarHelper::class,
        ],
        'factories' => [
            View\Helper\AssetPathHelper::class => View\Helper\AssetPathHelperFactory::class,
            View\Helper\FootLinkHelper::class => InvokableFactory::class,
            View\Helper\FormatHelper::class => View\Helper\FormatHelperFactory::class,
            View\Helper\GenericEntityHelper::class => View\Helper\GenericEntityHelperFactory::class,
            View\Helper\HeadLinkHelper::class => InvokableFactory::class,
            View\Helper\JavascriptConfigHelper::class => View\Helper\JavascriptConfigHelperFactory::class,
            View\Helper\LayoutParamsHelper::class => InvokableFactory::class,
            View\Helper\LocaleHelper::class => View\Helper\LocaleHelperFactory::class,
            View\Helper\ReplaceHelper::class => InvokableFactory::class,
            View\Helper\SettingsHelper::class => View\Helper\SettingsHelperFactory::class,
            View\Helper\SidebarHelper::class => View\Helper\SidebarHelperFactory::class,
        ],
    ]
];