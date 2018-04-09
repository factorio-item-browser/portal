(($, fib) => {
    /**
     * The class helping with the media queries and their breakpoints.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class MediaQuery {
        /**
         * Initializes the media query.
         */
        constructor() {
            /**
             * The breakpoints known to the page, sorted in descending order.
             * @type {Array<{name: string, pixel: number}>}
             * @private
             */
            this._breakpoints = this._prepareBreakpoints(fib.config.mediaQuery.breakpoints);

            /**
             * The currently active breakpoint.
             * @type {string}
             * @private
             */
            this._currentBreakpoint = this._detectCurrentBreakpoint();

            $(window).on('resize.mediaQuery', () => {
                this._handleResize();
            });
        }

        /**
         * Prepares the available breakpoints from the config.
         * @private
         */
        _prepareBreakpoints(breakpoints) {
            let result = [];
            $.each(breakpoints, (name, pixel) => {
                result.push({
                    name: name,
                    pixel: pixel
                });
            });

            result.sort((left, right) => {
                return left.pixel < right.pixel;
            });

            return result;
        }

        /**
         * Detects the breakpoint currently active.
         * @returns {string}
         * @private
         */
        _detectCurrentBreakpoint() {
            let width = $(window).width(),
                result = '';

            $.each(this._breakpoints, (_, breakpoint) => {
                if (width >= breakpoint.pixel) {
                    result = breakpoint.name;
                }
                return result === '';
            });

            return result;
        }

        /**
         * Handles the resize event.
         * @private
         */
        _handleResize() {
            let oldBreakpoint = this._currentBreakpoint,
                newBreakpoint = this._detectCurrentBreakpoint();

            if (newBreakpoint !== oldBreakpoint) {
                this._currentBreakpoint = newBreakpoint;
                $(this).trigger('breakpoint-change', [newBreakpoint, oldBreakpoint]);
            }
        }

        /**
         * Checks whether the specified breakpoint or a lower one is currently active.
         * @param {string} breakpointName
         * @returns {boolean}
         */
        isBreakpointOrLowerActive(breakpointName) {
            return (this._currentBreakpoint === breakpointName)
                || (this._isBreakpointGreaterThanCurrent(breakpointName) === false);
        }

        /**
         * Checks whether the specified breakpoint or a greater one is currently active.
         * @param {string} breakpointName
         * @returns {boolean}
         */
        isBreakpointOrGreaterActive(breakpointName) {
            return (this._currentBreakpoint === breakpointName)
                || (this._isBreakpointGreaterThanCurrent(breakpointName) === true);
        }

        /**
         * Checks whether the specified breakpoint is greater than the current one.
         * @param {string} breakpointName
         * @returns {boolean|undefined}
         * @private
         */
        _isBreakpointGreaterThanCurrent(breakpointName) {
            let sawCurrentBreakpoint = false,
                result;

            $.each(this._breakpoints, (_, breakpoint) => {
                let doContinue = true;

                if (breakpoint.name === this._currentBreakpoint) {
                    sawCurrentBreakpoint = true;
                } else if (breakpoint.name === breakpointName) {
                    result = sawCurrentBreakpoint;
                    doContinue = false;
                }
                return doContinue;
            });

            return result;
        }
    }

    fib.MediaQuery = MediaQuery;
})(jQuery, factorioItemBrowser);