<?
// ����������� ����������.
require_once dirname(__FILE__) . '/includes/hypher.php';


class hypher {
	private static $hy_ru;
	static public function staticConstruct(){
		// �������� ����� �������� � ������ ������.
		self::$hy_ru = hypher_load(dirname(__FILE__) . '/includes/hyph_ru_RU.conf');
	}
	// "����������" � ��������� �������� ������ � ����� �������. ����� ������� ��� �������� ����
	static function addhypher($text) {
		return hypher(self::$hy_ru, $text);
	}

}

hypher::staticConstruct();
?>