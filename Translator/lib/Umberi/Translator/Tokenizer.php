<?php

/**
 * Lorem Ipsum
 */

namespace Umberi\Translator;

/**
 * Lorem Ipsum
 */
final class Tokenizer {
	/**
	 * Lorem Ipsum
	 */
	const TYPE_FUNCTION = 1;

	/**
	 * Lorem Ipsum
	 */
	const TYPE_METHOD = 2;

	/**
	 * Lorem Ipsum
	 */
	const TYPE_STATIC_METHOD = 4;
 
	/**
	 * Lorem Ipsum
	 */
	const TYPE_ALL = 7;

	/**
	 * Lorem Ipsum
	 *
	 * @var array
	 */
	private $funcs = array(
		array(
			'rules' => array(T_OBJECT_OPERATOR, array(T_STRING, '_'), array(T_CHARACTER, '\\(')),
			'msgAt' => 1,
		),
		array(
			'rules' => array(T_OBJECT_OPERATOR, array(T_STRING, 't'), array(T_CHARACTER, '\\(')),
			'msgAt' => 1,
		),
		array(
			'rules' => array(T_OBJECT_OPERATOR, array(T_STRING, 'translate'), array(T_CHARACTER, '\\(')),
			'msgAt' => 1,
		),
	);

	/**
	 * Lorem Ipsum
	 */
	public function __construct() {
	}

	/**
	 * Lorem Ipsum
	 *
	 * @param string $name
	 *   Lorem Ipsum
	 * @param int $type
	 *   Lorem Ipsum
	 * @param int|array $messageAt
	 *   Lorem Ipsum
	 * @param void|array|string $samples
	 *   Lorem Ipsum
	 * @return Umberi\Translator\Tokenizer
	 */
	public function addFunction($name, $type = self::TYPE_ALL, $messageAt = 1, $samples = null) {
		/**
		 * @var string $smpl
		 *   Lorem Ipsum
		 */
		foreach ((array) $samples as $smpl) {
			if (preg_match("~^$name$~ADi", $smpl)) {
				// TODO: OK
			} else {
				// TODO: ERROR
			}
		}
		if ($type & self::TYPE_FUNCTION) {
			$this->funcs[] = array(
				'rules' => array(array(T_STRING, $name), array(T_CHARACTER, '\\(')),
				'msgAt' => $messageAt,
			);
		}
		if ($type & self::TYPE_METHOD) {
			$this->funcs[] = array(
				'rules' => array(T_OBJECT_OPERATOR, array(T_STRING, $name), array(T_CHARACTER, '\\(')),
				'msgAt' => $messageAt,
			);
		}
		if ($type & self::TYPE_STATIC_METHOD) {
			$this->funcs[] = array(
				'rules' => array(T_DOUBLE_COLON, array(T_STRING, $name), array(T_CHARACTER, '\\(')),
				'msgAt' => $messageAt,
			);
		}
		return $this;
	}

	/**
	 * Lorem Ipsum
	 *
	 * @param string $src
	 *   Lorem Ipsum
	 * @return array
	 *   Lorem Ipsum
	 */
	public function scanOLD($src) {
		/**
		 * Lorem Ipsum
		 *
		 * @var array
		 */
		$toks = token_get_all($src);

		/**
		 *
		 */


		/**
		 * @var int $si
		 *   Lorem Ipsum
		 * @var int $di
		 *   Lorem Ipsum
		 */
		for ($si = $di = 0 ; $si < count($toks) ; $si++, $di = $si) {
			if (is_array($toks)) {
				/**
				 * @var int $tok
				 *   Lorem Ipsum
				 * @var string $content
				 *   Lorem Ipsum
				 * @var int $line
				 *   Lorem Ipsum
				 */
				list($tok, $content, $line) = $toks[ $di ];

				if ($tok == T_WHITESPACE || $tok == T_COMMENT || $tok == T_DOC_COMMENT) {
					continue;
				}
			} else {
				/**
				 * Lorem Ipsum
				 *
				 * @var string
				 */
				$tok = $toks[ $di ];
			}
			/*if () {

			}*/
			/**
			 * @var array $func
			 *   Lorem Ipsum
			 */
			foreach ($this->funcs as $func) {
				if ($func['toks'][0] != $toks[ $di ]) {
					continue;
				}
				if ($func['toks'][1]) {

				}
				if ($func['toks'][2]) {

				}
			}
		}
	}

