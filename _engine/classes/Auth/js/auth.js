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
        if (member) {
            $("#password").val(member);
            $("#authform").submit();
        } else {
            ri
            //.prop('checked',member)
            .change(function(){
                if ($("#password").val()=='') {
                    alert('Сначала пароль, потом галочку, потом Enter');
                } else {
                    if ( $(this).prop('checked') )
                        localStorage.setItem('remember',$("#password").val());
                }
                $("#password").focus();
                return false;
            });
        }
    } else {
}
});