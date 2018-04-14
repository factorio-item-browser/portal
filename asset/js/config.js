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
             * The URLs used by the sidebar.
             * @type {Object}
             */
            urls: {
                /**
                 * The URL to pin an entity to the top of the sidebar.
                 * @type {string}
                 */
                pin: '',

                /**
                 * The URL to unpin an entity from the sidebar.
                 * @type {string}
                 */
                unpin: ''
            }
        },

        /**
         * The version to use for the assets.
         * @type {string}
         */
        version: '',

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