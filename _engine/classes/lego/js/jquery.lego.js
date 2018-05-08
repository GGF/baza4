/**
 * hash - Это  содержание адресной строки броузера после символа #
 * в нем будет хранится урл вызваный по AJAX
 */
var currentState;// = document.location.hash;;

$(function(){
    // Обработка ссылок вида <a data-confirm=... для отображения запроса подтверждения
    $(document).on("click.myConfirmEvent", "a[data-confirm]", function(){
        var text = $(this).attr("data-confirm");
        if(confirm(text)) {
            return true;
        } else {
            return false;
        }
    });

    $(document).on("click.myNeedAuthEvent", "a.needauth", function(){
        var a = this;
        if(Auth.isAuthorized()) return true;
        Auth.authorize(function(){
            $(a).click();
        });
        return false;
    });

    // Обработка молчаливых ссылок вида <a data-silent=...
    $(document).on("click.mySilentEvent", "a[data-silent], input[data-silent]", function(){
        var a = this;
        var target_element = $(this).attr("data-silent");

        var action = $(this).attr("data-silent-action");
        /* const потому как иммутабельная& посмотрим как обрабатывается этот код ES-6*/
        const after_action = $(this).attr("data-silent-afteraction");
        var url = jQuery.fn.lego.getAjaxUrl($(this).attr("href"), $(this).attr("legotarget"));
        //var load = $("<div class='ajaxloading'>Loading...</div>");
        var load = $('.ajaxloading');
        var text = $(this).attr("data-need-confirm");
        if (text!=null) {
            if(!confirm(text)) {
                return false;
            }
        }
        //alert(url);
        if(target_element == "self")
            $(this).hide();
        if(action==null)
            action = 'html'; //вставить внутрь элемента
        //load.insertAfter($(this));
        load.show();
        $.ajax({
            type: "GET",
            url:url,
            success: function(data){
                document.location.hash = '#'+url;
                currentState = document.location.hash;

                switch(target_element){
                    case 'nowhere':
                        return;
                    case 'self':
                        $(a).replaceWith(data);
                        return;
                    case 'alert':
                        alert(data);
                        return;
                }
                if ($(target_element).length >0 ) {
                    if (action === 'html')
                        $(target_element).html(data);
                    else if (action==='replace')
                        $(target_element).replaceWith(data);
                    else if (action==='append')
                        $(target_element).append(data);
                    else
                        $(target_element).html(data);

                } else {
                    $(document).append(data);
                }
            },
            error: function(){
                alert("error "+url);
            },
            complete: function(){
                //load.remove();
                load.hide();
                $(a).show();
                /* выполнить последействие (небезопасно)*/
                console.log(after_action);
                jQuery.globalEval(after_action);

            }
        });
        return false;
    });

    // Обработка молчаливых чекбоксов <input data-silent=...
    $(document).on("change.mySilentEvent", "input[data-silent]", function(){
        var target = $(this).attr("data-silent");
        var url = jQuery.fn.lego.getAjaxUrl($(this).attr("href"), $(this).lego().attr("name"));
        //var load = $("<div class='ajaxloading'>Loading...</div>");
        var load = $('.ajaxloading');
        load.show();
        //load.insertAfter($(this).next());
        $.ajax({
            type: "GET",
            url:url,
            success: function(data){
                if(target == "nowhere") return;
                if($(target)) $(target).replace(data);
            },
            error: function(){
                alert("error "+url);
            },
            complete: function(){
                //load.remove();
                load.hide();
            }
        });
        return false;
    });
});

    jQuery.fn.lego = function(e){
        var ret;
        ret= $(this).parents(".lego").first();
        return ret;
    }

    jQuery.fn.lego.log = function(variable){
        //return log(variable);
    //        if(typeof console == "undefined") return;
    //        console.log(variable);
    }

    jQuery.fn.lego.getNoAjaxUrl = function(url){
        return jQuery.fn.lego.getAjaxUrl(url, null);
    }

    jQuery.fn.lego.getAjaxUrl = function(url, legotarget){
        var arr = new Object();
        var path = url.indexOf("?") == -1 ? url : url.substring(0, url.indexOf("?"));
        var query = url.substring(url.indexOf("?")+1);
        parse_str(query, arr);
        arr.ajax = legotarget; // добавляем магический параметр ajax в гет
        if(legotarget == null) delete arr.ajax;
        return path+"?"+urldecode(http_build_query(arr));
    }

    jQuery.fn.lego.getLegoTargetFromUrl = function(url){
        var arr = new Object();
        var path = url.substring(0, url.indexOf("?"));
        var query = url.substring(url.indexOf("?")+1);
        parse_str(query, arr);
        return typeof (arr.ajax) == 'undefined' ? "" : arr.ajax;
    }

