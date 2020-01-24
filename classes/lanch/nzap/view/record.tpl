<div id="multyfiles">Файлы</div>
<div id="hiddenfiles">
<a class="filelink" href="{$filelink}" onclick='return false;'>Тех.Задание</a><br>
<a class="path" href="{$zpath}" hotkey="Ctrl + Atl + b" Title="Перейти к блоку" onclick="return false;">Путь  к блоку</a><br>
{$orderfiles}
<a data-silent="self" legotarget="lanch_nzap" href="{$onlycalclink}" onclick='return false;'>Отправить в незапускаемые</a><br>
</div>
<br>
<strong>{$blockname} {$boardinorder}шт.   {$blocksizex}x{$blocksizey}   {$phtemplates}шаб. {$layers} сл.</strong>
<br>
<table>
<tr>
<th>Плата</th><th>Размер</th><th>На заг.</th><th>Маски</th><th>Марк</th>
</tr>
{$boardsinfo}
</table>
