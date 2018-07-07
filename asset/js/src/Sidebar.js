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
             * All the entities of the sidebar.
             * @type {Object<number,SidebarEntity>}
             * @private
             */
            this._entities = {};

            /**
             * The sortable instances.
             * @type {{pinned: Sortable, unpinned: Sortable}}
             * @private
             */
            this._sortables = {
                pinned: null,
                unpinned: null
            };

            this._initialize();
        }

        /**
         * Initializes all the features of the sidebar.
         * @private
         */
        _initialize() {
            this._initializeEntities(this._elements.pinnedContainer, true);
            this._initializeEntities(this._elements.unpinnedContainer, false);
            this._initializeSortables();
            this._registerEvents();
            this._updatePinnedContainerVisibility();
        }

        /**
         * Initializes the entities of the specified container.
         * @param {jQuery} container
         * @param {boolean} isPinned
         * @private
         */
        _initializeEntities(container, isPinned) {
            container.find('.sidebar-entity').each((_, element) => {
                let entity = fib.Entity.SidebarEntity.createFromElement($(element));
                if (entity.isValid) {
                    entity.isPinned = isPinned;
                    this._entities[entity.id] = entity;
                }
            });
        }

        /**
         * Initializes the sortable instances of the sidebar.
         * @private
         */
        _initializeSortables() {
            this._sortables.pinned = new Sortable(this._elements.pinnedContainer[0], {
                group: {
                    name: 'sidebar-entities',
                    put: true,
                    pull: false
                },
                draggable: '.sidebar-entity',
                animation: 100,
                onStart: () => {
                    fib.tooltip.isEnabled = false;
                },
                onEnd: () => {
                    fib.tooltip.isEnabled = true;
                },
                onSort: () => {
                    this._sendPinnedEntitiesToServer();
                }
            });

            this._sortables.unpinned = new Sortable(this._elements.unpinnedContainer[0], {
                group: {
                    name: 'sidebar-entities',
                    put: false,
                    pull: true
                },
                sort: false,
                draggable: '.sidebar-entity',
                animation: 100,
                onStart: () => {
                    fib.tooltip.isEnabled = false;
                },
                onEnd: () => {
                    fib.tooltip.isEnabled = true;
                },
                onRemove: (event) => {
                    let entity = $(event.item).data('entity');
                    if (entity instanceof fib.Entity.SidebarEntity) {
                        entity.isPinned = true;
                        this._updatePinnedContainerVisibility();
                    }
                }
            });
        }

        /**
         * Registers the events of the sidebar.
         * @private
         */
        _registerEvents() {
            this._elements.pinnedContainer.on('click.sidebar', '.unpin', (event) => {
                this._handleUnpin($(event.currentTarget).closest('.sidebar-entity'));
                fib.tooltip.hide();

                event.preventDefault();
                event.stopPropagation();
                return false;
            });
            this._elements.unpinnedContainer.on('click.sidebar', '.pin', (event) => {
                this._handlePin($(event.currentTarget).closest('.sidebar-entity'));
                fib.tooltip.hide();

                event.preventDefault();
                event.stopPropagation();
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
         * Updates the visibility of the container holding the pinned entities.
         * @private
         */
        _updatePinnedContainerVisibility() {
            this._elements.pinnedContainer.toggleClass(
                'hidden',
                this._elements.pinnedContainer.find('.sidebar-entity').length === 0
            );
        }

        /**
         * Adds a new unpinned entity to the sidebar, if it is not already pinned.
         * @param {string} html The raw HTML of the new entity.
         */
        addNewEntity(html) {
            let entity = fib.Entity.SidebarEntity.createFromElement($(html));

            if (entity.isValid) {
                if (typeof(this._entities[entity.id]) === 'undefined') {
                    this._entities[entity.id] = entity;
                    this._addUnpinnedEntity(entity);
                } else {
                    if (this._entities[entity.id].isPinned) {
                        // Entity already pinned, so simply update the view time.
                        this._entities[entity.id].viewTime = entity.viewTime;
                    } else {
                        this._entities[entity.id].viewTime = entity.viewTime;
                        this._entities[entity.id].element.detach();
                        this._addUnpinnedEntity(this._entities[entity.id]);
                    }
                }
            }
        }

        /**
         * Adds an entity to the unpinned list.
         * @param {SidebarEntity} newEntity
         * @private
         */
        _addUnpinnedEntity(newEntity) {
            let lastEntity = null;
            $.each(this._entities, (id, entity) => {
                if (!entity.isPinned && entity.id !== newEntity.id && entity.viewTime < newEntity.viewTime
                    && (lastEntity === null || entity.viewTime > lastEntity.viewTime)
                ) {
                    lastEntity = entity;
                }
            });

            if (lastEntity === null) {
                this._elements.unpinnedContainer.append(newEntity.element);
            } else {
                lastEntity.element.before(newEntity.element);
            }

            this._limitUnpinnedEntities();
        }

        /**
         * Limits the number of unpinned entities to hte maximal allowed number.
         * @private
         */
        _limitUnpinnedEntities() {
            this._elements.unpinnedContainer
                .find('.sidebar-entity')
                .slice(fib.config.sidebar.numberOfUnpinnedEntities)
                .each((_, element) => {
                    let entity = $(element).data('entity');
                    if (entity instanceof fib.Entity.SidebarEntity) {
                        entity.element.remove();
                        delete this._entities[entity.id];
                    }
                });
        }

        /**
         * Handles pinning of the specified entity element.
         * @param {jQuery} element
         * @private
         */
        _handlePin(element) {
            let entity = element.data('entity');
            if (entity instanceof fib.Entity.SidebarEntity && !entity.isPinned) {
                entity.element.detach();
                this._elements.pinnedContainer.append(entity.element);

                entity.isPinned = true;
                this._updatePinnedContainerVisibility();
                this._sendPinnedEntitiesToServer();
            }
        }

        /**
         * Handles unpinning of the specified entity element.
         * @param {jQuery} element
         * @private
         */
        _handleUnpin(element) {
            let entity = element.data('entity');
            if (entity instanceof fib.Entity.SidebarEntity && entity.isPinned) {
                entity.element.detach();
                this._addUnpinnedEntity(entity);

                entity.isPinned = false;
                this._updatePinnedContainerVisibility();
                this._sendPinnedEntitiesToServer();
            }
        }

        /**
         * Sends the currently pinned elements and their order to the server.
         * @private
         */
        _sendPinnedEntitiesToServer() {
            let pinnedEntityIds = [];
            this._elements.pinnedContainer.find('.sidebar-entity').each((_, element) => {
                let entity = $(element).data('entity');
                if (entity instanceof fib.Entity.SidebarEntity && entity.isPinned) {
                    pinnedEntityIds.push(entity.id);
                }
            });

            $.ajax({
                url: fib.config.sidebar.pinnedUrl,
                method: 'POST',
                dataType: 'json',
                data: {
                    pinnedEntityIds: pinnedEntityIds
                }
            });
        }
    }

    fib.Sidebar = Sidebar;
})(jQuery, factorioItemBrowser);