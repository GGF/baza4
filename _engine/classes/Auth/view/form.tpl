{$css}
<div id='dialog'>
<div class="auth"> 
<table>
<tr><td colspan=2 class=zag>Необходимо авторизоваться для работы с базой
<!--tr><td colspan=2 class=zag>{$mes}&nbsp; -->
<tr class="tekst">
<td>Пароль
<span class=podtekst>(именно пароль и только пароль)</span>
<td>
<form action='{$actionlink}' method='POST' id="authform">
<input type=password name='password' id=password >
</form>
<tr class="tekst">
<td>Мне надоело! Запомнить меня!
<td><input type=checkbox name='rememberme'>
<tr><td class="underline" colspan=2>&nbsp;</td></tr>
</table>
</div>
<div>{$dop}</div>
</div>