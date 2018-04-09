(($, fib) => {
    /**
     * The class managing the basic browsing behavior of the page, using inline replacements to avoid loading the
     * header and footer etc. each time.
     *
     * Available events:
     * - browser-initialize: The browser has been initialize and other features may follow with their late
     *   initialisation.
     * - page-change: The page has changed, i.e. a new page has been opened, or an old page has been re-opened.
     *   The first parameter is the new page object which is applied to the browser.
     *   NOTE: This event is triggered on the initial page as soon as the browser instance is ready.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class Browser {
        /**
         * Initializes the browser.
         */
        constructor() {
            /**
             * The content element.
             * @type {jQuery}
             * @private
             */
            this._contentElement = $('#content');

            /**
             * The target elements to apply the scroll to.
             * @type {jQuery}
             * @private
             */
            this._scrollTarget = $('html,body');

            /**
             * The current page.
             * @type {Page}
             * @private
             */
            this._currentPage = null;

            /**
             * The AJAX of the currently loading page.
             * @type {object|null}
             * @private
             */
            this._loadingPageAjax = null;
        }

        /**
         * Initializes the browser after all other features are already initialised.
         */
        initialize() {
            this._initializeEvents();
            this._initializeCurrentPage();
            this._notifyInitialize();
        }

        /**
         * Initializes the events of the browser.
         * @private
         */
        _initializeEvents() {
            $(window).on('popstate.browser', (event) => {
                this._handlePopState(event);
            });

            $(document.body).on('click.page', 'a[data-link]', (event) => {
                let target = $(event.currentTarget);
                if (!target.hasClass('no-loading-circle')) {
                    fib.loadingCircle.attach(target);
                }
                this.navigateTo(target.attr('href'));
                event.stopPropagation();
                return false;
            });

            $('li[data-locale]').on('click', (event) => {
                this.redirectTo('', {
                    locale: $(event.currentTarget).data('locale')
                });
            });
        }

        /**
         * Initializes the current page.
         * @private
         */
        _initializeCurrentPage() {
            this._currentPage = this._readCurrentPage();
            window.history.replaceState(this._currentPage.exportData(), '', this._currentPage.url);
            fib.cache.write('page', this._currentPage.url, this._currentPage.content);
            this._notifyChange(this._currentPage);
        }

        /**
         * Navigates to the specified URL. This will not force the whole page to change, but only the content.
         * @param {string} url The URL to navigate to.
         * @param {object} [params] The POST parameters to use.
         */
        navigateTo(url, params) {
            this._navigateToPage(url, params, false);
        }

        /**
         * Navigates to the page with the specified URL, requesting the contents from the server.
         * @param {string} url
         * @param {object} [params]
         * @param {boolean} [reuseCurrentPage]
         * @private
         */
        _navigateToPage(url, params = {}, reuseCurrentPage = false) {
            params.context = 'json';

            if (this._loadingPageAjax !== null) {
                this._loadingPageAjax.abort();
                this._loadingPageAjax = null;
            }

            this._loadingPageAjax = $.ajax({
                method: 'POST',
                url: url,
                data: params,
                dataType: 'json',
                success: (data) => {
                    if (typeof(data.redirect) === 'string' && data.redirect.length > 0) {
                        this._navigateToPage(data.redirect, {}, reuseCurrentPage);
                    } else {
                        this._handleSucceededResponse(url, data, reuseCurrentPage);
                    }
                },
                error: (jqXHR) => {
                    if (jqXHR.statusText !== 'abort') {
                        this._handleErroneousResponse(url);
                    }
                },
                complete: () => {
                    this._loadingPageAjax = null;
                }
            });
        }

        /**
         * Handles a succeeded response and applies the received data to the actual page.
         * @param {string} url
         * @param {object} data
         * @param {boolean} reuseCurrentPage
         * @private
         */
        _handleSucceededResponse(url, data, reuseCurrentPage) {
            let newPage = new fib.Entity.Page(data);
            newPage.url = url;

            if (newPage.isValid) {
                if (reuseCurrentPage) {
                    window.history.replaceState(newPage.exportData(), '', newPage.url);
                    fib.cache.write('page', newPage.url, newPage.content);
                } else {
                    this._currentPage.scroll = $(window).scrollTop();
                    window.history.replaceState(this._currentPage.exportData(), '', this._currentPage.url);
                    fib.cache.write('page', this._currentPage.url, this._currentPage.content);

                    window.history.pushState(newPage.exportData(), '', newPage.url);
                    fib.cache.write('page', newPage.url, newPage.content);
                }

                if (typeof(data.newSidebarEntity) === 'string' && data.newSidebarEntity.length > 0) {
                    fib.sidebar.addNewEntity(data.newSidebarEntity);
                }
                this._applyPage(newPage);
                fib.config.change({
                    settingsHash: newPage.settingsHash
                });
            } else {
                this._handleErroneousResponse(url);
            }
        }

        /**
         * Handles an erroneous response when the specified URL has been called.
         * @param {string} url
         * @private
         */
        _handleErroneousResponse(url) {
            this.redirectTo(url);
        }

        /**
         * Redirects the browser to the specified URL, forcing a full reload of the page.
         * @param {string} [url] The URL to redirect to. If empty, the current URL is used.
         * @param {object} [params] The GET parameters to use.
         */
        redirectTo(url, params) {
            let paramString = $.param(params || {});

            if (typeof(url) !== 'string' || url.length === 0) {
                url = window.location.pathname;
            }
            if (paramString.length > 0) {
                url += '?' + paramString;
            }

            window.location.href = url;
        }

        /**
         * Forces a hard refresh of the current page.
         */
        forceRefresh() {
            this.redirectTo();
        }

        /**
         * Handles the popstate event of the window.
         * @param event
         * @private
         */
        _handlePopState(event) {
            let newPage = new fib.Entity.Page(event.originalEvent.state),
                pageContent = fib.cache.read('page', newPage.url);

            newPage.content = pageContent || '';
            if (newPage.settingsHash !== fib.config.settingsHash || typeof(pageContent) === 'undefined') {
                // The settings changed or we did not have the page content in the cache, so reload the page contents.
                this._navigateToPage(newPage.url, {}, true);
            } else if (!newPage.isValid) {
                // State was invalid, so force a full reload of the page.
                this.redirectTo(window.location.pathname);
            } else {
                // We have a valid page, so we can apply it.
                this._applyPage(newPage);
            }
        }

        /**
         * Reads the current page into an entity.
         * @returns {Page}
         * @private
         */
        _readCurrentPage() {
            let page = new fib.Entity.Page();

            page.url = window.location.pathname;
            page.title = document.title;
            page.bodyClass = $(document.body).attr('class');
            page.content = this._contentElement.html();
            page.searchQuery = fib.searchBox.query;
            page.settingsHash = fib.config.settingsHash;
            page.scroll = $(window).scrollTop();

            return page;
        }

        /**
         * Applies the specified page entity to the browser.
         * @param {Page} page
         * @private
         */
        _applyPage(page) {
            document.title = page.title;
            $(document.body).attr('class', page.bodyClass);
            this._contentElement.html(page.content);
            this._scrollTarget.scrollTop(page.scroll);

            this._currentPage = page;
            this._notifyChange(page);
        }

        /**
         * Updates the current page in the history, because it changed of any external reason.
         */
        updateCurrentPageInHistory() {
            let page = this._readCurrentPage();

            window.history.replaceState(page.exportData(), '', page.url);
            fib.cache.write('page', page.url, page.content);
            this._currentPage = page;

            this._notifyChange(page);
        }

        /**
         * Notifies the listeners that browser has been initialized and other features may follow.
         * @private
         */
        _notifyInitialize() {
            $(this).trigger('browser-initialize');
        }

        /**
         * Notifies the listeners that the page has changed.
         * @param {Page} page
         * @private
         */
        _notifyChange(page) {
            $(this).trigger('page-change', page);
        }
    }

    fib.Browser = Browser;
})(jQuery, factorioItemBrowser);