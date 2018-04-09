(($, fib) => {
    /**
     * The entity class representing an entity of the sidebar.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class SidebarEntity {
        /**
         * Initializes the entity.
         */
        constructor() {
            /**
             * The element representing the entity.
             * @type {jQuery|null}
             */
            this.element = null;

            /**
             * The internal id of the sidebar entity.
             * @type {number}
             */
            this.id = 0;

            /**
             * The timestamp of when the entity has last been viewed, used for sorting.
             * @type {number}
             */
            this.viewTime = 0;
        }

        /**
         * Returns whether the entity is in a valid state.
         * @returns {boolean}
         */
        get isValid() {
            return this.id > 0 && this.viewTime > 0;
        }
    }

    fib.Entity.SidebarEntity = SidebarEntity;
})(jQuery, factorioItemBrowser);