<?php

/**
 *
 */
namespace Umberi;

/**
 *
 */
final class Translator {
	/**
	 * @var Umberi\Translator
	 */
	private static $instance;

	/**
	 * @var bool
	 */
	private $prod = true;

	/**
	 * @var string
	 */
	private $dir;

	/**
	 *
	 */
	public static function setInstance($dir, array $opt = null) {
		if (self::$instance instanceof Translator) {
			throw new E;
		}
		return self::$instance = new Translator($dir, empty($opt) ? null : $opt);
	}

	/**
	 *
	 */
	public static function getInstance() {
		if (self::$instance instanceof Translator) {
			return self::$instance;
		}
		throw new E();
	}

	/**
	 *
	 */
	private function __construct($dir, array $opt) {
		if (!is_dir($dir)) {
			throw new Exception;
		}
		if (!is_readable($dir)) {
			throw new Exception;
		}
		$that = self::$instance;
		$dir = rtrim($dir, '/\\') . ;
		if () {

		}
	}

	/**
	 *
	 */
	/*private function parseTranslation($filename) {
		$key = null;
		$data = array();
		foreach (file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
			if (preg_match('/^\\S+/A', $line)) {
				$key = trim($line, '"');
			} elseif (!is_null($)) {
				$data[ $key ] = $line;
				$key = null;
			}
		}
		return $data;
	}*/

	/**
	 * @param  string            $filename
	 * @param  array             $data
	 * @return Umberi\Translator
	 */
	private function putContent($filename, array $data) {
		$added = array();
		$content = "<?php return array(";
		foreach ($data as $k => $v) {
			if (!in_array($k, $added)) {
				$content .= PHP_EOL . "\t" . '"' . addcslashes($k, '"') . '" => "' . addcslashes($v, '"') . '",';
				$added[] = $k;
			}
		}
		$content .= (empty($data) ? '' : PHP_EOL) . ");" . PHP_EOL;
		file_put_contents($filename, $content, LOCK_EX);
		return $this;
	}

	/**
	 *
	 */
	private function putContentEx($filename, array $data) {
		$content = "<?php return array(";
		foreach ($data as $k => $v) {

		}
		return $this;
	}

	/**
	 *
	 */
	public function __toString() {
		return '';	
	}

	/**
	 *
	 */
	public function translate($msg, $count = null) {

	}

	/**
	 *
	 */
	public function __destruct() {
		self::$instance = null;
	}
}
