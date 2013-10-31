
$(document).ready(
    function(){
        $(document).on('click','div#nzapfiles',
            function(){
                $(this).hide();
                $('div#hiddenfiles').show();
            }
        );
    }
);

