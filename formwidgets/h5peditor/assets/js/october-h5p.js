+function ($) { "use strict";
    var H5pEditor = function (element, options) {
        this.$el = $(element)
        this.options = options || {}

        this.init()
    }

    H5pEditor.prototype.constructor = H5pEditor

    H5pEditor.prototype.init = function() {
        ns.$ = H5P.jQuery;

        this.h5p = ns;

        this.h5p.getAjaxUrl = function (action, parameters) {
            var url = H5PIntegration.editor.ajaxPath + action + '/?';
            var request_params = [];

            if (parameters !== undefined) {
                for (var property in parameters) {
                    if (parameters.hasOwnProperty(property)) {
                        request_params.push(encodeURIComponent(property) + "=" + encodeURIComponent(parameters[property]));
                    }
                }
            }
            return url + request_params.join('&');
        };

        if (H5PIntegration == undefined | H5PIntegration.editor == undefined) {
            console.error('H5PIntegration missing...');
            return;
        }

        this.h5p.basePath = H5PIntegration.editor.libraryUrl;
        this.h5p.fileIcon = H5PIntegration.editor.fileIcon;
        this.h5p.ajaxPath = H5PIntegration.editor.ajaxPath;
        this.h5p.filesPath = H5PIntegration.editor.filesPath;
        this.h5p.apiVersion = H5PIntegration.editor.apiVersion;
        this.h5p.copyrightSemantics = H5PIntegration.editor.copyrightSemantics;
        this.h5p.assets = H5PIntegration.editor.assets;
        this.h5p.baseUrl = '';
        this.h5p.metadataSemantics = H5PIntegration.editor.metadataSemantics;
        if (H5PIntegration.editor.nodeVersionId !== undefined) {
            this.h5p.contentId = H5PIntegration.editor.nodeVersionId;
        }

        this.$params = $('input[name="parameters"]');
        this.$library = $('input[name="library"]');
        this.library = this.$library.val();
        this.$editor = this.$el.find('#h5p-editor');

        this.h5pEditor = new this.h5p.Editor(this.library, this.$params.val(), this.$editor[0]);

        window.activeH5pEditor = this.h5pEditor;
        window.activeH5pEditorElement = this.$el;
    }

    H5pEditor.DEFAULTS = {
        someParam: null
    }

    // PLUGIN DEFINITION
    // ============================

    var old = $.fn.h5pEditor

    $.fn.h5pEditor = function (option) {
        var args = Array.prototype.slice.call(arguments, 1), items, result

        items = this.each(function () {
            var $this   = $(this)
            var data    = $this.data('oc.h5pEditor')
            var options = $.extend({}, H5pEditor.DEFAULTS, $this.data(), typeof option == 'object' && option)
            if (!data) $this.data('oc.h5pEditor', (data = new H5pEditor(this, options)))
            if (typeof option == 'string') result = data[option].apply(data, args)
            if (typeof result != 'undefined') return false
        })

        return result ? result : items
    }

    $.fn.h5pEditor.Constructor = H5pEditor

    $.fn.h5pEditor.noConflict = function () {
        $.fn.h5pEditor = old
        return this
    }

    // Add this only if required
    $(document).ready(function (){
        $('[data-control="h5pEditor"]').h5pEditor()
    })
}(H5P.jQuery);
