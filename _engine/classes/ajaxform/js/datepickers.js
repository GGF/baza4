$(document).ready(function(){
    $.datepicker.setDefaults($.extend({
        showMonthAfterYear: false,
        showOn: 'button',
        numberOfMonths: [2,1],
        showCurrentAtPos: 1,
        //showOtherMonths: true,
        //selectOtherMonths: true,
        //buttonImage: '/images/calendar.gif',
        //buttonImageOnly: true
    }, $.datepicker.regional["ru"]));
    $("input[datepicker]").live("focus",function(){
        $(this).datepicker();
    });
});