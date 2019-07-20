<?php

declare(strict_types=1);

/**
 * The configuration of the view helpers.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

use BluePsyduck\ZendAutoWireFactory\AutoWireFactory;

return [
    'view_helpers' => [
        'aliases' => [
            'assetPath'        => View\Helper\AssetPathHelper::class,
            'footLink'         => View\Helper\FootLinkHelper::class,
            'format'           => View\Helper\FormatHelper::class,
            'genericEntity'    => View\Helper\GenericEntityHelper::class,
            'headLink'         => View\Helper\HeadLinkHelper::class,
            'javascriptConfig' => View\Helper\JavascriptConfigHelper::class,
            'layoutParams'     => View\Helper\LayoutParamsHelper::class,
            'recipe'           => View\Helper\RecipeHelper::class,
            'replace'          => View\Helper\ReplaceHelper::class,
            'settings'         => View\Helper\SettingsHelper::class,
            'sidebar'          => View\Helper\SidebarHelper::class,
        ],
        'factories' => [
            View\Helper\AssetPathHelper::class        => AutoWireFactory::class,
            View\Helper\FootLinkHelper::class         => AutoWireFactory::class,
            View\Helper\FormatHelper::class           => AutoWireFactory::class,
            View\Helper\GenericEntityHelper::class    => AutoWireFactory::class,
            View\Helper\HeadLinkHelper::class         => AutoWireFactory::class,
            View\Helper\JavascriptConfigHelper::class => AutoWireFactory::class,
            View\Helper\LayoutParamsHelper::class     => AutoWireFactory::class,
            View\Helper\RecipeHelper::class           => AutoWireFactory::class,
            View\Helper\ReplaceHelper::class          => AutoWireFactory::class,
            View\Helper\SettingsHelper::class         => AutoWireFactory::class,
            View\Helper\SidebarHelper::class          => AutoWireFactory::class,
        ],
    ],
];
