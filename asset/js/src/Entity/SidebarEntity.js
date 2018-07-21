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
             * The label of the sidebar entitiy.
             * @type {string}
             */
            this.label = '';

            /**
             * The link of the sidebar entity.
             * @type {string}
             */
            this.link = '';

            /**
             * The timestamp of when the entity has last been viewed, used for sorting.
             * @type {number}
             */
            this.viewTime = 0;

            /**
             * Whether the entity is currently pinned to the sidebar.
             * @type {boolean}
             */
            this.isPinned = false;
        }

        /**
         * Returns whether the entity is in a valid state.
         * @returns {boolean}
         */
        get isValid() {
            return this.id > 0 && this.viewTime > 0;
        }

        /**
         * Creates a new instance from the specified element.
         * @param {jQuery} element
         * @returns {SidebarEntity}
         */
        static createFromElement(element) {
            let entity = new this();

            entity.element = element;
            entity.id = parseInt(element.data('id') || 0, 10);
            entity.viewTime = parseInt(element.data('view-time') || 0, 10);
            entity.label = element.find('.label').text() || '';
            entity.link = element.attr('href') || '';

            element.data('entity', entity);
            return entity;
        }
    }

    fib.Entity.SidebarEntity = SidebarEntity;
})(jQuery, factorioItemBrowser);