// КЭШ
var LegoCache = {
    enabled: false, // пока отключен
    cache: {},
    put: function(lego_name, url, data){
        if(!this.enabled) return;
        this.cache[lego_name+url] = data;
    },
    get: function(lego_name, url, data){
        if(!this.enabled) return;
        if(typeof this.cache[lego_name+url] != 'undefined'){
            var ret = $(this.cache[lego_name+url]);
            var reload_block = $("<a href='javascript:void(0)' onclick='jQuery.fn.lego.reload(this)' />");
            reload_block.html("блок загружен из кэша, обновить");
            reload_block.css("display","block");
            reload_block.css("font-size","8px");
            reload_block.css("text-align","center");
            reload_block.css("background-color","yellow");
            try{
                ret.prepend(reload_block);
            }catch(e){}
            return ret;
        }
    }
}

    // ОБНОВЛЕНИЕ ТЕКУЩЕГО ЛЕГО БЕЗ КЭША
    jQuery.fn.lego.reload = function(e){
        var current_lego_name = $(e).lego().attr("name");
        $().lego.log('current_lego_name - ' + current_lego_name);
        var url = jQuery.fn.lego.loadedUrls[current_lego_name];
        $().lego.log('url - '+url);
        if(!url) document.reload();
        jQuery.fn.lego.load(current_lego_name, url, null, true);
    }

    jQuery.fn.lego.load = function(lego_name, url, data, nocache){
        $().lego.log("Загрузка адреса "+url+" в лего "+lego_name+"...");
        jQuery.fn.lego.loadedUrls[lego_name] = urldecode(url);
        var lego = $("div.lego[name="+lego_name+"]");
        lego.addClass("loading");
        var pellicle = $("<div>"); // БЕЛАЯ ПЛЕВА
        pellicle.css("position", "absolute");
        pellicle.css("width", lego.width());
        pellicle.css("height", lego.height());
        pellicle.css("z-index", "100");
        pellicle.css("background-color", "white");
        pellicle.css("opacity", "0.7");
        pellicle.css("cursor", "wait");
        $(".lego[name="+lego_name+"]").prepend(pellicle);


        url = $().lego.getAjaxUrl(url,lego_name);
        var no_ajax_url = jQuery.fn.lego.getNoAjaxUrl(url);
        // если что-то пошло не так, то грузим страницу обычным способом
        $().lego.log('count('+lego_name+')='+$(".lego[name="+lego_name+"]").length);
        if($(".lego[name="+lego_name+"]").length != 1){
            $().lego.log("Не обнаружен лего "+lego_name+", либо их много");
            alert("Не обнаружен лего "+lego_name+", либо их много. Произойдет вызов не ajax!");
            document.location = no_ajax_url+'&'+data;
            // дальше ничего не выполняется
            return true;
        }

        //сохраняем загруженный УРЛ в переменную:
        jQuery.fn.lego.lastLoadedUrl = urldecode(url);

        //БЕРЕМ ИЗ КЭША
        var from_cache = LegoCache.get(lego_name, url);
        if(from_cache && data == null && !nocache){
            $().lego.log("Взято из кэша в лего "+lego_name+"...");
            $(".lego[name="+lego_name+"]").replaceWith(from_cache);
            return true;
        }

        $('.ajaxloading').show();
        $.ajax({
            type: data == null ? "GET" : "POST",
            url: url,
            data: data,
            success: function(received){
                location.hash = url+'&'+data; //сохраняем загруженный УРЛ в адресную строку
                currentState = document.location.hash;

                $().lego.log("ОК, загружено: "+received.length+" байт в лего "+lego_name+"...");
                $().lego.log(received);//.substr(0,20));
                if($(received).hasClass('lego')){
                    $(".lego[name="+lego_name+"]").replaceWith(received);
                    //Кладем в кэш
                    LegoCache.put(lego_name, url, received);
                }
                else{
                    $().lego.log(lego_name+": Сервер не вернул требуемое Lego: "+url);
                    //document.location = no_ajax_url;
                }
            },
            error: function(x){
                $().lego.log("Не удалось загрузить url: "+url);
            },
            complete: function() {
                $('.ajaxloading').hide();
            }

        });
    }

    jQuery.fn.lego.lastLoadedUrl = "";
    jQuery.fn.lego.loadedUrls = {};

    jQuery.fn.lego.startProcessHash = function(){
        jQuery.fn.lego.processHash();
        setInterval(jQuery.fn.lego.processHash, 500);
    }

    jQuery.fn.lego.processHash = function(){
        if(location.hash.length <= 1) return;
        var hash_url = urldecode(location.hash).substring(1);
        if(jQuery.fn.lego.lastLoadedUrl != hash_url){ //если загруженный урл не такой как HASH в адресной строке
            $().lego.log("Обработка hash_url="+hash_url+" lastLoadedUrl пуст");
            var lego_target = jQuery.fn.lego.getLegoTargetFromUrl(hash_url);
            jQuery.fn.lego.load(lego_target, hash_url);
            jQuery.fn.lego.lastLoadedUrl = hash_url;
        }
    }

    jQuery.fn.lego.ajaxEnable = function(selector){
        jQuery.fn.lego.startProcessHash();


        // Обработка всех ссылок
        if(!selector) selector = "";
        $(document).on("click.myEvent", selector+"*:not(.noajax) a:not(.noajax)", function(e){
            if($(this).closest(".noajax").length > 0) return true;
            var href = $(this).attr("href");
            if(!href || href.indexOf("#") != -1) return true;
            if(href.match(/^(http(s)?:\/\/)|(javascript)/i)) return true;
            if($(this).attr("target")) return true;
            if($(this).attr("data-box")) return true;
            var name = $(e.currentTarget).lego().attr("name");
            var legotarget = $(e.currentTarget).attr("legotarget");
            if(typeof legotarget == "undefined") legotarget = name;
            var ajax_url = jQuery.fn.lego.getAjaxUrl(href, legotarget);
            jQuery.fn.lego.load(legotarget, ajax_url);
            return false;
        });

        // Обработка форм
        $("form:not(.noajax)").onquery("submit", function(e){
            var name = $(this).lego().attr("name");
            var legotarget = $(this).attr("legotarget");
            if(typeof legotarget == "undefined") legotarget = name;
            jQuery.fn.lego.load(legotarget, $().lego.getAjaxUrl($(this).attr("action"), legotarget), $(this).serialize());
            return false;
        });
    }



