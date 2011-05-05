<html>
<head>
{$css}
</head>
<body>
<div id='dialog'>
<div class="auth"> 
<table>
<tr><td colspan=2 class=zag>Необходимо авторизоваться для работы с базой</td></tr>
<tr><td colspan=2 class=zag>{$mes}&nbsp;</td></tr>
<tr class=tekst ><td>Пароль <span class=podtekst>(именно пароль и только пароль)</td>
<td>
<form action='{$actionlink}' method='POST' id="authform">
<input type=password name='password' id=password >
</form>
</td>
</tr>
<tr><td class="underline" colspan=2>&nbsp;</td></tr>
</table>
</div>
</div>
<script>
{$scripts}
</script>
</body>
</html>