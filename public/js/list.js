var minDate, maxDate;

$(document).ready(function() {
    // you may need to change this code if you are not using Bootstrap Datepicker
    $('.js-datepicker').datepicker({
        format: 'yyyy-mm-dd'
    });

    minDate = new DateTime($('#export_dateFrom'), {
        format: 'YYYY-MM-DD'
    });
    maxDate = new DateTime($('#export_dateTo'), {
        format: 'YYYY-MM-DD'
    });

});