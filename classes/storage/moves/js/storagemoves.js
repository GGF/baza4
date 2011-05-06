function moveschangetype(id) {
    //alert($('#'+id).val());
    if ($('#'+id).val()==1) {
        // приход
        $('#trsupply_id').show();
        $('#trsupply').show();
        $('#trprice').show();
    } else {
        // расход
        $('#trsupply_id').hide();
        $('#trsupply').hide();
        $('#trprice').hide();
    }
}

$(document).ready(function(){
    $('select[autohide=1]').live('myevent', function() {
        moveschangetype($(this).attr('id'));
    });
});