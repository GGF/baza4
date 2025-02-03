function moveschangetype(id) {
    //console.log(id);
    if ($('#'+id).attr('myid') == 'prselect') { // обработка только приход-расход
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
    if ($('#'+id).attr('myid') == 'supply') { // обработка только приход-расход
        if ($('#'+id).val()==0) {
            // новый поставщик
            $('#trsupply').show();
        } else {
            // старый поставщик
            $('#trsupply').hide();
        }
    }
}

$(document).ready(function(){
    $(document).on('change','select[autohide=1]', function() {
        moveschangetype($(this).attr('id'));
    });
});