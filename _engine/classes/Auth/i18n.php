<?php

Lang::add(array("Auth" => array(

	"session" => array(

		"cantfinduser" => array(
			"ru" => "Не могу найти пользователя по сессии. Обратитесь к разработчику!",
			"en" => "Can not find user by session. Call to developer",
		),
		"wrongpassword" => array(
			"ru" => "Не могу найти пользователя по паролю. Попробуйте еще раз!",
			"en" => "Can not find user by password. Try again!",
		),
		"old" => array(
			"ru" => "Сессия не верна или устарела!",
			"en" => "Session too old",
		),
		"nosession" => array(
			"ru" => "<span style='display:none'>Сессия не верна или устарела!</span>",
			"en" => "<span style='display:none'>Session too old</span>",
		),
		"success" => array(
			"ru" => "",
			"en" => "",
		),

	),

	"error" => array(

		"noconstructor" => array(
			"ru" => "Нельзя использовать конструктор для создания класса Auth.\\nИспользуйте статический метод getInstance()",
			"en" => "Do not use constructor. Use static method getInstance()",
		),
	),

)));

?>
