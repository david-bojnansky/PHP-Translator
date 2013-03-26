<?php

/**
 *
 */

namespace Umberi\Translator;

/**
 *
 */
define('T_COMMA', ',');

/**
 *
 */
define('T_L_BRACKET', '(');

/**
 *
 */
define('T_R_BRACKET', ')');

/**
 *
 */
final class Tokenizer {
	/**
	 * ...
	 */
	const FUNC = 1;

	/**
	 * ...
	 */
	const DMTHD = 2;

	/**
	 * ...
	 */
	const SMTHD = 4;

	/**
	 * ...
	 */
	const MTHD = 6;

	/**
	 * ...
	 */
	const ALL = 7;

	/**
	 *
	 */
	private $funcs = array();

	/**
	 *
	 */
	private $fenum = 'translate';

	/**
	 *
	 */
	public function __construct() {
		if (!extension_loaded('tokenizer')) {

		}
	}

	/**
	 * ...
	 *
	 * @param  string $function
	 *   ...
	 * @param  mixed $argument
	 *   ...
	 * @param  mixed $samples
	 *   ...
	 * @return Umberi\Translator\Tokenizer
	 */
	public function addFunction($function, $argument = 1, $samples = null) {
		$this->check($samples, $function)->funcs[] = array(
			'func' => $function,
			'arg'  => $argument, 
		);
		return $this;
	}

	/**
	 * ...
	 *
	 * @param  mixed $object
	 *   ...
	 * @param  string $method
	 *   ...
	 * @param  mixed $argument
	 *   ...
	 * @param  mixed $samples
	 *   ...
	 * @return Umberi\Translator\Tokenizer
	 */
	public function addMethod($object, $method, $argument = 1, $samples = null) {
		$this->check($samples, $method)->funcs[] = array(
			'obj'  => $this->spread($object, '->'),
			'func' => $method,
			'arg'  => $argument, 
		);
		return $this;
	}

	/**
	 * ...
	 *
	 * @param  mixed $class
	 *   ...
	 * @param  string $method
	 *   ...
	 * @param  mixed $argument
	 *   ...
	 * @param  mixed $samples
	 *   ...
	 * @return Umberi\Translator\Tokenizer
	 */
	public function addStaticMethod($class, $method, $argument = 1, $samples = null) {
		$this->check($samples, $method)->funcs[] = array(
			'obj'  => $this->spread($class, '::'),
			'func' => $method,
			'arg'  => $argument, 
		);
		return $this;
	}

	/**
	 * ...
	 *
	 * @param  mixed  $smpls
	 *   ...
	 * @param  string $pttrn
	 *   ...
	 * @return Umberi\Translator\Tokenizer
	 */
	private function check($smpls, $pttrn) {
		/**
		 * @var string $smpl
		 *   ...
		 */
		foreach ((array) $smpls as $smpl) {
			if (preg_match("~^$pttrn$~ADi", $smpl)) {
				// OK
			} else {
				// ERROR
			}
		}
	}

	/**
	 * ...
	 *
	 * @param  string $src
	 *   ...
	 * @param  string $append
	 *   ...
	 * @return array
	 *   ...
	 */
	private function spread($src, $append) {
		/**
		 * ...
		 *
		 * @var array
		 */
		$parts = array();

		/**
		 * ...
		 *
		 * @var array
		 */
		$profiles = array(
			array('~^[a-z_]\\w*$~ADi'),
		);

		/**
		 * ...
		 *
		 * @var array
		 */
		$allowed = array(
			'[',
			']',
			'(',
			')',
			T_CONSTANT_ENCAPSED_STRING,
			T_DNUMBER,
			T_LNUMBER,
			T_NS_SEPARATOR,
			T_NEW,
			T_OBJECT_OPERATOR,
			T_DOUBLE_COLON,
			T_STRING,
			T_VARIABLE,
			T_WHITESPACE,
		);

		/**
		 * @var mixed $token
		 *   ...
		 */
		foreach (token_get_all(rtrim($src, $append)) as $token) {

			if (is_array($token) && $token[0] != T_WHITESPACE) {
				$parts[] = $token[1];
			} elseif (is_string($token)) {
				$parts[] = $token;
			}
		}
		$parts[] = $append;
		return $parts;
	}

