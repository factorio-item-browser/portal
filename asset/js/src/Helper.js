(($, fib) => {
    /**
     * A class with random helper functions.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class Helper {
        /**
         * Initializes the helper.
         */
        constructor() {
            /**
             * The factor used to calculate rem.
             * @type {number}
             * @private
             */
            this._remFactor = parseInt($('html').css('font-size') || 1, 10);
        }

        /**
         * Converts the specified rem value to pixels.
         * @param {number} rem
         * @returns {number}
         */
        convertRemToPixel(rem) {
            return rem * this._remFactor;
        }

        /**
         * Converts the specified pixel value to rem.
         * @param {number} pixel
         * @returns {number}
         */
        convertPixelToRem(pixel) {
            return pixel / this._remFactor;
        }

        /**
         * Debounces the specified function, having it executed when the specified delay passed without getting called
         * again.
         * @param {Function} callback The callback to execute after debouncing the event.
         * @param {number} delay The delay in milliseconds to wait.
         * @returns {Function}
         */
         debounce(callback, delay) {
            let timer = null;

            return () => {
                let args = arguments;

                if (timer !== null) {
                    window.clearTimeout(timer);
                    timer = null;
                }
                timer = window.setTimeout(() => {
                    callback.apply(this, args);
                    timer = null;
                }, delay);
            };
        }
    }

    fib.Helper = Helper;
})(jQuery, factorioItemBrowser);
