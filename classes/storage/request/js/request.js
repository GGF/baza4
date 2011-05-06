$(document).ready(function(){
    $('form[name=requestform]').live('submit',function(){
        //log($(this).serialize());
        window.open($(this).attr('action')+'&'+$(this).serialize(),'_blank');
        return false;
    });
    $('#selectrequestdate').live('change',function(){
        //log('ddate='+$(this).val());
        var date = $(this).val();
        setTimeout(function(){
            $().lego.load('requestform',$('table.listtable').attr('loaded'),'ddate='+date);//$('form[name=requestform]').serialize());
        }, 500);
        
        return false;
    });
    $('#requestbutton').live('click',function(){
        $('form[name=requestform]').submit();
    });
});