(($, fib) => {
    /**
     * The class handling the sticky submit button.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class StickySubmitButton {
        /**
         * Initializes the loading circle.
         */
        constructor() {
            /**
             * The sticky button of the current page.
             * @type {jQuery|null}
             * @private
             */
            this._button = $();

            /**
             * Whether the button is currently visible.
             * @type {boolean}
             * @private
             */
            this._isButtonVisible = false;

            this._registerEvents();
        }

        /**
         * Registers the events to listen for.
         * @private
         */
        _registerEvents() {
            $(fib.browser).on('page-change.sticky-submit-button', () => {
                this._initializeElements();
            });
            $(window).on('scroll.sticky-submit-button resize.sticky-submit-button', () => {
                this.refresh();
            });
        }

        /**
         * Initializes the elements emulating a label click.
         * @private
         */
        _initializeElements() {
            let element = $('.sticky-submit-button');
            if (element.length > 0) {
                this._button = element;
                element.closest('form').find('input').on('change.sticky-submit-button', () => {
                    this.show();
                });
            }
            this._isButtonVisible = false;
        }

        /**
         * Refreshes the position of the sticky button.
         * @private
         */
        _refreshStickyButton() {
            let sibling = this._button.prev(),
                siblingOffset = sibling.offset(),
                siblingBottom = siblingOffset.top + sibling.height(),
                buttonHeight = this._button.outerHeight(true),
                buttonBottom = siblingBottom + buttonHeight,
                windowBottom = $(window).scrollTop() + $(window).height(),
                isSticky = buttonBottom > windowBottom;

            this._button.toggleClass('is-sticky', isSticky)
                        .css('left', parseInt(siblingOffset.left, 10) + 'px');
            sibling.css('margin-bottom', (isSticky ? buttonHeight : 0) + 'px');
        }

        /**
         * Shows the sticky submit button.
         */
        show() {
            if (this._button.length > 0 && !this._isButtonVisible) {
                this._button.removeClass('hidden');
                this._isButtonVisible = true;
                this._refreshStickyButton();
            }
        }

        /**
         * Refreshes the position of the sticky submit button.
         */
        refresh() {
            if (this._button.length > 0 && this._isButtonVisible) {
                this._refreshStickyButton();
            }
        }
    }

    fib.StickySubmitButton = StickySubmitButton;
})(jQuery, factorioItemBrowser);