function http_build_query (formdata, numeric_prefix, arg_separator) {
    // Generates a form-encoded query string from an associative array or object.
    // -    depends on: urlencode
    // *     example 1: http_build_query({foo: 'bar', php: 'hypertext processor', baz: 'boom', cow: 'milk'}, '', '&amp;');
    // *     returns 1: 'foo=bar&amp;php=hypertext+processor&amp;baz=boom&amp;cow=milk'
    // *     example 2: http_build_query({'php': 'hypertext processor', 0: 'foo', 1: 'bar', 2: 'baz', 3: 'boom', 'cow': 'milk'}, 'myvar_');
    // *     returns 2: 'php=hypertext+processor&myvar_0=foo&myvar_1=bar&myvar_2=baz&myvar_3=boom&cow=milk'
    var value, key, tmp = [];
    var _http_build_query_helper = function (key, val, arg_separator) {
        var k, tmp = [];
        if (val === true) val = "1";
        if (val === false) val = "0";
        if (val !== null && typeof(val) === "object") {
            for (k in val) {
                if (val[k] !== null) tmp.push(_http_build_query_helper(key + "[" + k + "]", val[k], arg_separator));
            }
            return tmp.join(arg_separator);
        } else if (typeof(val) !== "function") {
            return urlencode(key) + "=" + urlencode(val);
        } else {
            throw new Error('There was an error processing for http_build_query().');
        }
    };
    if (!arg_separator) arg_separator = "&";
    for (key in formdata) {
        value = formdata[key];
        if (numeric_prefix && !isNaN(key)) {
            key = String(numeric_prefix) + key;
        }
        tmp.push(_http_build_query_helper(key, value, arg_separator));
    }
    return tmp.join(arg_separator);
}

