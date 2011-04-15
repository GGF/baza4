<?

/**
 * ���������� ���� ����, ����� ����� ������ � ������� PHP-LEGO
 * 
 * @author   ������ ����
 * 
 * ������� �������������� ��������� �������������� �������.
 * 
 * �������:
 * ��������� ������ - �����, � ������� ���������� ����� �������, ������������� 
 * �� ���� "�������_�������/$class_folder/"
 * ���������� ������ - �����, � ������� ���������� ����� ������� � ���� ����. 
 * ������ ��� "������_�������/$class_folder/"
 * $GLOBALS["CLASS_PATHS"] - ���������� ������ �����, �� ������� ������� 
 * ������������� ����� ������ ����� �������,
 * ���� ��� �� ���� ������� �� � ��������� �������, �� � ���������� �������
 * 
 * ����� ����� ��� �������� ��� ������������, ��� ����� ������ ���� � 
 * ������� "���_������.class.php"
 * ��������! ����� ������ ������� �����������������. �.�. 
 * ����� MyClass ������ ��������� � ����� MyClass.class.php.
 * 
 * ������ �����������, ����� � ��������� ������� "�����������" ����� � ����� �� 
 * ������ �� ���������� �������. 
 * ���� ���������� ��� ������ � ����� ��������� � ���������� � ��������� 
 * �������,
 * �� �������������� ����� ���������.
 * 
 * ���� �� ������ ������������� ��������� ������� � �����, ��������, "myfolder",
 * �� ��� ����� ������ ��������� ������������� � ����� ������
 * ����� ������� myfolder_MyClass. ���� � ������� � ����� "myfolder" 
 * ������ ���������� myfolder_MyClass.class.php
 * 
 * �� ������������� ��������� ����� ����� � ������ $GLOBALS["CLASS_PATHS"] 
 * �.�. ��� ������������ ������ �� ������������������
 */
define('__LEGO_DIR__', strtr(str_replace(realpath($_SERVER['DOCUMENT_ROOT']),
                    '', realpath(__DIR__)), '\\', '/'));

/**
 * ������� ������������� �������. ���������� ����������� ���� �� ����� ������
 * 
 * @param mixed $class_name
 */
function __autoload($class_name) {

    if ($_SERVER[debug][noCache][php] || !file_exists($_SERVER['SYSCACHE'] . '/autoexec_' . md5(implode($_REQUEST, '')) . '.php')) {
        $class_folder = 'classes';
        // ��������� ������ (� ������ ����� ������� ����� ���� ����� $class_folder. 
        // ��� � ���������� ���������� �������)
        $class_paths[] = dirname($_SERVER['SCRIPT_FILENAME']) . "/$class_folder/";

        // ����� ������ (�� �����, � ������� ����� ���� ���� � 
        // �������� ����������� ��������)
        $class_paths[] = dirname(__FILE__) . "/classes/";

        //������� ���� �� ���������� ���������� $CLASS_PATHS
        if (!empty($GLOBALS["CLASS_PATHS"])) {
            if (!is_array($GLOBALS["CLASS_PATHS"]))
                throw new Exception('$CLASS_PATHS must be array!');
            $class_paths = array_merge($class_paths, $GLOBALS["CLASS_PATHS"]);
        }

        //����������� �� ��������� (��� ������� ������� ����� � ������ A_B_C)
        $slashed_class_name = str_replace("_", "/", $class_name); // A/B/C
        $short_path = substr($slashed_class_name, 0, strrpos($slashed_class_name, '/')); // A/B

        foreach ($class_paths as $class_path) {
            // ���� ����� A_B_C ��������� � ����� /A/B/C.class.php
            $file_full_name = "{$class_path}/{$slashed_class_name}.class.php";
            if (file_exists($file_full_name)) {
                require_once($file_full_name);
                $_SESSION[cache][] = $file_full_name;
                return;
            }

            // ���� ����� A_B_C ��������� � ����� /A/B/C/A_B_C.class.php
            $file_full_name =
                    "{$class_path}/{$slashed_class_name}/{$class_name}.class.php";
            if (file_exists($file_full_name)) {
                require_once($file_full_name);
                $_SESSION[cache][] = $file_full_name;
                return;
            }

            // ���� ����� A_B_C ��������� � ����� /A/B/A_B_C.class.php
            $file_full_name = "{$class_path}/{$short_path}/{$class_name}.class.php";
            if (file_exists($file_full_name)) {
                require_once($file_full_name);
                $_SESSION[cache][] = $file_full_name;
                return;
            }

            // ���� ����� A_B_C ��������� � ����� /A/B/A_B_C/A_B_C.class.php
            $file_full_name =
                    "{$class_path}/{$short_path}/{$class_name}/{$class_name}.class.php";
            if (file_exists($file_full_name)) {
                require_once($file_full_name);
                $_SESSION[cache][] = $file_full_name;
                return;
            }
        }
    } else {
        /* 
         * � ������ ���� ������� ��������� ��������� ����������� ���������� 
         */
        require_once $_SERVER['SYSCACHE'] . '/autoexec_' . md5(implode($_REQUEST, '')) . '.php';
    }
}

