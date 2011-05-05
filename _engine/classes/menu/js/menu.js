// MENU
// Функции для использования классом меню
function selectmenu(id)
{
    $('div.menu-item').removeClass('menu-item-sel');
    if (id==null) return;
    $('div#'+id+'.menu-item').addClass('menu-item-sel');
}
$(document).ready(function() {
   $('a.menu-item').live('click',function(){
       selectmenu($(this).attr('id'));
   });
});

