$(document).ready(function(){
    $('select[autoupdate]').live('myevent', function() {
        var target = $(this).attr('autoupdate');
        var url = $(this).attr('autoupdate-link');
        var self=this;
        //alert($('select['+target+']').val()+'___');
        //alert($(self).val());
        $('.ajaxloading').show();
        $.ajax({
            type: "GET",
            url: url,
            data: 'idstr='+$(self).val(),
            success: function(recieved){
                //log("OK loaded: "+recieved.length+" byte ");
                //log(recieved);
                //alert($('select['+target+']').html());
                $('select['+target+']').html(recieved);
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