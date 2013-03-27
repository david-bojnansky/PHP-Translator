<?php

ini_set('display_errors', true);

error_reporting(E_ALL | E_DEPRECATED);

function d($s) {
	print "<pre>";
	var_dump($s);
	print "</pre>";
}
$g = ("Omaj got");
require_once ('../lib/Umberi/Translator/Collector/PHP.php');

use Umberi\Translator\Collector;

$collector = new Collector\PHP;

d($collector->trigger(__FILE__));
