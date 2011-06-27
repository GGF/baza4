var Lang = {
    lang: {},

    add: function(array) {
        self.lang = array_replace_recursive(self.lang, array);
    },

    get: function(index) {
        var path = index.split('.');
        var tmp = self.lang;
        var v;
        while (v = each(path)) {
            if (typeof tmp[v.value] != 'undefined')
                tmp = tmp[v.value]
            else
                return index;
        }
        return tmp['ru'];
    }
}
function array_replace_recursive(arr) {
    // +   original by: Brett Zamir (http://brett-zamir.me)
    // *     example 1: array_replace_recursive({'citrus' : ["orange"], 'berries' : ["blackberry", "raspberry"]}, {'citrus' : ['pineapple'], 'berries' : ['blueberry']});
    // *     returns 1: {citrus : ['pineapple'], berries : ['blueberry', 'raspberry']}
    if (arguments.length < 2) {
        throw new Error('There should be at least 2 arguments passed to array_replace_recursive()');
    }
 
    // Although docs state that the arguments are passed in by reference, it seems they are not altered, but rather the copy that is returned (just guessing), so we make a copy here, instead of acting on arr itself
    var retObj = {};
    for (var prop in arr) {
        retObj[prop] = arr[prop];
    }
 
    for (var i = 1; i < arguments.length; i++) {
        for (var p in arguments[i]) {
            if (typeof retObj[p] === 'object' && retObj[p] !== null) {
                retObj[p] = this.array_replace_recursive(retObj[p], arguments[i][p]);
            } else {
                retObj[p] = arguments[i][p];
            }
        }
    }
    return retObj;
}
function each(arr) {
    // Return the currently pointed key..value pair in the passed array, and advance the pointer to the next element  
    // 
    // version: 1103.1210
    // discuss at: http://phpjs.org/functions/each
    // +   original by: Ates Goral (http://magnetiq.com) 
    // +    revised by: Brett Zamir (http://brett-zamir.me)
    // %        note 1: Uses global: php_js to store the array pointer
    // *     example 1: each({a: "apple", b: "balloon"});
    // *     returns 1: {0: "a", 1: "apple", key: "a", value: "apple"}
    //  Will return a 4-item object unless a class property 'returnArrayOnly'
    //  is set to true on this function if want to only receive a two-item
    //  numerically-indexed array (for the sake of array destructuring in
    //  JavaScript 1.7+ (similar to list() in PHP, but as PHP does it automatically
    //  in that context and JavaScript cannot, we needed something to allow that option)
    //  See https://developer.mozilla.org/en/New_in_JavaScript_1.7#Destructuring_assignment
    // BEGIN REDUNDANT
    this.php_js = this.php_js || {};
    this.php_js.pointers = this.php_js.pointers || [];
    var indexOf = function (value) {
        for (var i = 0, length = this.length; i < length; i++) {
            if (this[i] === value) {
                return i;
            }
        }
        return -1;
    };
    // END REDUNDANT
    var pointers = this.php_js.pointers;
    if (!pointers.indexOf) {
        pointers.indexOf = indexOf;
    }
    if (pointers.indexOf(arr) === -1) {
        pointers.push(arr, 0);
    }
    var arrpos = pointers.indexOf(arr);
    var cursor = pointers[arrpos + 1];
    var pos = 0;

    if (!(arr instanceof Array)) {
        var ct = 0;
        for (var k in arr) {
            if (ct === cursor) {
                pointers[arrpos + 1] += 1;
                if (each.returnArrayOnly) {
                    return [k, arr[k]];
                } else {
                    return {
                        1: arr[k],
                        value: arr[k],
                        0: k,
                        key: k
                    };
                }
            }
            ct++;
        }
        return false; // Empty
    }
    if (arr.length === 0 || cursor === arr.length) {
        return false;
    }
    pos = cursor;
    pointers[arrpos + 1] += 1;
    if (each.returnArrayOnly) {
        return [pos, arr[pos]];
    } else {
        return {
            1: arr[pos],
            value: arr[pos],
            0: pos,
            key: pos
        };
    }
}


Lang.add({
    "alerts":{
        "sent":{
            "ru" : "СООБЩЕНИЕ ОТПРАВЛЕНО.\\n\\nВаше сообщение было успешно отправлено.\\n\\nСпасибо.",
            "en" : "MESSAGE SENT.\\n\\nYour message has been successfully sent.\\n\\nThank you."
        },
        "code" : {
            "ru" : "Неверный код подтверждения.",
            "en" : "Specified confirmation code is wrong."
        },
        "time" : {
            "ru" : "ОШИБКА. СООБЩЕНИЕ НЕ ОТПРАВЛЕНО.\\n\\nНедавно Вы уже отправляли сообщение.\\n\\nНа сайте действует ограничение по количеству отсылаемых\\nсообщений: одно сообщение раз в 10 минут.\\n\\nЭто ограничение установлено в целях борьбы со спамом.",
            "en" : "ERROR. MESSAGE IS NOT SENT.\\n\\nYou have sent the message already.\\n\\nYou can send only one message in 10 minutes due to\\n\\nanti-spam restrictions."
        },
        "oblg" : {
            "ru" : "Заполнены не все обязательные поля...",
            "en" : "Obligatory fields aren't filled..."
        }
    }
});
