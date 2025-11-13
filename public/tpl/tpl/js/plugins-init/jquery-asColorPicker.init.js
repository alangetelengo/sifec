(function($) {
    "use strict"
    
    // Vérifier si le plugin asColorPicker existe et s'il y a des éléments à initialiser
    if (typeof $.fn.asColorPicker !== 'undefined') {
        // Colorpicker
        if ($(".as_colorpicker").length > 0) {
            $(".as_colorpicker").asColorPicker();
        }
        
        if ($(".complex-colorpicker").length > 0) {
            $(".complex-colorpicker").asColorPicker({
                mode: 'complex'
            });
        }
        
        if ($(".gradient-colorpicker").length > 0) {
            $(".gradient-colorpicker").asColorPicker({
                mode: 'gradient'
            });
        }
    }
})(jQuery);