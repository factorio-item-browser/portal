(($, fib) => {
    /**
     * The class handling the showing of a tooltip to certain elements, holding additional information for the user.
     *
     * This class requires elements with the following classes to be placed into the container:
     * - tooltip-content: The element holding the actual HTML content.
     * - tooltip-chevron: The chevron used to point to the target element.
     *
     * Additionally, this class will listen for the following attributes within the whole document:
     * - data-tooltip: An element which will get a tooltip on hover. Teh value is the (partial) route to request the
     *   tooltip contents.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class Tooltip {
        /**
         * Initializes the tooltip.
         * @param {jQuery} container The container element of the tooltip.
         */
        constructor(container) {
            /**
             * The elements manipulated by the tooltip.
             * @type {{container: jQuery, content: jQuery, chevron: jQuery}}
             * @private
             */
            this._elements = {
                container: container,
                content: container.find('.tooltip-content'),
                chevron: container.find('.tooltip-chevron')
            };

            /**
             * The dimensions of the chevron.
             * @type {{chevronHeight: number, chevronWidth: number, chevronOffset: number}}
             * @private
             */
            this._distances = {
                chevronHeight: 0,
                chevronWidth: 0,
                chevronOffset: 0
            };

            /**
             * The currently running AJAX.
             * @type {object|null}
             * @private
             */
            this._runningAjax = null;

            this._calculateChevronDimensions();
            this._registerEventListeners();
        }

        /**
         * Calculates the dimensions of the chevron.
         * @private
         */
        _calculateChevronDimensions() {
            let chevron = this._elements.chevron;

            this._elements.container.removeClass('hidden');
            this._distances.chevronHeight = chevron.outerHeight();
            this._distances.chevronWidth = chevron.outerWidth();
            this._distances.chevronOffset = (chevron.outerWidth(true) - chevron.outerWidth()) / 2;
            this._elements.container.addClass('hidden');
        }

        /**
         * Registers the event listeners to show and hide the tooltip.
         * @private
         */
        _registerEventListeners() {
            $(document.body)
                .on('mouseover.tooltip', '[data-tooltip]', (event) => {
                    let target = $(event.currentTarget);
                    target.addClass('has-tooltip');
                    this.requestAndShow(target);
                })
                .on('mouseout.tooltip', '[data-tooltip]', (event) => {
                    let target = $(event.currentTarget);
                    if (target.hasClass('has-tooltip')) {
                        target.removeClass('has-tooltip');
                        this.hide();
                    }
                    event.stopPropagation();
                    return false;
                });

            $(fib.browser).on('page-change.tooltip', () => {
                this.hide();
            });
        }

        /**
         * Shows the tooltip for the specified target.
         * @param {jQuery} target The target to show the tooltip for.
         * @param {string} content The actual content use for the tooltip.
         */
        show(target, content) {
            let anchor = target.find('[data-tooltip-anchor]'),
                targetDimensions = Tooltip._getDimensions(anchor.length === 0 ? target : anchor),
                windowDimensions = Tooltip._getWindowDimensions(),
                isTooltipOnTop = false,
                contentDimensions,
                calculatedTop,
                calculatedLeft;

            this._elements.content.html(content);
            this._elements.container.removeClass('hidden');

            contentDimensions = Tooltip._getDimensions(this._elements.content);

            calculatedTop = targetDimensions.top + targetDimensions.height + this._distances.chevronHeight;
            if (calculatedTop + contentDimensions.height + this._distances.chevronHeight > windowDimensions.bottom) {
                calculatedTop = targetDimensions.top - contentDimensions.height - this._distances.chevronHeight;
                isTooltipOnTop = true;
            }
            calculatedLeft = targetDimensions.left + targetDimensions.width / 2
                - this._distances.chevronWidth / 2 - this._distances.chevronOffset;

            if (calculatedLeft + contentDimensions.width + this._distances.chevronWidth > windowDimensions.right) {
                calculatedLeft = targetDimensions.left + targetDimensions.width / 2 - contentDimensions.width
                    + this._distances.chevronWidth / 2 + this._distances.chevronOffset;
                this._elements.chevron.css({right: 0});
            } else {
                this._elements.chevron.css({right: 'auto'});
            }

            this._elements.chevron.toggleClass('up', !isTooltipOnTop)
                .toggleClass('down', isTooltipOnTop);

            this._elements.container.css({
                top: calculatedTop,
                left: calculatedLeft
            });
            fib.browser.notifyPartialPageChange();
        }

        /**
         * Hides the tooltip if it is currently shown.
         */
        hide() {
            this._elements.container.addClass('hidden');
        }

        /**
         * Requests the tooltip data from the server, and shows it for the specified target.
         * @param {jQuery} target The target to show the tooltip for.
         * @param {string} [route] The route to use for the server request. If empty, the data-tooltip attribute of the
         * target will be used.
         */
        requestAndShow(target, route) {
            if (typeof(route) !== 'string') {
                route = target.data('tooltip');
            }
            if (typeof(route) === 'string' && route.length > 0
                && fib.mediaQuery.isBreakpointOrGreaterActive('medium')
            ) {
                let cacheValue = fib.cache.read('tooltip', route);

                if (typeof(cacheValue) === 'string') {
                    this.show(target, cacheValue);
                } else {
                    this.hide();
                    if (this._runningAjax !== null) {
                        this._runningAjax.abort();
                        this._runningAjax = null;
                    }

                    this._runningAjax = $.ajax({
                        url: route,
                        method: 'POST',
                        dataType: 'json',
                        success: (response) => {
                            if (typeof(response) === 'object' && typeof(response.content) === 'string') {
                                fib.cache.write('tooltip', route, response.content);
                                if (target.hasClass('has-tooltip')) {
                                    this.show(target, response.content);
                                }
                            }
                        },
                        complete: () => {
                            this._runningAjax = null;
                        }
                    });
                }
            }
        }

        /**
         * Returns the dimensions of the specified element.
         * @param {jQuery} element
         * @returns {{left: number, top: number, right: number, bottom: number, width: number, height: number}}
         * @private
         */
        static _getDimensions(element) {
            let offset = element.offset(),
                width = element.width(),
                height = element.height();

            return {
                left: offset.left,
                top: offset.top,
                right: offset.left + width,
                bottom: offset.top + height,
                width: width,
                height: height
            };
        }

        /**
         * Returns the dimensions of the window itself.
         * @returns {{left: number, top: number, right: number, bottom: number, width: number, height: number}}
         * @private
         */
        static _getWindowDimensions() {
            let win = $(window),
                left = win.scrollLeft(),
                top = win.scrollTop(),
                width = win.width(),
                height = win.height();

            return {
                left: left,
                top: top,
                right: left + width,
                bottom: top + height,
                width: width,
                height: height
            }
        }
    }

    fib.Tooltip = Tooltip;
})(jQuery, factorioItemBrowser);