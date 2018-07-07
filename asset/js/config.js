// Note: File must be ES5 compatible.
(function($, fib) {
    /**
     * The config for all the Javascript classes. Most values will actually be set through an inline Javascript snippet.
     * @type {Object}
     */
    fib.config = {
        /**
         * The config of the icon manager.
         * @type {object}
         */
        iconManager: {
            /**
             * The URL to request missing icons.
             * @type {string}
             */
            url: ''
        },

        /**
         * The config of the media queries.
         * @type {object}
         */
        mediaQuery: {
            /**
             * The breakpoints known to the page.
             * Must stay in sync with SCSS config at module/Portal/assets/scss/modules/vars/_foundation.scss
             * @type {object}
             */
            breakpoints: {
                small: 0,
                medium: 600,
                large: 1000,
                xlarge: 1500
            }
        },

        /**
         * The scripts to load.
         * @type {object}
         */
        script: {
            /**
             * The default script to load for ES6 compatible browsers.
             * @type {string}
             */
            default: '',

            /**
             * The fallback script to load for ES6-incompatible browsers.
             * @type {string}
             */
            fallback: ''
        },

        /**
         * The hash of the settings to detect any changes.
         * @type {string}
         */
        settingsHash: '',

        /**
         * The config of the sidebar.
         * @type {Object}
         */
        sidebar: {
            /**
             * The number of maximal allowed unpinned entities.
             * @type {number}
             */
            numberOfUnpinnedEntities: 10,

            /**
             * The URL to set the order of pinned entities.
             * @type {string}
             */
            pinnedUrl: ''
        },

        /**
         * Changes the config to the specified values.
         * @param {Object} newConfig
         */
        change: function(newConfig) {
            $.extend(true, this, newConfig);
            $(this).trigger('config-change', [newConfig]);
        }
    };

    if (typeof(window.factorioItemBrowserConfig) === 'object') {
        fib.config.change(window.factorioItemBrowserConfig);
        delete window.factorioItemBrowserConfig;
    }
})(jQuery, factorioItemBrowser);