	/**
	 * Lorem Ipsum
	 *
	 * @param string $src
	 *   Lorem Ipsum
	 * @return array
	 *   Lorem Ipsum
	 */
	public function scan($src) {
		/**
		 * Lorem Ipsum
		 *
		 * @var array
		 */
		$toks = $this->tokenize($src);

		/**
		 * Lorem Ipsum
		 *
		 * @param int $i
		 *   Lorem Ipsum
		 * @return array
		 *   Lorem Ipsum
		 */
		$getTok = function (&$i) use ($toks) {
			if ($toks[ $i ][0] != T_COMMENT) {
				return $toks[ $i ];
			}
			do {
				if (!isset($toks[ ++$i ])) {
					return array();
				}
				if ($toks[ $i ][0] != T_COMMENT) {
					return $toks[ $i ];
				}
			} while (true);
		};

		/**
		 * @var int $i
		 *   Lorem Ipsum
		 */
		for ($i = 0 ; $i < count($toks) ; $i++) {
			if ($toks[ $i ][0] != T_STRING && $toks[ $i ][0] != T_OBJECT_OPERATOR && $toks[ $i ][0] != T_DOUBLE_COLON) {
				continue;
			}

			/**
			 * Lorem Ipsum
			 *
			 * @var bool
			 */
			$continue = false;

			/**
			 * @var array $func
			 *   Lorem Ipsum
			 */
			foreach ($this->funcs as $func) {
				/**
				 * Lorem Ipsum
				 *
				 * @var int
				 */
				$j = $i;

				/**
				 * @var array|int $rule
				 *   Lorem Ipsum
				 */
				foreach ($func['rules'] as $rule) {
					/**
					 * Lorem Ipsum
					 *
					 * @var array
					 */
					$tok = $getTok($j);

					$continue = false;
					$j++;
					if (empty($tok)) {
						break 2; // ???
					}
					if (is_array($rule) && $rule[0] == $tok[0] && preg_match("~^$rule[1]$~ADi", $tok[1]) || $rule == $tok[0]) {
						$continue = true;
						continue;
					}		
					// Pokračovať ďalšou funkciou.
					continue 2;
				}
				if ($continue) {
					/**
					 * Lorem Ipsum
					 *
					 * @var int
					 */
					$msgAt = $func['msgAt'];

					break;
				}
			}
			if ($toks[ $i ][0] == T_OBJECT_OPERATOR || $toks[ $i ][0] == T_DOC_COMMENT) {
				$i++;
			}
			if (!$continue) {
				continue;
			}
			$i++;

			/**
			 * Lorem Ipsum
			 *
			 * @var int
			 */
			$lvl = 1;

			/**
			 * Lorem Ipsum
			 *
			 * @var array
			 */
			$rules = array(
				array(T_CONSTANT_ENCAPSED_STRING, array(T_CHARACTER, ')')),
				array(T_CONSTANT_ENCAPSED_STRING, array(T_CHARACTER, ',')),
				array(T_COMMENT, T_CONSTANT_ENCAPSED_STRING, array(T_CHARACTER, ')')),
				array(T_COMMENT, T_CONSTANT_ENCAPSED_STRING, array(T_CHARACTER, ',')),
			);

			/**
			 * Lorem Ipsum
			 *
			 * @var array
			 */
			$params = array();

			/**
			 *
			 */
			for ($j = $i + 1, $param = 1 ; $j < count($toks) ; $j++) {
				if ($toks[ $j ][0] == T_CHARACTER && $toks[ $j ][1] == '(') {
					$lvl++;
				}
				elseif ($toks[ $j ][0] == T_CHARACTER && $toks[ $j ][1] == ')') {
					$lvl--;
				}
				if (!$lvl) {
					break;
				}
				elseif ($toks[ $j ][0] != T_CHARACTER && $toks[ $j ][1] != ',') {
					$params[ $param ][] = $toks[ $j ];
				} else {
					$param++;
				}
			}

			d($params);
		}
	}

	/**
	 * Lorem Ipsum
	 *
	 * @param string $src
	 *   Lorem Ipsum
	 * @return array
	 *   Lorem Ipsum
	 */
	private function tokenize($src) {
		/**
		 * Lorem Ipsum
		 *
		 * @var array
		 */
		$toks = array();

		/**
		 * @var array|string $tok
		 *   Lorem Ipsum
		 */
		foreach (token_get_all($src) as $tok) {
			if (is_string($tok)) {
				$toks[] = array(T_CHARACTER, $tok, 0, array());
				continue;
			}
			if ($tok[0] == T_WHITESPACE || $tok[0] == T_DOC_COMMENT) {
				continue;
			}

			/**
			 * @var int $tok
		 	 *   Lorem Ipsum
		 	 * @var string $code
		 	 *   Lorem Ipsum
		 	 * @var int $line
		 	 *   Lorem Ipsum
			 */
			list($tok, $code, $line) = $tok;

			if ($tok != T_COMMENT) {
				$toks[] = array($tok, $code, $line, array());
				continue;
			}

			/**
			 * Lorem Ipsum
			 *
			 * @var array
			 */
			$matches = array();

			if (preg_match('~^/\\*[*\\s]*@translate(?:[*\\s]*\\s[*\\s]*(?i)(?P<flag>false|no|true|yes))?[*\\s]*\\*/$~AD', $code, $matches)) {
				$toks[] = array($tok, $code, $line, array(
					'key' => 'translate',
					'val' => !isset($matches['flag']) || !in_array($matches['flag'], array('false', 'no')),
				));
				continue;
			}
			if (preg_match('~^/\\*[*\\s]*@messageAt[*\\s]*\\s[*\\s]*(?P<at>[1-9]\\d?|100)[*\\s]*\\*/$~AD', $code, $matches)) {
				$toks[] = array($tok, $code, $line, array(
					'key' => 'messageAt',
					'val' => (int) $matches['at'],
				));
			#	continue;
			}
		}
		return $toks;
	}

	/**
	 *
	 */
	private function getTokOnly() {

	}
}
