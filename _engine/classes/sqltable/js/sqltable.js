/*
 *  TABLE
 *  Функции для использования класса sqltable
 *  Используют диалог jquery.dialog
 */

/* global Lang 
 * Это для локализации, если будет неопределена то и фиг с ней*/
/* global Noapplet
 * Если нет апплета, то истина*/
var Noapplet = false;

/**
 * Идентификатор первой строки в таблице
 * @type String
 */
var firsttr = '';
/**
 * Идентификатор текущей строки в таблице
 * @type String
 */
var curtr = firsttr;
/**
 * Идетификатор последней строки
 * @type String
 */
var lasttr = '';

/**
 * Выше глобальные переменные для движения клавишами по таблице, чтобы менять
 * выделеную строку и не уходить за конец-начало
 */


/**
 * Объект для копирования в буффер данных, должен быть глобальным, потому как меняется в разных местах.
 * 
 * @type ClipboardJS
 */
var filelinkclipboard;
/**
 *  @type ClipboardJS
 */
var pathlinkclipboard;
/**
 *  @type ClipboardJS
 */
var printlinkclipboard;
/**
 * Объект для копирования в буффер данных в формате excel.
 * 
 * @type ClipboardJS
 */
var copytoexcell;


/**
 * Функция перезагрузки-обновления таблицы, после, скажем, удаления-добавления
 * или просто проверить что поменялось. Можно вызывать по таймеру...
 */
function reload_table() {
    /* из библиотеки jquery.lego выззывается для таблицы с классом листаемая
     * (она должна быть одна такая, но в проекте пока так) берутся атрибуты "имя" и "loaded"
     * укаазывающую URL отккуда загружена таблица
     */
    $().lego.load($('table.listtable').attr('name'),$('table.listtable').attr('loaded'),null);
}

/**
 * Вызов диалога с текстом
 * @param info boolean инфформационное окно (Ok) или  с формой (ok=cancel)
 */
function dialog_modal(info)
{
    var Title;
    var Buttons;
    var formname;
    formname=$('#dialog').find('form').attr('name');

    // Создание кнопок диалога
    if (info)
    {
        Title = 'Сообщение';
        resizeble = false; // неменяемый размер
        onesc=true; //выход по ESC

        Buttons={
            Ok: function() {
                if ($('textarea[name="coment"]').length > 0 ) {
                    if($('textarea[name="coment"]').val().length !== 0)
                        $('input#sendcomment').click();
                }
                $(this).dialog('close');
                //reload_table();
                // Смысл перегружать таблицу если мы только сообщили чтото.
                // однако бывает сто вызывается как информационное, а на деле
                // есть активные элементы - пусть сами перегружают
            }
        };
    } else {
        Title='Редактирование';
        resizeble = true;
        onesc=false; // тут ESC может использоваться в активных элементах
        Buttons={
            Сохранить: function() {
                // изза файловых поле тут надо все же делать посылку формы в класс form_ajax
                //$(this).dialog('close');
                //editrecord(type,$('form[name=form_'+type+']').serialize());
                if ($('textarea[name="coment"]') > 0) {
                    if($('textarea[name="coment"]').val().length !== 0)
                        $('input#sendcomment').click();
                }
                $("form[name="+formname+"]").submit();
            },
            Закрыть: function() {
                $(this).dialog('close');
            },
            '?': function() {
                /// а вот тут можно посмотреть что будет посылаться, ну кроме файлов конечно
                alert($("form[name="+formname+"]").serialize());
            }

        };
    }

    // ну собственно объект диалога (jquery.dialog)
    $('#dialog').dialog({
        closeOnEscape: onesc,
        title:Title,
        width: 'auto',
        modal: true,
        resizable: resizeble,
        draggable: true,
        buttons: Buttons,
        close: function(){               
                $('#dialog').remove(); // убрать div с  диалогом из DOM страницы
            }
    });
    // вызвать скрытие комментариев и файлов TODO: Уж не знаю тут ли, но отображаться они будут только в диалогах
    //$('div.comments').hide().before('<div class="showcomment">Комментарии('+$('div.comments>div.coment').length+')</div>');
    if ($('div.comments>div.coment').length >0 ) {
        $('div.comments').hide().before('<a hotkey="Ctrl + x" Title="Показать коменатрии" class="showcomment"><div>Комментарии('+$('div.comments>div.coment').length+')</div></a>');
    } else {
        $('div.comments').hide().before('<a hotkey="Ctrl + x" Title="Показать коменатрии" class="showcomment"><div >-----------</div></a>');
        $('.showcomment').hide();
    }
    
    if (Noapplet) {
        // а вот тут диалог уже есть и можно поменять контейнер в filelinkclipboard
        filelinkclipboard.container = document.getElementById("dialog");
        pathlinkclipboard.container = document.getElementById("dialog");
        printlinkclipboard.container = document.getElementById("dialog");
    }
}