	/**
	 * ...
	 *
	 * @param string $src
	 *   ...
	 * @return array
	 *   ...
	 */
	public function scan($src) {
		array(
			//
			array('?', T_OBJECT_OPERATOR, T_STRING, '('),
			//
			array('?', T_DOUBLE_COLON, T_STRING, '('),
			//
			array(T_STRING, '('),
		);

		/**
		 *
		 */
		$pttrnsInFunc = array(
			//
			array(1, T_CONSTANT_ENCAPSED_STRING, ')'),
			//
			array(1, T_CONSTANT_ENCAPSED_STRING, ','),
			//
			array(2, T_COMMENT, T_CONSTANT_ENCAPSED_STRING),
			//
			array(3, T_VARIABLE, '=', T_CONSTANT_ENCAPSED_STRING),
			//
			array(3, T_VARIABLE, '=', T_COMMENT, T_CONSTANT_ENCAPSED_STRING),
			//
			array(3, T_VARIABLE, '=', T_COMMENT, T_CONSTANT_ENCAPSED_STRING),
		);

		$tokens = token_get_all($src);

		/**
		 * @var int $ci
		 *   ...
		 * @var int $di
		 *   ...
		 */
		for ($ci = $di = 0 ; $di < count($tokens) ; $ci++, $di++) {
			if (is_array($tokens[ $di ])) {
				/**
				 * @var int $token
				 *   ...
				 * @var string $content
				 *   ...
				 * @var int $line
				 *   ...
				 */
				list($token, $content, $line) = $tokens[ $di ];
			} else {
				/**
				 * ...
				 *
				 * @var string
				 */
				$token = $tokens[ $di ];
			}
			/**
			 * @var array $func
			 *   ...
			 */
			foreach ($this->funcs as $func) {
				if (isset($func['obj'])) {
					/*
					 *
					 */

				} else {
					/*
					 *
					 */
				}
			}
			if ($token == T_STRING || $token == T_VARIABLE) {

			}

			/*switch ($token) {
				case T_STRING:
				case :
				case :
					continue;
			} // ... $hu()->gu->fw->translate();*/
		}
		return array();
	}

	/**
	 * ...
	 *
	 * @param  string $src ...
	 * @return array ...
	 */
	public function scanOLD2($src) {

		/**
		 * ...
		 *
		 * @var array
		 */
		$funcs = array();

		$this->tokens = token_get_all($src);

		/**
		 * @var int          $i     ...
		 * @var array|string $token ...
		 */
		foreach ($tokens as $i => $token) {

			if (is_array($token)) {

				/**
				 * @var int    $token   ...
				 * @var string $content ...
				 * @var int    $line    ...
				 */
				list($token, $content, $line) = $token;

			}
			switch ($token) {
				case 1:
				default:
					continue;
			}
		}
		return array();
	}

	/**
	 * ...
	 *
	 * @param  int  $i           ...
	 * @param  bool $skipComment ...
	 * @param  int  $after       ...
	 * @return array ...
	 */
	private function getNextToken($i, $skipComment = true, $after = 0) {

		if (!isset($this->tokens[ ++$i ])) {
			return array();
		}

		/**
		 * ...
		 *
		 * @var array|string
		 */
		$token = $this->tokens[ $i ];

		if (is_array($token)) {

			/**
			 * @var int    $token   ...
			 * @var string $content ...
			 * @var int    $line    ...
			 */
			list($token, $content, $line) = $token;

			if ($token == T_WHITESPACE || ($token == T_COMMENT && !$skipComment) || $token == T_DOC_COMMENT) {
				return $this->getNextToken($i, $skipComment, $skip);
			}
			if ($skip > 0) {
				return $this->getNextToken($i, $skipComment, $skip - 1);
			}
			return array(
				'i'       => $i,
				'token'   => $token,
				'content' => $content,
				'line'    => $line,
			);
		}
		if ($skip > 0) {
			return $this->getNextToken($i, $skipComment, $skip - 1);
		}
		return array(
			'i'     => $i,
			'token' => $token,
		);
	}

