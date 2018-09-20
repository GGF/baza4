  Для того чтобы запускать файлы эксель по ссылкам нужно установить программу 
  <a href="http://www.nncron.ru/download/nncron193b3.exe"> nncron.</a>
  
Создать в ней задачу c именем clipboard
и текстом

<pre>
#( clipboard_command
NoLog
\ NoActive
WatchClipboard: "mppapp-command*"
Action:
CLIPBOARD 
\ проверка на русский язык в броузере
2DUP \ продублировать строку
S" ??"
StringGetPos \ найти вторую в первой
0<> \ нашли строку
IF
	TYPE CR
	TMSG: "Включий русский язык в броузере, неправильно копируется" 10
ELSE
	14 /STRING \ обезать ключ из строки
	2DUP \ продублировать строку
	S" OPENFILE" \ строка для сравнгения
	StringGetPos \ найти вторую в первой
	0<> \ нашли строку
	IF
		8 /STRING \ отрезать команду
		2DUP
		TYPE CR \ показать
		SWHide \ скрытое оно
		START-APP \ запустить
	ELSE
		TYPE CR
	THEN
THEN
\ дальше почистить клипбоард
S" "
CLIPBOARD!


)#
</pre>

Если не работает, проверьте в русском ли режиме клавиатура в броузере.