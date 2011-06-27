<?php

Lang::add(array("Auth" => array(
	
	"session" => array(
		
		"cantfinduser" => array(
			"ru" => "Не могу найти пользователя по сессии. Обратитесь к разработчику!", 
			"en" => "Can not find user by session. Call to developer",
		),
		
		"old" => array(
			"ru" => "Сессия не верна или устарела!", 
			"en" => "Session too old",
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
