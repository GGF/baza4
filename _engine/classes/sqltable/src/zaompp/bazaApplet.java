/**
 * Апплет используется для обхода системы безопасности броузеров
 * Копирования в буффер системы, получения локальных имен файлов, 
 * запуска в локальной файловой системе
 * Естественно, чтобы запустить нужно согласиться запускать подписаный апплет, 
 * неподписаный вообще без разговоров не будет работать
 */
package zaompp;

/**
 * Апплет нужно подписать
 * Для этого сгенерировать ключ в хранилище пользователя (типа /Users/user/.keystore)
 * генератор ключей лежит  в девелоперской части bin жабы
 * keytool -genkey -alias "GGF" -validity 99999
 * 99999 - срок  в месяцах
 * подписыватель там же
 * jarsigner.exe bazaApplet.jar "GGF"
 */


import java.applet.Applet;
import java.io.IOException;
import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.util.logging.Level;
import java.util.logging.Logger;
import java.awt.datatransfer.*;
import java.awt.*;
import java.security.*;

/**
 *
 * @author igor
 */
public class bazaApplet extends Applet implements ClipboardOwner {

    /**
     * Выводит в консоль то что передается
     * @param objs массив объектов
     */
    public void logme(Object... objs) {
        String text = (String) objs[0]; // преобразовать первый объект в строку
        if (objs.length==1) 
            // если только строка, то вывести используя Logger
            Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, text);
        else
            // иначе  сделать подстановки в строку
            Logger.getLogger(bazaApplet.class.getName()).log(Level.SEVERE, text, objs[1]);
        // не  работает почему то, нужно преобразовать второй элемент как-то
    }
    
    /**
     * Используется для  получения имени выбраного файла
     * @return string
     */
    public String addFile() {
        Frame frame = new Frame (  ) ; 
        FileDialog fd = new FileDialog(frame); // создаем диалог выбора файла
        fd.setVisible(true); // показываем
        String filename = fd.getDirectory()+fd.getFile(); // получаем полный путь к выбранному файлу
        return filename; // возвращаем
        // PROFIT
    }

    /**
     * Обрработка строки (обычно имени файла)
     * передача на стандартную обработку операционной системой
     * @param file файл или командная строка
     * @return int результат
     */
    public int openfile(String file) {
        
       String cmd; // строка  запуска командного процессора операционной системы
       Process process = null;
       cmd = getParameter("cmd"); // передается апплету в коде вставки в броузер
       /*
        * example: <applet code="zaompp.bazaApplet" 
        * archive="_engine/classes/sqltable/js/BazaApplet.jar" 
        * width="1" height="1" name="bazaapplet">
        * <param name="cmd" value="cmd.exe /c">
        * Applet for open files and clipboard 
        * (if you see it java-plugin not started)
        * </applet>
        * 
        */

       this.logme("Start execution!!!"); // для  отладки вывожу кучу нужной и не очень информации
       try //использовать try-catch - это  православно
        {
            this.logme( "args value : = {0}", file);
            this.logme( "cmd value : = {0}", cmd);
            this.logme( "Full command:  = {0} {1}", new Object[]{cmd, file});
            
            if (cmd != null && !cmd.trim().equals("")) // не забыли передать командный процессор
            {
                if (file == null || file.trim().equals("")) // а файлик дадите?
                {
                    this.logme("One"); // попали в первое место
                    final String tempcmd = cmd; // файнал, значит неменяемая
                    // далее создаем через контроллер доступа, а то не даст таки броузер обойти свою защиту
                    // создаем привелегированое действие
                    // в котором создаем объект запуска
                    process = (Process) AccessController.doPrivileged(new PrivilegedAction() {
                        @Override // это просто сообщение жабе чтоб знала, что перекрываем
                        public Object run() {
                            Process p = null;
                            try
                            {
                                // собственно попытка запуска
                                p = Runtime.getRuntime().exec(tempcmd);
                            }
                            catch (Exception e)
                            {
                                // почему тут полный путь логгера понятно? 
                                // мы тут внутре создания объекта
                                zaompp.bazaApplet.this.logme( "Caught exception in privileged block, Exception:{0}", e.toString());
                            }
                            return p; // этот ретёрн передается нашей переменной process
                            // или  нет? мммм....
                        }
                    });
                    // а если мы хотим получить вывод запущенного процесса
                    // создадим буферизованый ридер
                    // ну ты понел...
                    BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(process.getInputStream()));
                    String s;
                    // ну пока оттуда чтото идет логгируем все
                    while((s = bufferedReader.readLine()) != null)
                        this.logme(s);
                }
                else
                { // не дали файлик
                    this.logme("Two"); // попали во второе место
                    final String tempargs = file;
                    final String tempcmd1 = cmd;
                    // Далее та же ботва что и выше
                    process = (Process) AccessController.doPrivileged(new PrivilegedAction() {
                        @Override
                        public Object run()
                        {
                            Process p = null;
                            try
                            {
                                p = Runtime.getRuntime().exec(tempcmd1 + " " + tempargs);
                                //p.wait();
                                // вот с включенным ожиданием я так броузер и не освобождал. или ждал долго
                            }
                            catch (Exception e)
                            {
                                // ужос-ужос иксепшын!
                                zaompp.bazaApplet.this.logme( "Caught exception in privileged block, Exception:{0}", e.toString());
                            }
                            return p;
                        }
                    });
                    /*BufferedReader bufferedReader = new BufferedReader(new InputStreamReader(process.getInputStream()));
                    String s;
                    while((s = bufferedReader.readLine()) != null)
                        this.logme(s);
                     */
                    //Ну можно и включить, вообще не понятно зачем я дублировал код
                }
            }
            else
            { // нету командного процессора
                this.logme("execCmd parameter is null or empty");
            }
        }
        catch (Exception e)
        { // ну и другие проблемы запуска должны быть показаны
            this.logme( "Error executing command --> {0} ({1})", new Object[]{cmd, file});
            this.logme( null, e);
            return 254; // вернем скажем столько
        }
        return process.exitValue(); // хотелось бы посмотреть что же там вернулось

    }

    /**
     * Скопировать переданный текст в системный буфер обмена
     * @param text
     * @return boolean Поллучилось или нет
     */
    public boolean copytoclipboard(String text) {
        // сложный метод получения контроля над буфером
        SecurityManager sm = System.getSecurityManager();
        if (sm != null) {
            try {
               sm.checkSystemClipboardAccess();
            }
            catch (Exception ex) {
                // не вышло МУА_ХА_ХА
                this.logme( null, ex);
                return false;
            }
        } else {
            this.logme( "Can't get SecurityManager");
            return false;
        }
        final Toolkit tk = Toolkit.getDefaultToolkit();
        StringSelection st = new StringSelection(text);
        // тут  тоже приходится через контроллер доступа, вот такая безопасность у броузеров.
        Clipboard cp = (Clipboard) AccessController.doPrivileged(new PrivilegedAction() {
            @Override
                public Object run() {
                    return tk.getSystemClipboard();
                }
            });
        cp.setContents(st,this); //скопировать
        return true; // похоже всегда истина возвращается
    }
    @Override
    public void lostOwnership(Clipboard clip, Transferable tr) {
       this.logme("Lost Clipboard Ownership?!?");
    }
}
