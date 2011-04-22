$(document).ready(function(){
    $.datepicker.setDefaults($.extend({
        showMonthAfterYear: false,
        showOn: 'button',
        //buttonImage: '/images/calendar.gif',
        //buttonImageOnly: true
    }, $.datepicker.regional["ru"]));
    $("#datepicker").live("focus",function(){
        $(this).datepicker();
    });
    $("input[datepicker]").live("focus",function(){
        $(this).datepicker();
    });
});