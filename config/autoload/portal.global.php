<?php

declare(strict_types=1);

/**
 * The configuration of the Factorio Item Browser itself.
 *
 * @author BluePsyduck <bluepsyduck@gmx.com>
 * @license http://opensource.org/licenses/GPL-3.0 GPL v3
 */

namespace FactorioItemBrowser\Portal;

use FactorioItemBrowser\Portal\Constant\ConfigKey;

return [
    ConfigKey::PROJECT => [
        ConfigKey::PORTAL => [
            ConfigKey::LOCALES => [
                'af' => 'Afrikaans',
                'ar' => 'العربية',
                'be' => 'беларуская мова',
                'bg' => 'български език',
                'ca' => 'Català',
                'cs' => 'Čeština',
                'da' => 'Dansk',
                'de' => 'Deutsch',
                'el' => 'ελληνικά',
                'en' => 'English',
                'eo' => 'Esperanto',
                'es-ES' => 'Spanish',
                'et' => 'Eesti keel',
                'fi' => 'Suomen kieli',
                'fr' => 'Français',
                'fy-NL' => 'Frysk',
                'ga-IE' => 'Gaeilge',
                'he' => 'עברית',
                'hu' => 'Magyar',
                'it' => 'Italiano',
                'ja' => '日本語',
                'ko' => '한국어',
                'lt' => 'Lietuvių kalba',
                'lv' => 'Latviešu valoda',
                'nl' => 'Nederlands',
                'no' => 'Norsk',
                'pl' => 'Polski',
                'pt-BR' => 'Português brasileiro',
                'pt-PT' => 'Português',
                'ro' => 'Română',
                'ru' => 'русский язык',
                'sk' => 'Slovenčina',
                'sl' => 'Slovenščina',
                'sq' => 'Shqip',
                'sr' => 'српски језик',
                'sv-SE' => 'Svenska',
                'th' => 'ไทย',
                'tr' => 'Türkçe',
                'uk' => 'українська мова',
                'vi' => 'Tiếng Việt',
                'zh-CN' => '简体中文',
                'zh-TW' => '正體中文',
            ],
            ConfigKey::VERSION => time(), // Will be cached on production with the config.
        ],
    ],
];
