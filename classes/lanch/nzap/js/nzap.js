
$(document).ready(
    function(){
        $('div#nzapfiles').live('click',
            function(){
                $(this).hide();
                $('div#hiddenfiles').show();
            }
        );
    }
);

