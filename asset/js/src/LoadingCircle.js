(($, fib) => {
    /**
     * The class managing the showing of a little loading indicator.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class LoadingCircle {
        /**
         * Initializes the loading circle.
         * @param {jQuery} container
         */
        constructor(container) {
            /**
             * The container representing the loading container.
             * @type {jQuery}
             * @private
             */
            this._container = container;

            this._registerEvents();
        }

        /**
         * Registers the events to listen for.
         * @private
         */
        _registerEvents() {
            $(fib.browser).on('page-change.loading-circle', () => {
                this._container.addClass('hidden');
            });
        }

        /**
         * Attaches the loading circle to the specified target.
         * @param {jQuery} target
         */
        attach(target) {
            let offset = target.offset();

            this._container.css({
                left: offset.left,
                top: offset.top,
                width: target.outerWidth(),
                height: target.outerHeight()
            });
            this._container.removeClass('hidden');
        }
    }

    fib.LoadingCircle = LoadingCircle;
})(jQuery, factorioItemBrowser);