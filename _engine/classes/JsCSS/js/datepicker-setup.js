$(document).ready(function(){
    //$.datepicker.setDefaults( $.datepicker.regional[ "ru" ] );
    $.datepicker.setDefaults($.extend({
        //showMonthAfterYear: false,
        showOn: 'button',
        numberOfMonths: [2,1],
        //showCurrentAtPos: 1,
        //showOtherMonths: true,
        //selectOtherMonths: true,
        //buttonImage: '/images/calendar.gif',
        //buttonImageOnly: true
    }, $.datepicker.regional["ru"]));
    $(document).on("focus","input[datepicker]",function(){
        $(this).datepicker();
    });
});