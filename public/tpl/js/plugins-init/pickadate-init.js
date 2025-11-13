(function($) {
    "use strict"

    // Vérifier si le plugin pickadate existe et s'il y a des éléments à initialiser
    if (typeof $.fn.pickadate !== 'undefined' && $('.datepicker-default').length > 0) {
        //date picker classic default
        $('.datepicker-default').pickadate();
    }

})(jQuery);