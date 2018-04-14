(($, fib) => {
    /**
     * The class requesting data from the server for paginated lists.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class PaginatedList {
        /**
         * Initializes the paginated list.
         */
        constructor() {
            /**
             * The buttons which get autoload when coming into the viewport.
             * @type {Array<jQuery>}
             * @private
             */
            this._autoloadingButtons = [];

            this._registerEvents();
            this._handleScroll();
        }

        /**
         * Registers the events of the paginated list.
         * @private
         */
        _registerEvents() {
            $(fib.browser).on('page-change.paginated-list', () => {
                this._detectButtonElements();
                this._handleScroll();
            });
            $(window).on('scroll', fib.helper.debounce(() => {
                this._handleScroll();
            }, 100));
        }

        /**
         * Detects any button elements on the current page, initializing them.
         * @private
         */
        _detectButtonElements() {
            let elements = $('[data-paginated-list]');

            elements
                .off('click.paginated-list')
                .on('click.paginated-list', (event) => {
                    this._requestData($(event.currentTarget));
                });

            this._autoloadingButtons = [];
            $.each(elements, (_, item) => {
                let element = $(item);
                if (element.data('paginated-list-autoload') === 1) {
                    this._autoloadingButtons.push(element);
                }
            });
        }


        /**
         * Requests the data of the specified paginated list button element.
         * @param buttonElement
         * @private
         */
        _requestData(buttonElement) {
            let url = buttonElement.data('paginated-list') || '';
            if (url.length > 0) {
                buttonElement.addClass('loading');
                $.ajax({
                    url: url,
                    method: 'POST',
                    dataType: 'json',
                    success: (data) => {
                        buttonElement.replaceWith(data.content);
                        fib.browser.updateCurrentPageInHistory();
                    },
                    error: () => {
                        buttonElement.remove();
                    }
                });
            }
        }

        /**
         * Handles the scroll event to autoload any paginated lists if needed.
         * @private
         */
        _handleScroll() {
            if (this._autoloadingButtons.length > 0) {
                let windowBottom = $(window).scrollTop() + $(window).height();

                $.each(this._autoloadingButtons, (_, element) => {
                    let buttonTop = element.offset().top;
                    if (buttonTop < windowBottom) {
                        this._requestData(element);
                    }
                });

            }
        }
    }

    fib.PaginatedList = PaginatedList;
})(jQuery, factorioItemBrowser);