(($, fib) => {
    /**
     * The class handling the upload of the mod-list.json file.
     *
     * @author BluePsyduck <bluepsyduck@gmx.com>
     * @license http://opensource.org/licenses/GPL-3.0 GPL v3
     */
    class ModListFileUpload {
        /**
         * Initializes the file upload.
         */
        constructor() {
            /**
             * Whether the advanced upload features are available.
             * @type {boolean}
             * @private
             */
            this._isAdvancedUploadAvailable = ModListFileUpload._detectAdvancedUpload();


            $(fib.browser).on('page-change.modListFileUpload', () => {
                let form = $('.mod-upload-form');
                if (form.length > 0) {
                    this._initializeForm(form);
                }
            });
        }

        /**
         * Detects whether the features for advanced upload are available.
         * @returns {boolean}
         * @private
         */
        static _detectAdvancedUpload() {
            let div = document.createElement('div');
            return (('draggable' in div) || ('ondragstart' in div && 'ondrop' in div))
                && 'FormData' in window && 'FileReader' in window;
        }


        /**
         * Initializes the form, adding the required events to it.
         * @param {jQuery} form
         * @private
         */
        _initializeForm(form) {
            form.off('.modListFileUpload');
            if (this._isAdvancedUploadAvailable) {
                form.on('dragover.modListFileUpload, dragstart.modListFileUpload', (event) => {
                    form.addClass('is-dragover');
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                });
                form.on('dragleave.modListFileUpload, dragend.modListFileUpload', (event) => {
                    form.removeClass('is-dragover');
                    event.preventDefault();
                    event.stopPropagation();
                    return false;
                });
                form.on('drop.modListFileUpload', (event) => {
                    form.removeClass('is-dragover');
                    form.addClass('is-uploading');
                    event.preventDefault();
                    event.stopPropagation();
                    this._uploadDroppedFile(form, event.originalEvent.dataTransfer.files[0]);
                    return false;
                });

                form.addClass('has-advanced-upload');
            }

            form.find('input[type=file]').on('change.modListFileUpload', () => {
                form.addClass('is-uploading');
                form.trigger('submit');
            });
        }

        /**
         * Uploads the dropped file to the server.
         * @param {jQuery} form
         * @param {File} droppedFile
         * @private
         */
        _uploadDroppedFile(form, droppedFile) {
            let ajaxData = new window.FormData();
            ajaxData.append('context', 'json');
            ajaxData.append(form.find('input[type=file]').attr('name'), droppedFile);

            $.ajax({
                url: form.attr('action'),
                type: form.attr('method'),
                data: ajaxData,
                dataType: 'json',
                processData: false,
                contentType: false,
                complete: () => {
                    fib.browser.forceRefresh();
                }
            });
        }
    }

    fib.ModListFileUpload = ModListFileUpload;
})(jQuery, factorioItemBrowser);
