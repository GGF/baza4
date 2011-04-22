$(document).ready(function(){
    $('select[autoupdate]').live('myevent', function() {
        var target = $(this).attr('autoupdate');
        var url = $(this).attr('autoupdate-link');
        var self=this;
        //alert($('select['+target+']').val()+'___');
        $('.ajaxloading').show();
        $.ajax({
            type: "GET",
            url: url,
            data: target+'='+$('select['+target+']').val(),
            success: function(recieved){
                //log("OK loaded: "+recieved.length+" byte ");
                //log(recieved.substring(0,40));
                //alert($(self).html());
                $(self).html(recieved);
                //alert($(self).html());
            },
            error: function(x){
                $().lego.log("Can not load url: "+url);
            },
            complete:function(){
                $('.ajaxloading').hide();
            }
        });
    });
});