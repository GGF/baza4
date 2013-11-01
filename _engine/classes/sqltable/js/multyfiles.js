/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(
    function(){
        $(document).on('click','div#multyfiles',
            function(){
                $(this).hide();
                $(this).next('div#hiddenfiles').show();
            }
        );
    }
);

