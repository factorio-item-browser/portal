(($, fib) => {
    /**
     * The entity class representing a page shown in the browser.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class Page {
        /**
         * Initializes the entity.
         * @param {Object} [data]
         */
        constructor(data = {}) {
            /**
             * The URL of the page.
             * @type {string}
             */
            this.url = data.url || '/';

            /**
             * The title of the page.
             * @type {string}
             */
            this.title = data.title || '';

            /**
             * The CSS class to use on the body.
             * @type {string}
             */
            this.bodyClass = data.bodyClass || '';

            /**
             * The content of the page.
             * @type {string}
             */
            this.content = data.content || '';

            /**
             * The query of the search box.
             * @type {string}
             */
            this.searchQuery = data.searchQuery || '';

            /**
             * The hash of the current settings.
             * @type {string}
             */
            this.settingsHash = data.settingsHash || '';

            /**
             * The value of the scrollbar.
             * @type {number}
             */
            this.scroll = data.scroll || 0;
        }

        /**
         * Returns whether the page is considered valid.
         * @returns {boolean}
         */
        get isValid() {
            return this.content.length > 0;
        }

        /**
         * Exports the page data to a simple object.
         * @returns {Object}
         */
        exportData() {
            return {
                url: this.url,
                title: this.title,
                bodyClass: this.bodyClass,
                searchQuery: this.searchQuery,
                settingsHash: this.settingsHash,
                scroll: this.scroll
            };
        }
    }

    fib.Entity.Page = Page;
})(jQuery, factorioItemBrowser);