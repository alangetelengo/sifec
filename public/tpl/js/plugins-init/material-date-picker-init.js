(function($) {
    "use strict"

    // Vérifier si le plugin bootstrapMaterialDatePicker existe et s'il y a des éléments à initialiser
    if (typeof $.fn.bootstrapMaterialDatePicker !== 'undefined') {
        // Material Date picker
        if ($('#mdate').length > 0) {
            $('#mdate').bootstrapMaterialDatePicker({
                weekStart: 0,
                time: false
            });
        }
        
        if ($('#timepicker').length > 0) {
            $('#timepicker').bootstrapMaterialDatePicker({
                format: 'HH:mm',
                time: true,
                date: false
            });
        }
        
        if ($('#date-format').length > 0) {
            $('#date-format').bootstrapMaterialDatePicker({
                format: 'dddd DD MMMM YYYY - HH:mm'
            });
        }

        if ($('#min-date').length > 0) {
            $('#min-date').bootstrapMaterialDatePicker({
                format: 'DD/MM/YYYY HH:mm',
                minDate: new Date()
            });
        }
    }

})(jQuery);