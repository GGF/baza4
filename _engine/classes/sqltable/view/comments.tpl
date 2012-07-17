<div class="lego comments" name="comments">
{$comments}
<input type='hidden' name='savecommenturl' id='savecommenturl' value='{$savecommenturl}'/>
<form id=addcomment>
<textarea name="coment" placeholder="Введите здесь комментарий и нажмите стрелку справа">
</textarea>
<input type='hidden' name='table' id='commenttable' value='{$table}'/>
<input type='hidden' name='userid' id='userid' value='{$userid}'/>
<input type='hidden' name='recordid' id='recordid' value='{$recordid}'/>
<input type='button' id='sendcomment' value='&gt;'/>
</form>
</div>