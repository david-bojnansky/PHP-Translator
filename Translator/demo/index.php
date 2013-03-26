<?php

ini_set('display_errors', true);

error_reporting(E_ALL | E_DEPRECATED);

function d($s) {
	print "<pre>";
	var_dump($s);
	print "</pre>";
}

require_once '../lib/Umberi/Translator/Collector.php';

use Umberi\Translator\Collector;

//d(token_name(315));
function parse($filename) {
	/**
	 * @var bool
	 */
	$isFuncDef = false;

	/**
	 * @var
	 */
	$tokens = token_get_all(file_get_contents($filename));
	//d($tokens);
	foreach ($tokens as $id) {
		if (is_string($id)) {
			switch ($id) {
				case '{':
					$isFuncDef = false;
					break;
			}
		} else {
			list($id, $token, $line) = $id;
			switch ($id) {
				case T_FUNCTION:
					$isFuncDef = true;
					break;
				case T_STRING:
					if (!$isFuncDef && $token === 'translate') {
						
					}
					break;
			}
		}
		
		/*if (is_string($token)) {
			continue;
		}
		if ($token[0] === T_STRING && $token[1] === 'translate' && $tokens[ $i + 2 ][0] !== T_FUNCTION) {
			//d($token);
		}*/
	}
}
/*
Collector::addFn('_');
Collector::addFn('t');
Collector::addFn('translate');

Collector::process(__FILE__);
*/
/*$t = Translator::setInstance(__DIR__ . DIRECTORY_SEPARATOR . 'langs', 'en-US', array(
	'prod' => false,
));

$t->setPrimaryLocale();*/

$file = 'test';

/*

  @translate false


 */

/* @translate */ "Ahoj mama..." . 'as';

/*
 * @translate *
 */
'Ako sa máš?';


function scan($code) {

	$tokens = token_get_all($code);

	foreach ($tokens as $token) {
		//d($token);
	}
}
scan(file_get_contents(__FILE__));

$collector = new Collector;

$collector
  ->addFunction('translate', Collector::TYPE_METHOD)
  ->scan(file_get_contents(__FILE__));
/*
$collector
  ->addFunction('__')
  ->addMethod(null, '_')
  ->addMethod('$translator', 'translate')
  ->addStaticMethod(null, '_')
  ->addStaticMethod('Umberi\\Translator', 'translate')
  

  ->addFunc('_')
  ->addFunc('t')
  ->addFunc('translate', 1, Collector::SMTHD)
// scan
  ->scan(file_get_contents(__FILE__));*/

@__("Mother fucke!r!");
@$t->__("Mother fucke!r!");
@$t-> translate(@$t-> translate  /* @translate */  ("Mother fucker!"));

