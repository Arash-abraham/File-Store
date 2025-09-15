CKEDITOR.replace('body');
CKEDITOR.replace('summary');
$(document).ready(function() {
    $("#startDatePersian").persianDatepicker({
        format: "YYYY/MM/DD",
        altField: '#startDatePersian'
    });
    console.log('log');
    
})