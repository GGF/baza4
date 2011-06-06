$(document).ready(function(){
            document.location.hash = '';
            currentState = document.location.hash
            $('#dialog').dialog({
                closeOnEscape: false,
                title:'Авторизация',
                width: 'auto',
                modal: true,
                resizable: false,
                draggable: false
            }); 
            $("input[name=rememberme]").change(function(){
                $("#password").focus();
            });
});