/*
 *  TABLE
 *  Функции для использования класса Table
 *  Используют диалог jquery.dialog
 */


var firsttr = '';
var curtr = firsttr;
var lasttr = '';

function reload_table() {
    $().lego.load($('table.listtable').attr('name'),$('table.listtable').attr('loaded'),null);
}

function dialog_modal(info)
{
    var Title;
    var Buttons;
    var formname;
    formname=$('#dialog').find('form').attr('name');
    if (info)
    {
        Title = 'Сообщение';
        resizeble = false;
        onesc=true;
		
        Buttons={
            Ok: function() {
                $(this).dialog('close');
                $('#dialog').remove();
                //reload_table();
            }
        };
    } else {
        Title='Редактирование';
        resizeble = true;
        onesc=false;
        Buttons={
            Сохранить: function() {
                // изза файловых поле тут надо все же делать посылку формы в класс form_ajax
                //$(this).dialog('close');
                //editrecord(type,$('form[name=form_'+type+']').serialize());
                $("form[name="+formname+"]").submit();
            },
            Закрыть: function() {
                $(this).dialog('close');
                $('#dialog').remove();
            },
            '?': function() {
                alert($("form[name="+formname+"]").serialize());
            }

        };
    }
    $('#dialog').dialog({
        closeOnEscape: onesc,
        title:Title,
        width: 'auto',
        modal: true,
        resizable: resizeble,
        draggable: true,
        buttons: Buttons
    });
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

$(document).ready(function(){

    // если скрипты работают удалим желтизну на ховере
    $('body').append('<style>.chettr:hover, .nechettr:hover  {background-color: lightyellow;}</style>');
    $('.nechettr, .chettr').live('hover',function(){
        curtr=$(this).attr('id');
    });

    $('form[name=find]').live('submit',function(){
        $().lego.load($('table.listtable').attr('name'), $(this).attr('action'),$(this).serialize());
        return false;
    });

    $('input[name=find]').live('blur',function(){
        $(this).val('');
    });
    
    table_set_keyboard();
    /* следующий блок добавляет контекстное меню для копирования ссылок
	   и для открытия по клику использует applet
	*/
    $(document).contextMenu( function () {
        return CMenu_builder(this.event);
    });

    $("a.filelink").live("click", function(){
        var link = $(this).attr("href");
            var re = new RegExp('/','gi');
            document.bazaapplet.openfile('\"'+link.split(':')[1].replace(re,'\\')+'\"');
            return false;
//        if (link.search("xml") != -1 || link.search("xls") != -1 ) {
//            var re = new RegExp('/','gi');
//            document.bazaapplet.openfile('\"'+link.split(':')[1].replace(re,'\\')+'\"');
//            return false;
//        }
//        return true;
    });    
    $("a.path").live("click", function(){
        var link = $(this).attr("href");
            var re = new RegExp('/','gi');
            document.bazaapplet.openfile('\"\"d:\\Total Commander XP\\TOTALCMD.EXE\" \"'+link+'\"\"');
            return false;
    });
    
    //$('input[type=file]').live('change',function(){alert($(this).val())});
});

function table_set_keyboard()
{

    var tskHandled;
    $(document).keypress(function(ev) {
        if (tskHandled) return false;
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

        if ($.inArray(event.keyCode,$.keyb.getRange('letters')) != -1 ||
            $.inArray(event.keyCode,$.keyb.getRange('allnum')) != -1) {
            // для ввода в строку поиска
            
            if ($('#dialog').length>0 && $('#dialog').is(':visible')) {
                return true;
            } else {
                var find=$('input[name=find]');
                if (find.val().length==0) {
                    find.focus();
                    return true;
                } else {
                    //log(find.val().length);
                    return true;
                }
            }
            return true;
        } else if (event.keyCode==$.keyb.getIndexCode('enter')) {
            if ($('input.find').last().val().length>0) {
                return true;
            }
            if ($('#dialog').length<=0 || $('#dialog').is(':hidden')) {
                var tr = $('#'+curtr+' #showlink').first();
                tr.click();
                return true;
            } else {
                if ($('.partybutton').length>0) {
                    $('.partybutton').first().click();
                    // отменить дальнейшую обработку
                    tskHandled = true;
                    event.returnValue = false;
                    event.stopPropagation();
                    eevent.preventDefault();
                    return false; // должно заблокировать дальнейшую обработку энтер, в опере не срабатывает
                }
            }
            return true;
        } else if (event.keyCode==$.keyb.getIndexCode('aup')) {
            if ($('#dialog').length<=0 || $('#dialog').is(':hidden')) {
                $('.chettr').removeClass("yellow");
                $('.nechettr').removeClass("yellow");
                curtr = $('#'+curtr).attr('prev');
                $('#'+curtr).addClass("yellow");
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
            if ($('#dialog').length<=0 || $('#dialog').is(':hidden')) {
                $('.chettr').removeClass("yellow");
                $('.nechettr').removeClass("yellow");
                curtr = $('#'+curtr).attr('next');
                $('#'+curtr).addClass("yellow");
                if (($('#'+curtr).position().top+$('#'+curtr).height())>($('#maindiv').position().top+$('#maindiv').height())) {
                    $('#maindiv').scrollTop($('#maindiv').scrollTop()+$('#'+curtr).height());
                }
            }
            return false;
        }  /*else if (event.keyCode==$.keyb.getIndexCode('delete')) {
			if ($('#dialog').is(':hidden')) {
				$('#'+curtr+' #dellink').click();
			}
			return false;
						
		}*/
        return true;
		
    });

}

function CMenu_builder(oEvent) {
    var objMenu = [];
    switch (true) {
        /* тут даже можно вставить проверку, чтобы целевой фрагмент не был выделенным текстом, 
         * который мы, например, можем хотеть скопировать/вставить при помощи основного меню :-)*/
        case (document.getSelection().length > 0) :
            break;
        case oEvent.target.nodeName == 'A' && oEvent.target.className.search('filelink')>-1 :
            /* генерируем данные для одного случая в массив objMenu */
            objMenu.push({
                'Скопировать в буффер' : function () {
                    document.bazaapplet.copytoclipboard(Url.decode(oEvent.target.href));
                    return true;
                }
            });
            break;
        case oEvent.target.nodeName == 'BUTTON' && oEvent.target.className.search('subElems')>-1 :
            /* генерируем данные для другого случая objMenu */
            break;
        case oEvent.target.nodeName == 'a' && oEvent.target.parentNode.id =='footer' :
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

function copytable() {
    var str = '';
    var buffer = '';
    $('table.listtable>tbody>tr').each(function(){
        $(this).find('td').each(function(){
            if ( str == '') {
                str = $(this).text() ;
            } else {
                str = str + "\t" + $(this).text() ;
            }
        });
        buffer = buffer + str + "\n";
        str = '';
    });
    document.bazaapplet.copytoclipboard(buffer);
    $('#copytable').val('Готово');
    return false;
}