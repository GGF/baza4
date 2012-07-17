/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

$(document).ready(
    function(){
        $('.showcomment').live('click',
            function(){
                $(this).hide();
                $('div.comments').show();
            }
        );
        $('#sendcomment').live('click',
            function() {
                var url = $('#savecommenturl').val();
                var data = $('form#addcomment').serialize();
                $('.ajaxloading').show();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: data,
                    success: function(received){
                        location.hash = url+'&'+data; //сохраняем загруженный УРЛ в адресную строку
                        currentState = document.location.hash;
                        $("div.comments").replaceWith(received);
                    },
                    error: function(x){
                        $().lego.log("Не удалось загрузить url: "+url);
                    },
                    complete: function() {
                        $('.ajaxloading').hide();
                    }

                });
            }
        );
        $('.delbutton').live('click',
            function() {
                var par = $(this).parent();
                var url = $(this).attr('url');
                $('.ajaxloading').show();
                $.ajax({
                    type: "GET",
                    url: url,
                    data: '',
                    success: function(received){
                        par.append(received).hide();//.html(received);
                    },
                    error: function(x){
                        $().lego.log("Не удалось загрузить url: "+url);
                    },
                    complete: function() {
                        $('.ajaxloading').hide();
                    }

                });
            }
        );
    }
);

