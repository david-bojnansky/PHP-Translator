<?php d(T_CHARACTER); // sa
ini_set('display_errors', true);

error_reporting(E_ALL | E_DEPRECATED);

function d($s) {
	print "<pre>";
	var_dump($s);
	print "</pre>";
}

require_once '../lib/Umberi/Translator/Tokenizer/TokenIterator.php';

use Umberi\Translator\Tokenizer\TokenIterator;

$t = new TokenIterator(token_get_all(file_get_contents(__FILE__)));

foreach ($t as $k => $v) {
	d($k);
	d($v);
}

