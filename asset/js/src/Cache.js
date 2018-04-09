(($, fib) => {
    /**
     * A small wrapper class for using the session storage as cache, with a fallback to a local variable in case the
     * Storage is not available in the current browser.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class Cache {
        /**
         * Initializes the cache.
         */
        constructor() {
            /**
             * Whether the web storage is available in the current browser.
             * @type {boolean}
             * @private
             */
            this._isSessionStorageAvailable = typeof(Storage) !== 'undefined';

            /**
             * The session storage we are using.
             * @type {Storage}
             * @private
             */
            this._sessionStorage = window.sessionStorage;

            /**
             * The settings hash used in the cache.
             * @type {string}
             * @private
             */
            this._settingsHash = fib.config.settingsHash;

            /**
             * The local cache of the current page.
             * @type {Object<string, string>}
             * @private
             */
            this._localCache = {};


            if (this.read('cache', 'hash') !== this._settingsHash) {
                // The hash changed, so invalidate the whole cache.
                this.clear();
            }

            $(fib.config).on('config-change.cache', (event, newConfig) => {
                this._handleConfigChange(newConfig);
            });
        }


        /**
         * Writes the specified value to the cache.
         * @param {string} namespace The namespace of the cache to use.
         * @param {string} key The key to write the value to.
         * @param {string} value The actual value to write.
         */
        write(namespace, key, value) {
            let cacheKey = Cache._buildCacheKey(namespace, key),
                cacheValue = (new Date()).getTime() + '|' + value;

            if (this._isSessionStorageAvailable) {
                this._sessionStorage.setItem(cacheKey, cacheValue);
            } else {
                this._localCache[cacheKey] = cacheValue;
            }
        }

        /**
         * Reds the value with the specified key from the cache.
         * @param {string} namespace The namespace of the cache to use.
         * @param {string} key The key to read from the cache.
         * @param {number} [maxAge] The maximum age in milliseconds the value may have to be considered valid.
         * @returns {string|undefined} The cache value, or undefined if it was not in the cache.
         */
        read(namespace, key, maxAge) {
            let cacheKey = Cache._buildCacheKey(namespace, key),
                result;

            if (this._isSessionStorageAvailable) {
                result = Cache._extractValue(this._sessionStorage.getItem(cacheKey), maxAge);
            } else {
                result = Cache._extractValue(this._localCache[cacheKey], maxAge);
            }
            return result;
        }

        /**
         * Removes the specified key from the cache.
         * @param {string} namespace The namespace of the cache to use.
         * @param {string} key The key to remove.
         */
        remove(namespace, key) {
            let cacheKey = Cache._buildCacheKey(namespace, key);

            if (this._isSessionStorageAvailable) {
                this._sessionStorage.removeItem(cacheKey);
            } else {
                delete this._localCache[cacheKey];
            }
        }

        /**
         * Clears all values from the cache.
         */
        clear() {
            this._localCache = {};
            if (this._isSessionStorageAvailable) {
                this._sessionStorage.clear();
            }
        }

        /**
         * Builds the key used for the cache.
         * @param {string} namespace
         * @param {string} key
         * @returns {string}
         * @private
         */
        static _buildCacheKey(namespace, key) {
            return namespace + '|' + key;
        }


        /**
         * Extracts the value from the specified cacheValue, paying attention to its age.
         * @param {string} cacheValue
         * @param {number} [maxAge]
         * @returns {string|undefined}
         * @private
         */
        static _extractValue(cacheValue, maxAge) {
            let result = undefined,
                match,
                time,
                value;

            if (typeof(cacheValue) === 'string') {
                match = cacheValue.match(/^([0-9]+)\|([\s\S]*)$/);
                if (match !== null) {
                    time = parseInt(match[1], 10);
                    value = match[2];

                    if (typeof(maxAge) === 'undefined' || (new Date()).getTime() <= time + maxAge) {
                        result = value;
                    }
                }
            }
            return result;
        }

        /**
         * Handles the change of the config.
         * @param {Object} newConfig
         * @private
         */
        _handleConfigChange(newConfig) {
            if (typeof(newConfig.settingsHash) === 'string' && newConfig.settingsHash !== this._settingsHash) {
                this._settingsHash = newConfig.settingsHash;
                this.clear();
            }
        }
    }

    fib.Cache = Cache;
})(jQuery, factorioItemBrowser);
