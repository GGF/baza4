/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(
    function(){
        $('div#multyfiles').live('click',
            function(){
                $(this).hide();
                $('div#hiddenfiles').show();
            }
        );
    }
);

