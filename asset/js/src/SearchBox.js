(($, fib) => {
    /**
     * The class managing the search box in the header of the page, including requesting more results.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class SearchBox {
        /**
         * Initializes the search box.
         * @param {jQuery} container The container element of the tooltip.
         */
        constructor(container) {
            /**
             * The elements representing the search box.
             * @type {{container: jQuery, input: jQuery, loadingIndicator: jQuery, toggle: jQuery}}
             * @private
             */
            this._elements = {
                container: container,
                input: container.find('.header-search-input'),
                icon: container.find('.header-search-box-icon'),
                toggle: $('#toggle-search-box')
            };

            /**
             * The URL to use for requesting the search results.
             * @type {string}
             * @private
             */
            this._searchUrl = container.data('url');

            /**
             * The current query of the search box.
             * @type {string}
             * @private
             */
            this._currentQuery = this._elements.input.val();

            this._registerEvents();
        }


        /**
         * Sets the specified query into the search box. This will not trigger a change event.
         * @param {string} query
         */
        set query(query) {
            this._currentQuery = query;
            this._elements.input.val(query);
        }

        /**
         * Returns the current query of the search box.
         * @returns {string}
         */
        get query() {
            return this._currentQuery;
        }

        /**
         * Registers the events for the search box.
         * @private
         */
        _registerEvents() {
            this._elements.container.on('submit.searchBox', (event) => {
                this._handleChange();
                event.stopPropagation();
                return false;
            });

            this._elements.input.on('keyup.searchBox change.searchBox', fib.helper.debounce(() => {
                this._handleChange();
            }, 500));

            $(fib.browser).on('page-change.searchBox', (event, page) => {
                this._handlePageChange(page);
            });

            this._elements.toggle.on('change', () => {
                if (this._elements.toggle.prop('checked')) {
                    this._elements.input.focus();
                }
            });
        }

        /**
         * Handles the change of the input element.
         * @private
         */
        _handleChange() {
            let query = this._elements.input.val();
            if (query.length >= 2 && query !== this._currentQuery) {
                this._elements.icon.addClass('loading');
                fib.browser.navigateTo(this._createUrl(query));
                this._currentQuery = query;
            }
        }

        /**
         * Handles the change of the page.
         * @param {Page} page
         * @private
         */
        _handlePageChange(page) {
            this._elements.icon.removeClass('loading');
            if (!this._elements.input.is(':focus')) {
                this._elements.input.val(page.searchQuery);
                this._currentQuery = page.searchQuery;
            }
        }

        /**
         * Creates and returns the URL to call the specified query.
         * @param {string} query
         * @returns {string}
         * @private
         */
        _createUrl(query) {
            return this._searchUrl.replace('--query--', encodeURIComponent(query));
        }
    }

    fib.SearchBox = SearchBox;
})(jQuery, factorioItemBrowser);