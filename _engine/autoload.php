<?

/**
 * ПОДКЛЮЧИТЕ ЭТОТ ФАЙЛ, ЧТОБЫ ИМЕТЬ ДОСТУП К КЛАССАМ PHP-LEGO
 * 
 * @author   Дубров Олег
 * 
 * Функция автоматической подгрузки неопределенных классов.
 * 
 * Понятия:
 * Локальные классы - папка, в которой содержатся файлы классов, расположенная 
 * по пути "текущий_каталог/$class_folder/"
 * Глобальные классы - папка, в которой содержатся файлы классов и этот файл. 
 * Обычно это "корень_проекта/$class_folder/"
 * $GLOBALS["CLASS_PATHS"] - глобальный массив путей, по которым система 
 * автоподкрузки будет искать файлы классов,
 * если они не были найдены ни в локальных классах, ни в глобальных классах
 * 
 * Чтобы класс был доступен для автозагрузки, имя файла должно быть в 
 * формате "имя_класса.class.php"
 * ВНИМАНИЕ! Имена файлов классов регистрозависимые. т.е. 
 * класс MyClass должен находится в файле MyClass.class.php.
 * 
 * Будьте внимательны, класс в локальных классах "перегружает" класс с таким же 
 * именем из глобальных классов. 
 * Если существует два класса с одним названием в глобальных и локальных 
 * классах,
 * то использоваться будет локальный.
 * 
 * Если вы хотите сгруппировать несколько классов в папку, например, "myfolder",
 * то для этого просто поставьте подчеркивание в имени класса
 * таким образом myfolder_MyClass. Файл с классом в папке "myfolder" 
 * должен называться myfolder_MyClass.class.php
 * 
 * Не рекомендуется добавлять много путей в массив $GLOBALS["CLASS_PATHS"] 
 * т.к. это отрицательно влияет на производительность
 */
define('__LEGO_DIR__', strtr(str_replace(realpath($_SERVER['DOCUMENT_ROOT']),
                    '', realpath(__DIR__)), '\\', '/'));

if (!$_SERVER["debug"]["noCache"]["php"]) {
    require_once realpath($_SERVER['DOCUMENT_ROOT']).cache::buildScript($_SESSION["cache"], 'php');
}

/**
 * Функция автоподгрузки классов. Подключает необходимый файл по имени класса
 * 
 * @param mixed $class_name
 */
function __autoload($class_name) {

        $class_folder = 'classes';
        // Локальные классы (в каждой папке проекта может быть папка $class_folder. 
        // Она и называется локальными классам)
        $class_paths[] = dirname($_SERVER['SCRIPT_FILENAME']) . "/$class_folder/";

        // Общие классы (та папка, в которой лежит этот файл и 
        // является глобальными классами)
        $class_paths[] = dirname(__FILE__) . "/classes/";

        //добавим пути из глобальной переменной $CLASS_PATHS
        if (!empty($GLOBALS["CLASS_PATHS"])) {
            if (!is_array($GLOBALS["CLASS_PATHS"]))
                throw new Exception('$CLASS_PATHS must be array!');
            $class_paths = array_merge($class_paths, $GLOBALS["CLASS_PATHS"]);
        }

        //Группировка по подпапкам (для примера возьмем класс с именем A_B_C)
        $slashed_class_name = str_replace("_", "/", $class_name); // A/B/C
        $short_path = substr($slashed_class_name, 0, strrpos($slashed_class_name, '/')); // A/B

        
        foreach ($class_paths as $class_path) {
            // если класс A_B_C находится в файле /A/B/C.class.php
            $file_full_name = "{$class_path}/{$slashed_class_name}.class.php";
            if (file_exists($file_full_name)) {
                require_once($file_full_name);
                if ($class_name!='cache')
                    $_SESSION["cache"][$class_name] = $file_full_name;
                return;
            }

            // если класс A_B_C находится в файле /A/B/C/A_B_C.class.php
            $file_full_name =
                    "{$class_path}/{$slashed_class_name}/{$class_name}.class.php";
            if (file_exists($file_full_name)) {
                require_once($file_full_name);
                if ($class_name!='cache')
                    $_SESSION["cache"][$class_name] = $file_full_name;
                return;
            }

            // если класс A_B_C находится в файле /A/B/A_B_C.class.php
            $file_full_name = "{$class_path}/{$short_path}/{$class_name}.class.php";
            if (file_exists($file_full_name)) {
                require_once($file_full_name);
                if ($class_name!='cache')
                    $_SESSION["cache"][$class_name] = $file_full_name;
                return;
            }

            // если класс A_B_C находится в файле /A/B/A_B_C/A_B_C.class.php
            $file_full_name =
                    "{$class_path}/{$short_path}/{$class_name}/{$class_name}.class.php";
            if (file_exists($file_full_name)) {
                require_once($file_full_name);
                if ($class_name!='cache')
                    $_SESSION["cache"][$class_name] = $file_full_name;
                return;
            }
        }
}