// Focus first element
/*$.fn.focus_first = function() {
        var elem = $(':text:visible', this).get(0);
        var select = $('select:visible', this).get(0);
        if (select && elem) {
            if (select.offsetTop < elem.offsetTop) {
                elem = select;
            }
        }
        var textarea = $('textarea:visible', this).get(0);
        if (textarea && elem) {
            if (textarea.offsetTop < elem.offsetTop) {
                elem = textarea;
            }
        }
        if (elem) {
            elem.focus();
        }
        return this;
    };
*/


/**
 * Поссле загрузки DOM страницы  нужно выполнить кое какие действия
 */
$(document).ready(function(){

    // если скрипты работают удалим желтизну на ховере
    /*
     * Объясняю: Без скриптов таблица бет черезполосно покрашена в серые цвета,
     * а про проведении поверх них мышой, будет окрашиваться как светложелтая,
     * но только если эти скрипты работают
     */
    $('body').append('<style>.chettr:hover, .nechettr:hover  {background-color: lightyellow;}</style>');
    // а еще когда мышка над строкой сделаем её текущей, чтоб стрелками идти из нужного места
    $(document).on('hover','.nechettr, .chettr',function(){
        curtr=$(this).attr('id');
    });

    // когда нажат enter на  строке поиска, получается посыл формы, а при посыле
    // нужно обновить табличку, а не выполнять POST. Без  скриптов сработает POST и  все будет круто
    $(document).on('submit','form[name=find]',function(){
        $().lego.load($('table.listtable').attr('name'), $(this).attr('action'),$(this).serialize());
        return false;
    });

    // а когда фокус поиска потеряется почистим поле ввода
    $(document).on('blur','input[name=find]',function(){
        $(this).val('');
    });

    // Назначим клавиатурные комбинации
    table_set_keyboard();
    
    // прверка на апплет
    try {
            document.applets.bazaapplet.copytoclipboard('');
        } catch (exception) {
            $().lego.log(exception);
            Noapplet = true;
    }

    if (Noapplet) {
        // добавим копирование команды в буффер с помощью clipboard.js
        // Глобальная переменная
        filelinkclipboard = new ClipboardJS('a.filelink', {
            text: function(trigger) {
                var re = new RegExp('/','gi');
                // огнелисные три палки нужно менять на две
                var re1 = new RegExp('///','gi');
                var link = trigger.href.split(':')[1].replace(re1,'//').replace(re,'\\');
                return 'mppapp-commandOPENFILE'+'\"\"'+decodeURI(link)+'\"\"';
            } 
          // , container: document.getElementById('dialog') // В этот момент диалога еще нет
        });

        printlinkclipboard = new ClipboardJS('a.printlink', {
            text : (trigger) => {
                var re = new RegExp('/','gi');
                // огнелисные три палки нужно менять на две
                var re1 = new RegExp('///','gi');
                var link = trigger.href.split(':')[1].replace(re1,'//').replace(re,'\\');
                return 'mppapp-commandOPENFILE'+'printany.exe \"\"'+decodeURI(link)+'\"\"';
            }
        });
    
        pathlinkclipboard = new ClipboardJS('a.path',{
            text : (trigger) => {
                // а программа хранится в локальном хранилище, и довольно долго
                var totalcmd = localStorage.getItem('total_cmd_path'); // 'd:\\Total Commander XP\\TOTALCMD.EXE';
                if (totalcmd === null) {
                    // если не хранится пошлем настраивать
                    alert(Lang.get('sqltable.warnings.nototalfind'));
                    $(this).attr("href","/?level=setting");
                    return true;
                }
                return 'mppapp-commandOPENFILE'+'\"\"'+totalcmd+'\" \"'+decodeURI(trigger.href)+'\"\"';
            }
        });
        // копирование по кнопке в эксель
        copytoexcell = new ClipboardJS('#copytablebutton', {
            text : () => {
                var str = '';
                var buffer = '';
                // для каждой строчки
                $('table.listtable>tbody>tr').each(function(){
                    $(this).find('td').each(function(){
                        if ( str === '') {
                            // для пустой добавляем
                            str = $(this).text() ;
                        } else {
                            // для не пустой - разделитель и добавляем
                            str = str + "\t" + $(this).text() ;
                        }
                    });
                    // и в буфер все, в буфер
                    buffer = buffer + str + "\n";
                    str = '';
                });
                return buffer;
            }
        });
        copytoexcell.on('success',(target)=>{
            target.trigger.style.display='none';
        });
    } else {
        // при клике на файловых ссылках вызовем из небезопасно, зато удобно из командного процессора
        $(document).on("click","a.filelink", function(){
            var re = new RegExp('/','gi');
            var link = $(this).attr("href").split(':')[1].replace(re,'\\');
            document.applets.bazaapplet.openfile('\"'+link+'\"');
            return false;
        });
        // Ссылки "печатать"
        $(document).on("click","a.printlink", function(){
            var link = $(this).attr("href");
                var re = new RegExp('/','gi');
                // программа лежит в аксессуарах, скопирована с http://www.robvanderwoude.com/csharpexamples.php#PrintAny
                document.applets.bazaapplet.openfile('printany.exe \"'+link.split(':')[1].replace(re,'\\')+'\"');
                return false;
        });
        // при клике на ссылках "путь" соответственно запустим программу открытия пути
        $(document).on("click", "a.path", function(){
                var link = $(this).attr("href");
                var re = new RegExp('/','gi');
                // а программа хранится в локальном хранилище, и довольно долго
                var totalcmd = localStorage.getItem('total_cmd_path'); // 'd:\\Total Commander XP\\TOTALCMD.EXE';

                if (totalcmd === null) {
                    // если не хранится пошлем настраивать
                    alert(Lang.get('sqltable.warnings.nototalfind'));
                    $(this).attr("href","/?level=setting");
                    return true;
                }
                var res = document.applets.bazaapplet.openfile('\"\"'+totalcmd+'\" \"'+link+'\"\"');
                $().lego.log(res);
                return false; // чтоб нажатие на ссылку броузер не отработал нормальным способом
        });
        
        // copytablebutton
        $(document).on('click','#coptablebutton',()=>{
            var str = '';
            var buffer = '';
            // для каждой строчки
            $('table.listtable>tbody>tr').each(function(){
                $(this).find('td').each(function(){
                    if ( str === '') {
                        // для пустой добавляем
                        str = $(this).text() ;
                    } else {
                        // для не пустой - разделитель и добавляем
                        str = str + "\t" + $(this).text() ;
                    }
                });
                // и в буфер все, в буфер
                buffer = buffer + str + "\n";
                str = '';
            });
            // скормим буфер апплету
            document.applets.bazaapplet.copytoclipboard(buffer);
            // надпись на кнопке поменяем
            $('#copytablebutton').hide();
            return false;
        });
    }
});

