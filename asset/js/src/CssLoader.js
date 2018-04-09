(($, fib) => {
    /**
     * The class for loading additional CSS files after the main page finished loading.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class CssLoader {
        /**
         * Initializes the CSS loader.
         */
        constructor() {
            /**
             * The base URL to sue for the CSS files.
             * @type {string}
             * @private
             */
            this._url = fib.config.cssLoader.url;

            $(fib.browser).on('browser-initialize.css-loader', () => {
                this._load();
            });
        }

        /**
         * Loads all the additionally needed CSS files.
         * @private
         */
        _load() {
            let head = $('head');

            if (this._url.length > 0) {
                let link = $('<link>');
                link.attr({
                    rel: 'stylesheet',
                    href: this._url,
                    type: 'text/css'
                });
                head.append(link);
            }
        }
    }

    fib.CssLoader = CssLoader;
})(jQuery, factorioItemBrowser);