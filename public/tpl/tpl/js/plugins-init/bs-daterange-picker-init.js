(function($) {
    "use strict"

    // Vérifier si le plugin daterangepicker existe et s'il y a des éléments à initialiser
    if (typeof $.fn.daterangepicker !== 'undefined') {
        // Daterange picker
        if ($('.input-daterange-datepicker').length > 0) {
            $('.input-daterange-datepicker').daterangepicker({
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse',
                locale: {
                    applyLabel: 'Valider',
                    cancelLabel: 'Effacer'
                }
            });
        }
        
        if ($('.input-daterange-timepicker').length > 0) {
            $('.input-daterange-timepicker').daterangepicker({
                timePicker: true,
                format: 'MM/DD/YYYY h:mm A',
                timePickerIncrement: 30,
                timePicker12Hour: true,
                timePickerSeconds: false,
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse'
            });
        }
        
        if ($('.input-limit-datepicker').length > 0) {
            $('.input-limit-datepicker').daterangepicker({
                format: 'MM/DD/YYYY',
                minDate: '06/01/2015',
                maxDate: '06/30/2015',
                buttonClasses: ['btn', 'btn-sm'],
                applyClass: 'btn-danger',
                cancelClass: 'btn-inverse',
                dateLimit: {
                    days: 6
                }
            });
        }
    }
})(jQuery);
