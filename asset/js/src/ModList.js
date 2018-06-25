(($, fib) => {
    /**
     * The class managing the mod list page.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class ModList {
        /**
         * Initializes the mod list.
         */
        constructor() {
            /**
             * The available mods.
             * @type {Object<string,Mod>}
             * @private
             */
            this._mods = {};

            /**
             * The mod list element.
             * @type {jQuery}
             * @private
             */
            this._modList = $();

            /**
             * The filter element of the mod list.
             * @type {jQuery}
             * @private
             */
            this._filterElement = $();

            this._initializeEvents();
        }

        /**
         * Initializes the global events of the mod list.
         * @private
         */
        _initializeEvents() {
            $(fib.browser).on('page-change.modList', () => {
                this._initializeModList();
            });

            $('#content')
                .on('keyup.mod-list change.mod-list', 'input.mod-list-filter', () => {
                    this._filterMods(this._filterElement.val());
                })
                .on('click.mod-list', '.mod-list-filter-reset-icon', () => {
                    this._filterElement.val('');
                    this._filterMods('');
                });
        }

        /**
         * Initializes the list of mods from the current page and adds the events for it.
         * @private
         */
        _initializeModList() {
            this._modList = $('.mod-list');

            this._mods = {};
            if (this._modList.length > 0) {
                this._filterElement = $('.mod-list-filter');

                this._modList.find('[data-mod]').each((_, element) => {
                    this._initializeMod($(element));
                });
            }
        }

        /**
         * Initializes the mod of the specified container.
         * @param {jQuery} container
         * @private
         */
        _initializeMod(container) {
            let checkbox = container.prev('input[type=checkbox]'),
                data = container.data('mod');

            if (container.length > 0 && checkbox.length > 0 && typeof(data) === 'object') {
                let mod = new fib.Entity.Mod(data);
                mod.container = container;
                mod.checkbox = checkbox;

                this._mods[mod.name] = mod;
            }
        }

        /**
         * Filters the mods to the specified search term.
         * @param searchTerm
         * @private
         */
        _filterMods(searchTerm) {
            $.each(this._mods, (_, mod) => {
                mod.container.toggleClass('hidden', !this._matchFilterTerm(mod, searchTerm.toLowerCase()));
            });
            fib.stickySubmitButton.refresh();
        }

        /**
         * Checks whether the mod matches the specified search term.
         * @param {Mod} mod
         * @param {string} searchTerm
         * @returns {boolean}
         * @private
         */
        _matchFilterTerm(mod, searchTerm) {
            let result = true,
                modTerm = [
                    mod.name,
                    mod.title,
                    mod.author,
                    mod.description
                ].join(' ').toLowerCase();

            if (searchTerm.length > 0) {
                $.each(searchTerm.split(' '), (_, searchTermPart) => {
                    result = modTerm.includes(searchTermPart);
                    return result;
                });
            }
            return result;
        }
    }

    fib.ModList = ModList;
})(jQuery, factorioItemBrowser);