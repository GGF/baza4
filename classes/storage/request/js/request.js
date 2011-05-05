$(document).ready(function(){
    $('form[name=requestform]').live('submit',function(){
        log($(this).serialize());
        window.open($(this).attr('action')+'&'+$(this).serialize(),'_blank');
        //$().lego.load($('table.listtable').attr('name'), $(this).attr('action'),$(this).serialize());
        return false;
    });
    $('#selectrequestdate').live('change',function(){
        //log($(this).serialize());
        $().lego.load('requestform',$('table.listtable').attr('loaded'),'&ddate='+$(this).attr('value'));
    });
    $('#requestbutton').live('click',function(){
        $('form[name=requestform]').submit();
    });
});