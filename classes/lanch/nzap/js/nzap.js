/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

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

