$(document).ready(function(){
    $('form[name=monthform]').live('submit',function(){
        log($(this).serialize());
        $().lego.load($('table.listtable').attr('name'), $(this).attr('action'),$(this).serialize());
        return false;
    });
    $('form[name=peroidreport]').live('submit',function(){
        log($(this).serialize());
        $().lego.load($('table.listtable').attr('name'), $(this).attr('action'),$(this).serialize());
        return false;
    });

    $('#month').live('change',function(){
        $('form[name=monthform]').submit();
    });
    $('#monthbutton').live('click',function(){
        $('form[name=monthform]').submit();
    });
    $('#rangebutton').live('click',function(){
        $('form[name=peroidreport]').submit();
    });
});