function parse_str (str, array){
    // Parses GET/POST/COOKIE data and sets global variables
    try{
        var glue1 = '=', glue2 = '&', array2 = String(str).split(glue2),
        i, j, chr, tmp, key, value, bracket, keys, evalStr, that = this,
        fixStr = function (str) {
            return urldecode(str).replace(/([\\"'])/g, '\\$1').replace(/\n/g, '\\n').replace(/\r/g, '\\r');
        };

        if (!array) {
            array = this.window;
        }

        for (i = 0; i < array2.length; i++) {
            tmp = array2[i].split(glue1);
            if (tmp.length < 2) {
                tmp = [tmp, ''];
            }
            key   = fixStr(tmp[0]);
            value = fixStr(tmp[1]);
            while (key.charAt(0) === ' ') {
                key = key.substr(1);
            }
            if (key.indexOf('\0') !== -1) {
                key = key.substr(0, key.indexOf('\0'));
            }
            if (key && key.charAt(0) !== '[') {
                keys    = [];
                bracket = 0;
                for (j = 0; j < key.length; j++) {
                    if (key.charAt(j) === '[' && !bracket) {
                        bracket = j + 1;
                    }
                    else if (key.charAt(j) === ']') {
                        if (bracket) {
                            if (!keys.length) {
                                keys.push(key.substr(0, bracket - 1));
                            }
                            keys.push(key.substr(bracket, j - bracket));
                            bracket = 0;
                            if (key.charAt(j + 1) !== '[') {
                                break;
                            }
                        }
                    }
                }
                if (!keys.length) {
                    keys = [key];
                }
                for (j = 0; j < keys[0].length; j++) {
                    chr = keys[0].charAt(j);
                    if (chr === ' ' || chr === '.' || chr === '[') {
                        keys[0] = keys[0].substr(0, j) + '_' + keys[0].substr(j + 1);
                    }
                    if (chr === '[') {
                        break;
                    }
                }
                evalStr = 'array';
                for (j = 0; j < keys.length; j++) {
                    key = keys[j];
                    if ((key !== '' && key !== ' ') || j === 0) {
                        key = "'" + key + "'";
                    }
                    else {
                        key = eval(evalStr + '.push([]);') - 1;
                    }
                    evalStr += '[' + key + ']';
                    if (j !== keys.length - 1 && eval('typeof ' + evalStr) === 'undefined') {
                        eval(evalStr + ' = [];');
                    }
                }
                evalStr += " = '" + value + "';\n";
                eval(evalStr);
            }
        }
    }catch(e){
        return [];
    }
}

/**
 * Тут по моему все понятно декодировать URL
 */
function urldecode (str){
    return decodeURIComponent(str.replace(/\+/g, '%20'));
}

/**
 * Заккодировать URL
 */
function urlencode (str){
    str = (str+'').toString();
    return encodeURIComponent(str).replace(/!/g, '%21').replace(/'/g, '%27').replace(/\(/g, '%28').replace(/\)/g, '%29').replace(/\*/g, '%2A').replace(/%20/g, '+');
}

/**
 * Функци проверяет текущий статус, если он не совпадает с записаным
 * вызывает последний AJAX заппро нормальным вызовом
 *
 */
function checkLocalState() {
    if (document.location.hash && document.location.hash != currentState) {
        $().lego.log('Reloadpage:'+currentState);
        currentState = document.location.hash;
        window.location =  $().lego.getNoAjaxUrl(currentState.substr(1));
    }
}

/**
 * Запишем в таймер функцию проверки статуса, после того как документ загружен.
 */
$(document).ready(function() {
    setInterval(checkLocalState, 500);
    $('body').append("<div class='ajaxloading'>Loading...</div>");
    $('.ajaxloading').hide();
});



