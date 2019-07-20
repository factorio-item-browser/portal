<?php

declare(strict_types=1);

/**
 * The configuration of the Zend Translator.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

return [
    'translator' => [
        'locale' => 'en',
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../../language',
                'pattern'  => '%s.lang.php',
            ],
        ],
    ],
];
