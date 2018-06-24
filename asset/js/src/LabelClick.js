(($, fib) => {
    /**
     * The class making it possible to click on larger labels for checkboxes and radio buttons.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class LabelClick {
        /**
         * Initializes the loading circle.
         */
        constructor() {
            this._registerEvents();
        }

        /**
         * Registers the events to listen for.
         * @private
         */
        _registerEvents() {
            $(fib.browser).on('page-change.label-click', () => {
                this._initializeElements();
            });
        }

        /**
         * Initializes the elements emulating a label click.
         * @private
         */
        _initializeElements() {
            $('[data-label-for]').on('click', (event) => {
                $('#' + $(event.currentTarget).data('label-for')).trigger('click');
            });
        }
    }

    fib.LabelClick = LabelClick;
})(jQuery, factorioItemBrowser);