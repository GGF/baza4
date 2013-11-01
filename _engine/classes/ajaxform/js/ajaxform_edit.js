$(document).ready(function(){
    $(document).on('myevent', 'select[autoupdate]', function() {
        var target = $(this).attr('autoupdate');
        var url = $(this).attr('autoupdate-link');
        var self=this;
        $('.ajaxloading').show();
        $.ajax({
            type: "GET",
            url: url,
            data: 'idstr='+$(self).val(),
            success: function(recieved){
                $('select['+target+']').html(recieved);
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