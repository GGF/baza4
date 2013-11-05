$(document).ready(function(){
    $(document).on('submit','form[name=monthform]',function(){
        //log($(this).serialize());
        $().lego.load($('table.listtable').attr('name'), $(this).attr('action'),$(this).serialize());
        return false;
    });
    $(document).on('submit','form[name=peroidreport]',function(){
        //log($(this).serialize());
        $().lego.load($('table.listtable').attr('name'), $(this).attr('action'),$(this).serialize());
        return false;
    });

    $(document).on('change','#month',function(){
        $('form[name=monthform]').submit();
    });
    $(document).on('click','#monthbutton',function(){
        $('form[name=monthform]').submit();
    });
    $(document).on('click','#rangebutton',function(){
        $('form[name=peroidreport]').submit();
    });
});