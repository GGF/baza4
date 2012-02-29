<!DOCTYPE html>
<html lang="ru" class="no-js">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <title>{$title}</title>
    <meta name="Author" content="Игорь Федоров">
    <meta name="Description" content="ЗАО МПП">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="/favicon.ico">

    {$css}
</head>

<body>
<div id="container">
<header>
    {$adminhere}
    <a href="{$linkbase}" class="glavmenu" title="Домой"><div class="glavmenu">Главное меню</div></a>
    <!--a href="{$linkbase}" class="glavmenu" title="Домой"><div class="glavmenu">Главное меню</div></a-->
    {$birthdays}
    {$bashcite}
    {$menu}
</header>
<div id="maindiv">
    {$content}
</div>
<footer>
</footer>
</div>
{$scripts}
</body>
</html>
<!--   Copyright 2011 Igor Fedoroff   |  g_g_f@mail.ru  -->