	/**
	 *
	 */
	public function scanOLD($src) {
		/**
		 * ...
		 *
		 * @var bool
		 */
		$translate = true;

		/**
		 * ...
		 *
		 * @var int
		 */
		$processes = 0;

		/**
		 * ...
		 *
		 * @var array
		 */
		$lvls = array();

		/**
		 * ...
		 *
		 * @var int
		 */
		$type = self::FUNC;

		/**
		 * ...
		 *
		 * @var int|array
		 */
		$order = 1;

		/**
		 * ...
		 *
		 * @var array
		 */
		$orders = array(
			'current' => array(),
			'' => array(),
		);

		/**
		 * ...
		 *
		 * @var array
		 */
		$nextNeed = array(
			'T_COMMA'                    => false,
			'T_CONSTANT_ENCAPSED_STRING' => false,
			'T_L_BRACKET'                => false,
			'T_R_BRACKET'                => false,
			'T_STRING'                   => false,
		);

		/**
		 * ...
		 *
		 * @var array
		 */
		$expects = array();

		/**
		 * ...
		 *
		 * @var array
		 */
		$tokens = token_get_all($src);

		/**
		 * @var int          $i     ...
		 * @var array|string $token ...
		 */
		foreach ($tokens as $i => $token) {
			if (is_array($token)) {
				/**
				 * @var int    $token ...
				 * @var string $code  ...
				 * @var int    $line  ...
				 */
				list($token, $code, $line) = $token;
			}
			switch ($token) {
				/*
				 * Medzikrok A
				 */
				case T_WHITESPACE:
					continue;

				/*
				 * Medzikrok B
				 */
				case T_DOC_COMMENT:
					continue;

				/*
				 * Medzikrok C
				 */
				case T_COMMENT: // `#...` || `//...` || `/*...*/`
					// ...
					// `/* @translate */`
					// ...
					// `/* @translate false */`
					// ...
					// `/* @translate no */`
					// ...
					// `/* @translate true */`
					// ...
					// `/* @translate yes */`
					// ...
					if (preg_match('~^/\\*[*\\s]*@translate(?:[*\\s]*\\s[*\\s]*(?i)(?P<flag>false|no|true|yes))?[*\\s]*\\*/$~AD', $code, $matches)) {
						$translate = true;
						if (array_key_exists('flag', $matches)) {
							$translate = !in_array(strtolower($matches['flag']), array('false', 'no'));
						}
						$nextNeed['T_CONSTANT_ENCAPSED_STRING'] = true;
					}
					continue;

				/*
				 * Krok č. 1A
				 */
				case T_OBJECT_OPERATOR:
					$type = self::DMTHD;
					continue;

				/*
				 * Krok č. 1B
				 */
				case T_DOUBLE_COLON:
					$type = self::SMTHD;
					continue;

				/*
				 * Krok č. 2
				 */
				case T_STRING:
					/**
					 * @var array $func ...
					 */
					foreach ($this->funcs as $func) {
						if ($type & $func['types'] && preg_match("~^{$func['pttrn']}$~ADi", $code)) {
							$order = $func['order'];
							$expects = 'T_L_BRACKET';
							break;
						}
					}
					continue;

				/*
				 * Krok č. 3
				 */
				case T_L_BRACKET:
					if ($expects == 'T_L_BRACKET') {
						/*
						 * Zvýši počet aktuálne spracovávaných funkcií určených na prekladanie. Ak sa žiadna funkcia aktuálne
						 * nespracúva, tak hodnota tejto premennej je 'int(0)'. Väčšinou sa bude spracovávať iba jedna funkcia, 
						 * no môže nastať prípad, keď sa budú spracovávať dve a viac funkcií naraz, a to v prípade, ak je
						 * funkcia vložená do inej funkcii atď. Avšak, toto sa príliš často diať nebude, iba naozaj vo výnimočných
						 * prípadoch, situáciach.
						 *
						 * Príklady:
						 *
						 * Bude sa spracovávať iba JEDNA funkcia určená na prekladanie.
						 *     translate('Text');
						 * Naraz sa budú spracovávať DVE funkcie určené na prekladanie.
						 *     translate(translate('Text'));
						 * Naraz sa budú spracovávať TRI funkcie určené na prekladanie.
						 *     translate(translate(t('Text')));
						 * Atď.
						 */
						$processes++;

						$orders[ $processes ][] = $order;
						$lvls[ $processes ] = 0;
					}
					if ($processes) {
						$lvls[ $processes ]++;
					}
					$expects = '';
					continue;

				/*
				 * Krok č. 3
				 */
				case T_VARIABLE:

					continue;

				// ...
				// `"..."`
				// ...
				// `'...'`
				// ...
				case T_CONSTANT_ENCAPSED_STRING:
					if ($nextNeed['T_CONSTANT_ENCAPSED_STRING'] && $translate) {
						$text = substr($code, 1, -1);
						$nextNeed['T_COMMA'] = $nextNeed['T_RIGHT_BRACKET'] = true;
					}
					continue;

				// ...
				// `,`
				// ...
				case T_COMMA:

					continue;

				// ...
				// `)`
				// ...
				case T_R_BRACKET:
					if ($processes) {
						$lvls[ $processes ]--;
					}
					if ($processes > 0 && !$lvls[ $processes ]) {
						$processes--;
					}
					continue;

				// ...
				// `všetky ostatné symboly`
				// ...
				default:
					$translate = true;
					$type = self::FUNC;
					$order = 1;
					$nextNeed['T_CONSTANT_ENCAPSED_STRING'] = $nextNeed['T_LEFT_BRACKET'] = false;
					continue;
			}
		}
	}

