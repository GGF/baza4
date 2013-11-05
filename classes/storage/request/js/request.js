$(document).ready(function(){
    $(document).on('submit','form[name=requestform]',function(){
        //log($(this).serialize());
        window.open($(this).attr('action')+'&'+$(this).serialize(),'_blank');
        return false;
    });
    $(document).on('change','#selectrequestdate',function(){
        //log('ddate='+$(this).val());
        var date = $(this).val();
        $().lego.load('requestform',$('table.listtable').attr('loaded')+'&ddate='+date);
//        setTimeout(function(){
//            $().lego.load('requestform',$('table.listtable').attr('loaded')+'$ddate='+date);//$('form[name=requestform]').serialize());
//        }, 500);
//        
//        return false;
    });
    $(document).on('click','#requestbutton',function(){
        $('form[name=requestform]').submit();
    });
});