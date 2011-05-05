  Для того чтобы запускать файлы эксель по ссылкам нужно установить программу 
  <a href="nncron193b3.exe"> nncron.</a>
  
Создать в ней задачу c именем OpenInExcel
и текстом

  <pre>WatchClipboard: "*"
: wget1-mask S" /file:\/\/.+\.(xls)|(xml)/i" ;
Rule: WIN-ACTIVE: "База данных ЗАО МПП*" RE-MATCH: %CLIPBOARD% %wget1-mask% AND
Action:
QUERY: "Открыть в Excel?"
IF
ShowNormal   NormalPriority
START-APP: explorer %CLIPBOARD%
THEN
</pre>
  после этого можно копировать ссылку в буффер и она откроется!!!
