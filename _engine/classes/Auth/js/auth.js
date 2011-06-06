$(document).ready(function(){
    document.location.hash = '';
    currentState = document.location.hash

    var member = eval(localStorage.getItem('remember'));
    var ri = $("input[name=rememberme]");
    if (ri.length>0) {
        $('#dialog').dialog({
            closeOnEscape: false,
            title:'Авторизация',
            width: 'auto',
            modal: true,
            resizable: false,
            draggable: false
        });
        ri.prop('checked',member)
        .change(function(){
            $("#password").focus();
            var val = $(this).prop('checked');
            localStorage.setItem('remember',val);
        });
    } else {
        if (member) {
            localStorage.setItem('auth',true);
        }
    }
});