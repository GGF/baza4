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
            var val = eval(localStorage.getItem('remember'));
            log('g-'+val);
            $("input[name=rememberme]").prop('checked',val).change(function(){
                $("#password").focus();
                var val = $(this).prop('checked');
                localStorage.setItem('remember',val);
                log(val);
            });
});