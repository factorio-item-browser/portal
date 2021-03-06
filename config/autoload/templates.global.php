<?php

declare(strict_types=1);

/**
 * The configuration of the templates.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

return [
    'templates' => [
        'layout' => 'layout::layout',
        'paths' => [
            'error' => ['template/error'],
            'helper' => ['template/helper'],
            'layout' => ['template/layout'],
            'portal' => ['template/portal']
        ]
    ]
];