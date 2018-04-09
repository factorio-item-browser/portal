(($, fib) => {
    /**
     * The entity class representing a mod of the mod-list.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class Mod {
        /**
         * Initializes the entity.
         * @param {Object} [data]
         */
        constructor(data = {}) {
            /**
             * The internal name of the mod.
             * @type {string}
             */
            this.name = data.name || '';

            /**
             * The title of the mod.
             * @type {string}
             */
            this.title = data.title || '';

            /**
             * The description of the mod.
             * @type {string}
             */
            this.description = data.description || '';

            /**
             * The author of the mod.
             * @type {string}
             */
            this.author = data.author || '';

            /**
             * The container element representing the mod.
             * @type {null}
             */
            this.container = null;

            /**
             * The element representing the checkbox of the mod.
             * @type {null}
             */
            this.checkbox = null;
        }
    }

    fib.Entity.Mod = Mod;
})(jQuery, factorioItemBrowser);