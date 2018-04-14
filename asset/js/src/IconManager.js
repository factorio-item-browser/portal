(($, fib) => {
    /**
     * The class managing the styles of the icons of items and recipes.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class IconManager {
        /**
         * Initializes the icon manager.
         */
        constructor() {
            /**
             * The entities which have already been processed.
             * @type {Object<string,bool>}
             * @private
             */
            this._processedEntities = {};

            /**
             * The dynamic stylesheet element.
             * @type {jQuery}
             * @private
             */
            this._stylesheetElement = null;

            this._initialize();
        }

        _initialize() {
            this._stylesheetElement = $('<style type="text/css"></style>');
            $('head').append(this._stylesheetElement);
            this._registerEvents();
        }

        /**
         * Registers the events to listen for.
         * @private
         */
        _registerEvents() {
            $(fib.browser).on('page-change.icon-manager page-change-partial.icon-manager', () => {
                this._checkForMissingIcons();
            });
        }

        /**
         * Checks for any icons which are still missing, and requests them.
         * @private
         */
        _checkForMissingIcons() {
            let missingIconNames = this._findMissingIconNames();
            if (missingIconNames.length > 0) {
                this._requestIcons(missingIconNames);
            }
        }

        /**
         * Finds the names of all the icons which are currently missing.
         * @returns {Array<string>}
         * @private
         */
        _findMissingIconNames() {
            let missingIconNames = [];
            $('[data-icon]').each((_, element) => {
                let icon = $(element),
                    name = icon.data('icon') || '';

                if (name.length > 0 && !this._processedEntities[name]) {
                    missingIconNames.push(name);
                    icon.removeAttr('data-icon');
                }
            });
            return missingIconNames;
        }

        /**
         * Requests the specified icons from the server.
         * @param iconNames
         * @private
         */
        _requestIcons(iconNames) {
            $.ajax({
                url: fib.config.iconManager.url,
                method: 'POST',
                data: {entities: iconNames},
                dataType: 'json',
                success: (response) => {
                    $.each(response.processedEntities || [], (_, processedEntity) => {
                        this._processedEntities[processedEntity] = true;
                    });
                    if (typeof(response.content) === 'string' && response.content.length > 0) {
                        this._stylesheetElement.append(response.content);
                    }
                }
            });
        }
    }

    fib.IconManager = IconManager;
})(jQuery, factorioItemBrowser);