function table_set_keyboard()
{

    var tskHandled; // отметим в этой переменной, что уже захватили мир
    $(document).keypress(function(ev) {
        if (tskHandled) return false; // и больше не будем
        return true;
    });

    //    $(document).keyup(function(event){
    //        log(event.keyCode);
    //    });
    // со сменой на версию 1.4.3 плохо работает jquery.keyboard попытаюсь сменить на обычный кейпрес
    // однако используем индесы оттуда
    // keypress имеет другие коды!!!
    $(document).keydown(function(event){
        tskHandled = false;

        // все буквы и цифры передаем ввод в строку поиска
        // правда исключаются символы типа двоеточия
        if ($.inArray(event.keyCode,$.keyb.getRange('letters')) !== -1 ||
            $.inArray(event.keyCode,$.keyb.getRange('allnum')) !== -1) {
            // для ввода в строку поиска
            // если в поиске уже чтото есть
            //if ($('#dialog').length>0 && $('#dialog').is(':visible')) {
            // если есть элементы ввода кроме find в таблице
            if ($('input[type=text]').length>$('input[name=find]').length) {
                return true; // то продолжим
            } else {
                // иначе найдем строку поиска
                var find=$('input[name=find]');
                if (find.val().length===0) {
                    // и кинем туда фокус ввода
                    find.focus();
                    return true;
                } else {
                    //log(find.val().length);
                    return true;
                }
            }
            return true;
        } else if (event.keyCode===$.keyb.getIndexCode('enter')) {
            // если ентер
            // то проверяем в поиске и мы
            if ($('input.find').last().val().length>0) {
                // тут генерится сабмит и обрабатывается (см. выше) обновлением таблицы
                return true;
            }
            // если не в поиске и не в диалоге
            if ($('#dialog').length<=0 || $('#dialog').is(':hidden')) {
                //кликнем по текущей выбраной строке
                var tr = $('#'+curtr+' #showlink').first();
                tr.click();
                return true;
            } else {
                // а вот если мы в диалоге, а они у нас модальные
                return true; //08-10-2013 в многослойках появилсяэлемент допонения, пришлось отменить запуски, да и не так это удобно было
                /*
                    if ($('.partybutton').length>0) {
                    // то или запустим очередную партию заготовок
                    $('.partybutton').first().click();
                    // отменить дальнейшую обработку
                    tskHandled = true;
                    event.returnValue = false;
                    event.stopPropagation();
                    eevent.preventDefault();
                    return false; // должно заблокировать дальнейшую обработку энтер, в опере не срабатывает
                } else {
                    // или  еще чо нехорошее делаем
                }
                */
            }
            return true;
        } else if (event.keyCode===$.keyb.getIndexCode('aup')) {
            // клавиши верх-низ
            if ($('#dialog').length<=0 || $('#dialog').is(':hidden')) {
                // желтим-нежелтим
                $('.chettr').removeClass("yellow");
                $('.nechettr').removeClass("yellow");
                curtr = $('#'+curtr).attr('prev');
                $('#'+curtr).addClass("yellow");
                // и проверяем скролинг
                if (($('#'+curtr).position().top)<($('#maindiv').position().top)) {
                    if (curtr === firsttr) {
                        $('#maindiv').scrollTop(0);
                    } else {
                        $('#maindiv').scrollTop($('#maindiv').scrollTop()-$('#'+curtr).height());
                    }
                }
            }
            return false;
        } else if (event.keyCode===$.keyb.getIndexCode('adown')) {
            // клавиши верх-низ
            if ($('#dialog').length<=0 || $('#dialog').is(':hidden')) {
                // желтим-нежелтим
                $('.chettr').removeClass("yellow");
                $('.nechettr').removeClass("yellow");
                curtr = $('#'+curtr).attr('next');
                $('#'+curtr).addClass("yellow");
                // и проверяем скролинг
                if (($('#'+curtr).position().top+$('#'+curtr).height())>($('#maindiv').position().top+$('#maindiv').height())) {
                    $('#maindiv').scrollTop($('#maindiv').scrollTop()+$('#'+curtr).height());
                }
            }
            return false;
        }  // а вот делит по кнопке оказался опасной клавишей
        // сделаешь дырку - потом не запломбируешь
        /*else if (event.keyCode==$.keyb.getIndexCode('delete')) {
			if ($('#dialog').is(':hidden')) {
				$('#'+curtr+' #dellink').click();
			}
			return false;

		}*/
        return true;

    });

}
