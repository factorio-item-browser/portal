(($, fib) => {
    /**
     * The class managing the sidebar with its pinned or unpinned entities.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class Sidebar {
        /**
         * Initializes the sidebar.
         * @param {jQuery} container The main sidebar container.
         */
        constructor(container) {
            /**
             * The elements of the sidebar.
             * @type {{container: jQuery, pinnedContainer: jQuery, unpinnedContainer: jQuery}}
             * @private
             */
            this._elements = {
                container: container,
                pinnedContainer: container.find('.sidebar-pinned-entity-list'),
                unpinnedContainer: container.find('.sidebar-unpinned-entity-list'),
                toggle: $('#toggle-sidebar')
            };

            /**
             * The pinned entities which are managed by the user.
             * @type {Array|SidebarEntity[]}
             * @private
             */
            this._pinnedEntities = this._fetchInitialEntities(this._elements.pinnedContainer);

            /**
             * The unpinned entities which get updated automatically.
             * @type {Array|SidebarEntity[]}
             * @private
             */
            this._unpinnedEntities = this._fetchInitialEntities(this._elements.unpinnedContainer);


            this._registerEvents();
            this._updatePinnedContainerVisibility();
        }

        /**
         * Registers the events of the sidebar.
         * @private
         */
        _registerEvents() {
            this._elements.pinnedContainer.on('click.sidebar', '.unpin', (event) => {
                event.preventDefault();
                event.stopPropagation();
                this._handleUnpin($(event.currentTarget).closest('.sidebar-entity').data('id') || 0);
                fib.tooltip.hide();
                return false;
            });
            this._elements.unpinnedContainer.on('click.sidebar', '.pin', (event) => {
                event.preventDefault();
                event.stopPropagation();
                this._handlePin($(event.currentTarget).closest('.sidebar-entity').data('id') || 0);
                fib.tooltip.hide();
                return false;
            });
            $(fib.browser).on('page-change', () => {
                this._elements.toggle.prop('checked', false);
                $(document.body).removeClass('hasOverlay');
            });
            this._elements.toggle.on('change', () => {
                $(document.body).toggleClass('hasOverlay', this._elements.toggle.prop('checked'));
            });
        }

        /**
         * Fetches the initial entities of the page..
         * @returns {Array<SidebarEntity>}
         * @private
         */
        _fetchInitialEntities(container) {
            let entities = [];

            container.find('.sidebar-entity').each((_, element) => {
                let entity = Sidebar._createEntityFromElement($(element));
                if (entity.isValid) {
                    entities.push(entity);
                }
            });
            return entities;
        }

        /**
         * Creates and returns an entity based on the specified element.
         * @param {jQuery} element
         * @returns {SidebarEntity}
         * @private
         */
        static _createEntityFromElement(element) {
            let entity = new fib.Entity.SidebarEntity();

            entity.element = element;
            entity.id = element.data('id') || 0;
            entity.viewTime = element.data('view-time') || 0;

            return entity;
        }

        /**
         * Updates the visibility of the container holding the pinned entities.
         * @private
         */
        _updatePinnedContainerVisibility() {
            this._elements.pinnedContainer.toggleClass('hidden', this._pinnedEntities.length === 0);
        }

        /**
         * Adds a new unpinned entity to the sidebar, if it is not already pinned.
         * @param {string} html The raw HTML of the new entity.
         */
        addNewEntity(html) {
            let element = $(html),
                entity = Sidebar._createEntityFromElement(element),
                key;

            if (entity.isValid) {
                key = this._getKeyOfEntityById(this._pinnedEntities, entity.id);
                if (key !== -1) {
                    this._pinnedEntities[key].viewTime = entity.viewTime;
                } else {
                    // The entity is not pinned, so we can add it to the unpinned list.
                    this._removeEntityWithId(this._unpinnedEntities, entity.id);
                    this._addUnpinnedEntity(entity);
                    this._limitUnpinnedEntities();
                }
            }
        }

        /**
         * Searches for the specified entity id, and returns its key within the array.
         * @param {Array<SidebarEntity>} entities
         * @param {number} entityId
         * @returns {number}
         * @private
         */
        _getKeyOfEntityById(entities, entityId) {
            let result = -1;
            $.each(entities, (key, entity) => {
                if (entity.id === entityId) {
                    result = key;
                }
                return result === -1;
            });
            return result;
        }

        /**
         * Removes an unpinned entity with the specified id, if present.
         * @param {Array<SidebarEntity>} entities
         * @param {number} entityId
         * @private
         */
        _removeEntityWithId(entities, entityId) {
            let key = this._getKeyOfEntityById(entities, entityId);
            if (key !== -1) {
                entities[key].element.remove();
                entities.splice(key, 1);
            }
        }

        /**
         * Adds an entity to the unpinned list.
         * @param {SidebarEntity} newEntity
         * @private
         */
        _addUnpinnedEntity(newEntity) {
            let nextEntityKey = -1;
            $.each(this._unpinnedEntities, (key, entity) => {
                if (entity.viewTime < newEntity.viewTime) {
                    nextEntityKey = key;
                }
                return nextEntityKey === -1;
            });

            if (nextEntityKey === -1) {
                this._elements.unpinnedContainer.append(newEntity.element);
                this._unpinnedEntities.unshift(newEntity);
            } else {
                this._unpinnedEntities[nextEntityKey].element.before(newEntity.element);
                this._unpinnedEntities.splice(nextEntityKey, 0, newEntity);
            }
        }

        /**
         * Limits the number of unpinned entities to hte maximal allowed number.
         * @private
         */
        _limitUnpinnedEntities() {
            let entity;

            while (this._unpinnedEntities.length > fib.config.sidebar.numberOfUnpinnedEntities) {
                entity = this._unpinnedEntities.pop();
                entity.element.remove();
            }
        }

        /**
         * Handles pinning of the entity with the specified id.
         * @param {number} entityId
         * @private
         */
        _handlePin(entityId) {
            let key = this._getKeyOfEntityById(this._unpinnedEntities, entityId);
            if (key !== -1) {
                let entity = this._unpinnedEntities[key];
                this._removeEntityWithId(this._unpinnedEntities, entityId);
                this._pinnedEntities.push(entity);
                this._elements.pinnedContainer.append(entity.element);

                $.ajax({
                    url: fib.config.sidebar.urls.pin.replace('--id--', entityId),
                    method: 'POST',
                    dataType: 'json'
                });

                this._updatePinnedContainerVisibility();
            }
        }

        /**
         * Handles unpinning of the entity with the specified id.
         * @param {number} entityId
         * @private
         */
        _handleUnpin(entityId) {
            let key = this._getKeyOfEntityById(this._pinnedEntities, entityId);
            if (key !== -1) {
                let entity = this._pinnedEntities[key];
                this._removeEntityWithId(this._pinnedEntities, entityId);
                this._addUnpinnedEntity(entity);
                this._limitUnpinnedEntities();

                $.ajax({
                    url: fib.config.sidebar.urls.unpin.replace('--id--', entityId),
                    method: 'POST',
                    dataType: 'json'
                });

                this._updatePinnedContainerVisibility();
            }
        }
    }

    fib.Sidebar = Sidebar;
})(jQuery, factorioItemBrowser);