	/**
	 * ...
	 *
	 * @param  array $tokens
	 * @param  int   $i
	 * @return mixed
	 */
	private function nextToken($tokens, $i) {
		return isset($tokens[ ++$i ]) ? is_array($tokens[ $i ]) && $tokens[ $i ][0] === T_WHITESPACE ? $this->nextToken($tokens, $i) : $tokens[ $i ] : null;
	}
}






/**
 *
 */
final class TokenizerOld {
	/**
	 * @var array
	 */
	private static $fns = array();

	/**
	 * @var array
	 */
	private static $tokens;

	/**
	 * @var bool
	 */
	private static $isFn = false;

	/**
	 * @var bool
	 */
	private static $isFnDef = false;

	/**
	 *
	 */
	public static function addFn($name, $inOrder = 1) {
		if (array_key_exists($name, self::$fns)) {
			throw new Exception;
		}
		if (!preg_match("/^(?:[a-zA-Z_]|[a-zA-Z_*]\\w|[a-zA-Z_]\\*|[a-zA-Z_*](?:\\w\\*?)+)$/AD", $name)) {
			throw new Exception;
		}
		if (strpos($name, '*') === 0) {
			$pattern = "[a-zA-Z_]\\w*" . str_replace('*', "\\w+", substr($name, 1));
		} else {
			$pattern = str_replace('*', "\\w+", $name);
		}
		self::$fns[ $name ] = array(
			'name'    => $name,
			'pattern' => "/^$pattern$/AD",
			'inOrder' => $inOrder,
		);
	}

	/**
	 *
	 */
	public static function process($filename) {
		self::$tokens = token_get_all(file_get_contents($filename));
		//d(self::$tokens);
		/**
		 * @var int   $i
		 * @var mixed $val
		 */
		foreach (self::$tokens as $i => $val) {
			if (is_string($val)) {
				self::processTwoArgs($i, $val);
			} else {
				self::processFourArgs($i, $val[0], $val[1], $val[2]);
			}
		}
	}

	/**
	 *
	 */
	private static function processTwoArgs($i, $token) {
		switch ($token) {
			case '{':
				self::$isFnDef = false;
				break;
		}
	}

	/**
	 *
	 */
	private static function processFourArgs($i, $token, $code, $line) {
		switch ($token) {
			case T_FUNCTION:
				self::$isFnDef = true;
				break;
			case T_STRING:
				if (!self::$isFnDef && (self::$tokens[ $i + 1 ] === '(' || is_array(self::$tokens[ $i + 1 ]) && self::$tokens[ $i + 1 ][0] === T_WHITESPACE && self::$tokens[ $i + 2 ] === '(')) {
					foreach (self::$fns as $fn) {
						if (preg_match($fn['pattern'], $code)) {
							self::$isFn = true;
							break;
						}
					}
				}
				break;
			case T_CONSTANT_ENCAPSED_STRING:
				if (self::$isFn) {
					if (self::$tokens[ $i + 1 ] == ',' || self::$tokens[ $i + 1 ] == ')') {
						d(substr($code, 1, -1));
					} elseif (is_array(self::$tokens[ $i + 1 ]) && self::$tokens[ $i + 1 ][0] == T_WHITESPACE && (self::$tokens[ $i + 2 ] == ',' || self::$tokens[ $i + 2 ] == ')')) {
						d(substr($code, 1, -1));
					}
					self::$isFn = false;
				}
				break;
		}
	}
}
