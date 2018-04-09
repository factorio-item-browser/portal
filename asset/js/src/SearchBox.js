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
             * @type {{container: jQuery, input: jQuery, loadingIndicator: jQuery, moreResultsButton: jQuery, toggle: jQuery}}
             * @private
             */
            this._elements = {
                container: container,
                input: container.find('.header-search-input'),
                icon: container.find('.header-search-box-icon'),
                moreResultsButton: this._getMoreResultsElement(),
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
            this._handleScroll();
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

            $(window).on('scroll', fib.helper.debounce(() => {
                this._handleScroll();
            }, 100));

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
         * Returns the element to load more results.
         * @returns {jQuery}
         * @private
         */
        _getMoreResultsElement() {
            let element = $('.search-results-more');
            if (element.length > 0) {
                element.off('click.searchBox')
                    .on('click.searchBox', () => {
                        this._requestMoreResults();
                    });
            }
            return element;
        }

        /**
         * Handles the click on the "more results" button.
         * @private
         */
        _requestMoreResults() {
            let element = this._elements.moreResultsButton,
                url = element.data('url');

            if (element.length > 0 && typeof(url) === 'string' && url.length > 0) {
                element.addClass('loading');
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'json',
                    success: (data) => {
                        element.replaceWith(data.content);
                        fib.browser.updateCurrentPageInHistory();
                    },
                    error: () => {
                        element.remove();
                    }
                });
            }
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

            this._elements.moreResultsButton = this._getMoreResultsElement();
            this._handleScroll();
        }

        /**
         * Handles the scroll event.
         * @private
         */
        _handleScroll() {
            if (this._elements.moreResultsButton.length > 0) {
                let buttonTop = this._elements.moreResultsButton.offset().top,
                    windowBottom = $(window).scrollTop() + $(window).height();

                if (buttonTop < windowBottom) {
                    this._requestMoreResults();
                }
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