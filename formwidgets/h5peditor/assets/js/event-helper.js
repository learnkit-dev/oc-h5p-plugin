$('html').on('oc.beforeRequest', 'form', function (event) {
    if (window.activeH5pEditor) {
        let h5peditor = window.activeH5pEditor;
        let $library = window.activeH5pEditorElement.find('[name="library"]');
        let $params = window.activeH5pEditorElement.find('[name="parameters"]');

        if (h5peditor !== undefined) {
            var params = h5peditor.getParams();

            if (params !== undefined) {
                $library.val(h5peditor.getLibrary());
                $params.val(JSON.stringify(params));
            } else {
                event.preventDefault();
            }
        }
    }
});
