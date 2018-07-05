/*
 *  TABLE
 *  Функции для использования класса sqltable
 *  Используют диалог jquery.dialog
 */

/**
 * Идентификатор первой строки в таблице
 */
var firsttr = '';
/**
 * Идентификатор текущей строки в таблице
 */
var curtr = firsttr;
/**
 * Идетификатор последней строки
 */
var lasttr = '';

/**
 * Выше глобальные переменные для движения клавишами по таблице, чтобы менять
 * выделеную строку и не уходить за конец-начало
 */


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
 * @param boolean info инфформационное окно (Ok) или  с формой (ok=cancel)
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
    /* следующий блок добавляет контекстное меню для копирования ссылок
	   и для открытия по клику использует applet
	*/
//    $(document).contextMenu( function () {
//        return CMenu_builder(this.event);
//    });

    // при клике на файловых ссылках вызовем из небезопасно, зато удобно из командного процессора
    $(document).on("click","a.filelink", function(){
        var link = $(this).attr("href");
            var re = new RegExp('/','gi');
            document.bazaapplet.openfile('\"'+link.split(':')[1].replace(re,'\\')+'\"');
            return false;
    });
    // Ссылки "печатать"
    $(document).on("click","a.printlink", function(){
        var link = $(this).attr("href");
            var re = new RegExp('/','gi');
            // программа лежит в аксессуарах, скопирована с http://www.robvanderwoude.com/csharpexamples.php#PrintAny
            document.bazaapplet.openfile('printany.exe \"'+link.split(':')[1].replace(re,'\\')+'\"');
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
            //alert('\"mkdir \"'+link+'\"\"');
            //document.bazaapplet.openfile('\"mkdir \"'+link+'\"\"');
            var res = document.bazaapplet.openfile('\"\"'+totalcmd+'\" \"'+link+'\"\"');
            $().lego.log(res);
            return false; // чтоб нажатие на ссылку броузер не отработал нормальным способом
    });

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
        if ($.inArray(event.keyCode,$.keyb.getRange('letters')) != -1 ||
            $.inArray(event.keyCode,$.keyb.getRange('allnum')) != -1) {
            // для ввода в строку поиска
            // если в поиске уже чтото есть
            //if ($('#dialog').length>0 && $('#dialog').is(':visible')) {
            // если есть элементы ввода кроме find в таблице
            if ($('input[type=text]').length>$('input[name=find]').length) {
                return true; // то продолжим
            } else {
                // иначе найдем строку поиска
                var find=$('input[name=find]');
                if (find.val().length==0) {
                    // и кинем туда фокус ввода
                    find.focus();
                    return true;
                } else {
                    //log(find.val().length);
                    return true;
                }
            }
            return true;
        } else if (event.keyCode==$.keyb.getIndexCode('enter')) {
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
        } else if (event.keyCode==$.keyb.getIndexCode('aup')) {
            // клавиши верх-низ
            if ($('#dialog').length<=0 || $('#dialog').is(':hidden')) {
                // желтим-нежелтим
                $('.chettr').removeClass("yellow");
                $('.nechettr').removeClass("yellow");
                curtr = $('#'+curtr).attr('prev');
                $('#'+curtr).addClass("yellow");
                // и проверяем скролинг
                if (($('#'+curtr).position().top)<($('#maindiv').position().top)) {
                    if (curtr == firsttr) {
                        $('#maindiv').scrollTop(0);
                    } else {
                        $('#maindiv').scrollTop($('#maindiv').scrollTop()-$('#'+curtr).height());
                    }
                }
            }
            return false;
        } else if (event.keyCode==$.keyb.getIndexCode('adown')) {
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

/**
 * Функция срисована с когото, мне нужна была только для пункта меню "Копировать в буффер"
 * использует библиотеку jquery.contextmenu
 */
function CMenu_builder(oEvent) {
    var objMenu = [];
    switch (true) {
        /* тут даже можно вставить проверку, чтобы целевой фрагмент не был выделенным текстом,
         * который мы, например, можем хотеть скопировать/вставить при помощи основного меню :-)*/
        case (document.getSelection().length > 0) :
            break;
        case oEvent.target.nodeName === 'A' && oEvent.target.className.search('filelink')>-1 :
            /* генерируем данные для одного случая в массив objMenu */
            objMenu.push({
                'Скопировать в буффер' : function () {
                    document.bazaapplet.copytoclipboard(Url.decode(oEvent.target.href));
                    return true;
                }
            });
            break;
        case oEvent.target.nodeName === 'BUTTON' && oEvent.target.className.search('subElems')>-1 :
            /* генерируем данные для другого случая objMenu */
            break;
        case oEvent.target.nodeName === 'a' && oEvent.target.parentNode.id ==='footer' :
            /* генерируем данные для другого случая objMenu из статичного набора*/
            objMenu.push({
                'Действие №1' : function () {
                    return true;
                }
            });
            objMenu.push({
                'Действие №2' : function () {
                    return true;
                }
            });
            //etc
            break;
        default:
            return false;
            break;
    }
    return objMenu.length ? objMenu : false;
}

/**
 * Это замечательная штука использует копирование в буффер апплета
 * и загоняет туда таблицу в формате удобном для вставки
 * в excel, а  то ссылки вставлялись и другое оформление
 */
function copytable() {
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
    document.bazaapplet.copytoclipboard(buffer);
    // надпись на кнопке поменяем
    $('#copytable').val('Готово');
    return false;
}