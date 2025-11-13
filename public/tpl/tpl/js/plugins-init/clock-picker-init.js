(function($) {
    "use strict"

    // Vérifier si le plugin clockpicker existe et s'il y a des éléments à initialiser
    if (typeof $.fn.clockpicker !== 'undefined') {
        // Clock pickers
        if ($('#single-input').length > 0) {
            var input = $('#single-input').clockpicker({
                placement: 'bottom',
                align: 'left',
                autoclose: true,
                'default': 'now'
            });
            
            $('#check-minutes').click(function (e) {
                // Have to stop propagation here
                e.stopPropagation();
                input.clockpicker('show').clockpicker('toggleView', 'minutes');
            });
        }

        if ($('.clockpicker').length > 0) {
            $('.clockpicker').clockpicker({
                donetext: 'Done',
            }).find('input').change(function () {
                console.log(this.value);
            });
        }
    }

})(jQuery)