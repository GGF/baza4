<!--   Copyright 2011 Igor Fedoroff   |  g_g_f@mail.ru  -->
<!DOCTYPE html>
<html>
  <head>
    <meta name="Author" content="����� �������">
	<meta name="Description" content="��� ���">
    <title>{$title}</title>
    {$lego->getAllHeaderblock()}
  </head>
  <body>
  {$adminhere}
  <a href="{$linkbase}" class="glavmenu" title="�����"><div class="glavmenu">������� ����</div></a>
  {$birthdays}
  {$bashcite}
  {$menu}

  <div id="maindiv">
  {$content}
  </div>
  {$console}
  </body>
</html>
