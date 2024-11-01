(function($) {
    $(document).ready(function() {
        if ($('#super_block_css_global').length) {
            wp.codeEditor.initialize($('#super_block_css_global'), {
                codemirror: {
                    mode: 'css',
                    lineNumbers: true,
                    lineWrapping: true,
                    extraKeys: {"Ctrl-Space": "autocomplete"},
                    matchBrackets: true,
                    autoCloseBrackets: true,
                }
            });
        }
    });
})(jQuery);