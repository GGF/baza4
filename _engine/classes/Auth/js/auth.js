function uniqid (prefix, more_entropy) {
    // +   original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
    // +    revised by: Kankrelune (http://www.webfaktory.info/)
    // %        note 1: Uses an internal counter (in php_js global) to avoid collision
    // *     example 1: uniqid();
    // *     returns 1: 'a30285b160c14'
    // *     example 2: uniqid('foo');
    // *     returns 2: 'fooa30285b1cd361'
    // *     example 3: uniqid('bar', true);
    // *     returns 3: 'bara20285b23dfd1.31879087'
    if (typeof prefix == 'undefined') {
        prefix = "";
    }
 
    var retId;
    var formatSeed = function (seed, reqWidth) {
        seed = parseInt(seed, 10).toString(16); // to hex str
        if (reqWidth < seed.length) { // so long we split
            return seed.slice(seed.length - reqWidth);
        }
        if (reqWidth > seed.length) { // so short we pad
            return Array(1 + (reqWidth - seed.length)).join('0') + seed;
        }
        return seed;
    };
 
    // BEGIN REDUNDANT
    if (!this.php_js) {
        this.php_js = {};
    }
    // END REDUNDANT
    if (!this.php_js.uniqidSeed) { // init seed with big random int
        this.php_js.uniqidSeed = Math.floor(Math.random() * 0x75bcd15);
    }
    this.php_js.uniqidSeed++;
 
    retId = prefix; // start with prefix, add current milliseconds hex string
    retId += formatSeed(parseInt(new Date().getTime() / 1000, 10), 8);
    retId += formatSeed(this.php_js.uniqidSeed, 5); // add seed hex string
    if (more_entropy) {
        // for more entropy we add a float lower to 10
        retId += (Math.random() * 10).toFixed(8).toString();
    }
 
    return retId;
}

$(document).ready(function(){
    //    document.location.hash = '';
    //    currentState = document.location.hash

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
                    alert(Lang.get('Auth.warnings.order'));
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
    // сделаем механизм сессий окна
    var lss;
    lss=sessionStorage.getItem('lss');
    if (lss==null) {
        lss=uniqid();
        log('Создан новый ИД сессии');
        sessionStorage.setItem('lss',lss);
        var url = window.location.toString();
        if (url.search("lss=")!=-1) {
            url.replace("/lss=[^&]*/gi",'lss='+lss);
        }
        window.location = url; //перейти
        
    } else {
		
    }
    $('a').each(function(){
        var url = $(this).attr('href');
        //log($(this).html());
        // проверим на относительность ссылок, те начинается с http - не наш клиент
        if (url.search("http://")==-1 && url.search("javascript:")==-1) {
            //log('Не найден http в ссылке');
            if (url.search("lss=")!=-1) {
                //log('Уже есть ид сессии в ссылке');
                //alert(url);
                url.replace("/lss=[^&]*/gi",'lss='+lss);
            //alert(url);
            } else {
                //log('Пока нет сессии в ссылке');
                if(url.search("/\?/")==-1) {
                    //log('Не найдено параметров в ссылке');
                    url = url + '?lss='+lss;
                } else {
                    //log('Найдены параметры в ссылке');
                    url = url + '&lss='+lss;
                }
            }
            $(this).attr('href',url);
        }
    });


});