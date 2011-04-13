/*
 *  TABLE
 *  ������� ��� ������������� ������ Table
 *  ���������� ������ jquery.dialog
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
        Title = '���������';
        resizeble = false;
        onesc=true;
		
        Buttons={
            Ok: function() {
                $(this).dialog('close');
                $('#dialog').remove();
                reload_table();
            }
        };
    } else {
        Title='��������������';
        resizeble = true;
        onesc=false;
        Buttons={
            ���������: function() {
                // ���� �������� ���� ��� ���� ��� �� ������ ������� ����� � ����� form_ajax
                //$(this).dialog('close');
                //editrecord(type,$('form[name=form_'+type+']').serialize());
                $("form[name="+formname+"]").submit();
            },
            �������: function() {
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
        draggable: false,
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

    // ���� ������� �������� ������ �������� �� ������
    $('body').append('<style>.chettr:hover, .nechettr:hover  {background-color: lightyellow;}</style>');
    $('.nechettr, .chettr').live('hover',function(){
        curtr=$(this).attr('id');
    });

    $('input.find').live('blur',function(){
        $(this).val('');
    });

    $('form[name=find]').live('submit',function(){
        $().lego.load($('table.listtable').attr('name'), $(this).attr('action'),$(this).serialize());
        return false;
    });
    table_set_keyboard();
    /* ��������� ���� ��������� ����������� ���� ��� ����������� ������
	   � ��� �������� �� ����� ���������� applet
	*/
    $(document).contextMenu( function () {
        return CMenu_builder(this.event);
    });

    $("a.filelink").live("click", function(){
        var link = $(this).attr("href");
        if (link.search("xml") != -1 || link.search("xls") != -1 ) {
            var re = new RegExp('/','gi');
            document.bazaapplet.openfile('\"'+link.split(':')[1].replace(re,'\\')+'\"');
            return false;
        }
        return true;
    });
});

function table_set_keyboard()
{

    var Handled;
    $(document).keypress(function(ev) {
        if (Handled) return false;
        return true;
    });

    // �� ������ �� ������ 1.4.3 ����� �������� jquery.keyboard ��������� ������� �� ������� �������
    // ������ ���������� ������ ������
    // keypress ����� ������ ����!!!
    $(document).keydown(function(event){
        //log(event.keyCode);
        if ($.inArray(event.keyCode,$.keyb.getRange('letters')) != -1 ||
            $.inArray(event.keyCode,$.keyb.getRange('allnum')) != -1) {
            // ��� ����� � ������ ������
            if ($('#dialog').length>0 && $('#dialog').is(':visible')) {
                return true;
            } else {
                find=$('.find').last();
                find.focus();
                return true;
            }
        } else if (event.keyCode==$.keyb.getIndexCode('enter')) {
            //log('enter');
            if ($('input.find').last().val().length>0) {
                //log($('input.find').last().val().length);
                return true;
            }
            if ($('#dialog').length<=0 || $('#dialog').is(':hidden')) {
                var tr = $('#'+curtr+' #showlink').first();
                tr.click();
                return true;
            } else {
                $('.partybutton').first().click();
                // �������� ���������� ���������
                Handled = true;
                event.returnValue = false;
                event.stopPropagation();
                eevent.preventDefault();
                return false;
                return false; // ������ ������������� ���������� ��������� �����, � ����� �� �����������
            }
            return true;
        } else if (event.keyCode==$.keyb.getIndexCode('aup')) {
            if ($('#dialog').length<=0 || $('#dialog').is(':hidden')) {
                $('.chettr').removeClass("yellow");
                $('.nechettr').removeClass("yellow");
                curtr = $('#'+curtr).attr('prev');
                $('#'+curtr).addClass("yellow");
                if (($('#'+curtr).position().top)<($('#maindiv').position().top)) {
                    //log($('#maindiv').height()+'x'+ $('#maindiv').width());
                    if (curtr == firsttr) {
                        $('#maindiv').scrollTop(0);
                    } else {
                        $('#maindiv').scrollTop($('#maindiv').scrollTop()-$('#'+curtr).height());
                    }
                }
            }
            return false;
        } else if (event.keyCode==$.keyb.getIndexCode('adown')) {
            //log($('#maindiv').height()+'x'+ $('#maindiv').width());
            if ($('#dialog').length<=0 || $('#dialog').is(':hidden')) {
                $('.chettr').removeClass("yellow");
                $('.nechettr').removeClass("yellow");
                curtr = $('#'+curtr).attr('next');
                $('#'+curtr).addClass("yellow");
                //log($('#'+curtr).position().top);
                if (($('#'+curtr).position().top+$('#'+curtr).height())>($('#maindiv').position().top+$('#maindiv').height())) {
                    $('#maindiv').scrollTop($('#maindiv').scrollTop()+$('#'+curtr).height());
                //log($('#maindiv').height()+'x'+ $('#maindiv').width());
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
        /* ��� ���� ����� �������� ��������, ����� ������� �������� �� ��� ���������� �������, 
         * ������� ��, ��������, ����� ������ �����������/�������� ��� ������ ��������� ���� :-)*/
        case (document.getSelection().length > 0) :
            break;
        case oEvent.target.nodeName == 'A' && oEvent.target.className.search('filelink')>-1 :
            /* ���������� ������ ��� ������ ������ � ������ objMenu */
            objMenu.push({
                '����������� � ������' : function () {
                    document.bazaapplet.copytoclipboard(Url.decode(oEvent.target.href));
                    return true;
                }
            });
            break;
        case oEvent.target.nodeName == 'BUTTON' && oEvent.target.className.search('subElems')>-1 :
            /* ���������� ������ ��� ������� ������ objMenu */
            break;
        case oEvent.target.nodeName == 'a' && oEvent.target.parentNode.id =='footer' :
            /* ���������� ������ ��� ������� ������ objMenu �� ���������� ������*/
            objMenu.push({
                '�������� �1' : function () {
                    return true;
                }
            });
            objMenu.push({
                '�������� �2' : function () {
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