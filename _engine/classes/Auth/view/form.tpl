{$css}
<div class="auth"> 
<table>
<tr><td rowspan=6>&nbsp;</td>
<td colspan=2 class=zag>&nbsp;</td><td>&nbsp;</td>
</tr> <tr><td colspan=2 class=zag>Необходимо авторизоваться для работы с базой</td><td>&nbsp;</td> </tr>
<tr><td colspan=2 class=zag>{$mes}&nbsp;</td> <td>&nbsp;</td> </tr>
<form action='{$actionlink}' method='POST'>
<tr class=tekst ><td>Пароль <span class=podtekst>(именно пароль и только пароль)</td>
<td><input type=password name='password' id=password></td>
</tr>
</form>
<tr><td>&nbsp;</td><td class=tekst>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>
<tr><td class="underline" colspan=4>&nbsp;</td></tr></table>
</div>
<script>
document.location.hash = '';
currentState = document.location.hash
</script>
