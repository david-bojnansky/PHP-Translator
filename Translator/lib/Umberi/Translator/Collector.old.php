<?php

/**
 * Lorem Ipsum
 */

namespace Umberi\Translator;

/**
 * Lorem Ipsum
 */
final class Collector {
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
	const TYPE_OBJECT = 8;
 
	/**
	 * Lorem Ipsum
	 */
	const TYPE_ALL = 15;

	/**
	 * Lorem Ipsum
	 *
	 * @var array
	 */
	private $funcs = array();

	/**
	 * Lorem Ipsum
	 *
	 * @var array
	 */
	private $msgs = array();

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
	 * @return Umberi\Translator\Collector
	 */
	public function addMacro($, $name, $type = self::TYPE_ALL, $messageAt = 1, $samples = null) { // 
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
		$this->funcs[] = array(
			'type'  => $type,
			'name'  => $name,
			'msgAt' => (array) $messageAt,
		);
		return $this;
	}

	/**
	 * Lorem Ipsum
	 *
	 * @param string $file
	 *   Lorem Ipsum
	 * @return array
	 *   Lorem Ipsum
	 */
	public function scan($file) {
		if (empty($this->funcs)) {
			// TODO: ERROR
		}

		/**
		 * Lorem Ipsum
		 *
		 * @var array
		 */
		$toks = $this->tokenize(file_get_contents($file));

		/**
		 * Lorem Ipsum
		 *
		 * @var array
		 */
		$illegalStrToks = array(
			'false',
			'null',
			'parent',
			'self',
			'true',
		);

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
		 * Lorem Ipsum
		 *
		 * @param int $i
		 *   Lorem Ipsum
		 * @param bool $skipCmmnt
		 *   Lorem Ipsum
		 * @param int $shift
		 *   Lorem Ipsum
		 * @return array
		 *   Lorem Ipsum
		 */
		$getPrevTok = function ($i, $skipCmmnt = true, $shift = 0) use ($toks) {
			do {
				if (!isset($toks[ --$i ])) {
					return array();
				}
				if ((!$skipCmmnt || $toks[ $i ][0] != T_COMMENT) && $shift-- <= 0) {
					return $toks[ $i ];
				}
			} while (true);
		};

		/**
		 * Lorem Ipsum
		 *
		 * @param int $i
		 *   Lorem Ipsum
		 * @param bool $skipCmmnt
		 *   Lorem Ipsum
		 * @param int $shift
		 *   Lorem Ipsum
		 * @return array
		 *   Lorem Ipsum
		 */
		$getNextTok = function ($i, $skipCmmnt = true, $shift = 0) use ($toks) {
			do {
				if (!isset($toks[ ++$i ])) {
					return array();
				}
				if ((!$skipCmmnt || $toks[ $i ][0] != T_COMMENT) && $shift-- <= 0) {
					return $toks[ $i ];
				}
			} while (true);
		};

		/**
		 * @var int $i
		 *   Lorem Ipsum
		 */
		for ($i = 0 ; isset($toks[ $i ]) ; $i++) {
			if ($toks[ $i ][0] != T_STRING && $toks[ $i ][0] != T_CONSTANT_ENCAPSED_STRING) {
				continue;
			}
			if ($toks[ $i ][0] == T_STRING && in_array($toks[ $i ][1], $illegalStrToks)) {
				continue;
			}

			/**
			 * Lorem Ipsum 
			 *
			 * @var array
			 */
			$nextTok = $getNextTok($i);

			if (empty($nextTok)) {
				break;
			}
			if ($toks[ $i ][0] == T_STRING ? $nextTok[1] != '(' : $nextTok[1] != ')') {
				continue;
			}
			$i++;
			if ($toks[ $i ][0] == T_STRING) {
				$nextTok = $getNextTok($i);
				if (empty($nextTok)) {
					break;
				}
				if ($nextTok[1] == ')') {
					$i++;
					continue;
				}
			}

			/**
			 * Lorem Ipsum 
			 *
			 * @var array
			 */
			$prevTok = $getPrevTok($i);

			if ($toks[ $i ][0] == T_CONSTANT_ENCAPSED_STRING) {
				if (empty($prevTok) || $prevTok[1] != '(') {
					continue;
				}
				$prevTok = $getPrevTok($i, true, 1);
				if (!empty($prevTok)) {
					if (in_array($prevTok[0], array(T_STRING, T_VARIABLE))) {
						continue;
					}
					if (in_array($prevTok[1], array(']', '}'))) {
						continue;
					}
				}
				$this->msgs[] = array(
					'type' => substr($toks[ $i ][1], 0,  1),
					'msg'  => substr($toks[ $i ][1], 1, -1),
					'file' => $file,
					'line' => $toks[ $i ][2],
				);
				continue;
			}

			/**
			 * Lorem Ipsum
			 *
			 * @var int
			 */
			$type = self::TYPE_FUNCTION; // Pridať podporu aj pre TYPE_OBJECT predsa len!!!

			if (!empty($prevTok)) {
				if ($prevTok[0] == T_NEW) {
					continue;
				} elseif ($prevTok[0] == T_OBJECT_OPERATOR) {
					$type = self::TYPE_METHOD;
				} elseif ($prevTok[0] == T_DOUBLE_COLON) {
					$type = self::TYPE_STATIC_METHOD;
				} elseif ($prevTok[0] == T_NS_SEPARATOR) {
					/**
					 * Lorem Ipsum
					 *
					 * @var int
					 */
					$j = 1;

					do {
						/**
						 * Lorem Ipsum
						 *
						 * @var array
						 */
						$pTok = $getPrevTok($i - 1, true, $j++);

						if (empty($pTok)) {
							break;
						}
						if ($pTok[0] == T_NEW) {
							continue 2;
						}
						if ($pTok[0] != T_STRING && $pTok[0] != T_NS_SEPARATOR) {
							break;
						}
					} while (true);
				}
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
				if ($type & $func['type'] && preg_match("~^{$func['name']}$~ADi", $toks[ $i ][1])) {
					/**
					 * Lorem Ipsum
					 *
					 * @var int
					 */
					$msgAt = $func['msgAt'];

					$continue = true;
					break;
				}
			}
			if (!$continue) {
				continue;
			}

			/**
			 * Lorem Ipsum
			 *
			 * @var int
			 */
			$lvl = 0;

			/**
			 * Lorem Ipsum
			 *
			 * @var array
			 */
			$params = array();

			/**
			 * @var int $j
			 *   Lorem Ipsum
			 * @var int $at
			 *   Lorem Ipsum
			 */
			for ($j = $i + 1, $at = 1 ; isset($toks[ $j ]) ; $j++) { // zvyšovať aj $i ???
				if ($toks[ $j ][1] == ',') {
					$at++;
					continue;
				}
				if ($toks[ $j ][1] == ')') {
					if ($lvl--) {
						break;
					}
				} elseif ($toks[ $j ][1] == '(') {
					$lvl++;
				}
				$params[ $at ][] = $toks[ $j ];
			}

			/**
			 * Lorem Ipsum
			 *
			 * @var array
			 */
			$rules = array(
				array(1, T_CONSTANT_ENCAPSED_STRING),
				array(3, T_VARIABLE, '=', T_CONSTANT_ENCAPSED_STRING),
			);

			/**
			 * @var int $at
			 *   Lorem Ipsum
			 */
			foreach ($msgAt as $at) {
				if (!isset($params[ $at ])) {
					continue;
				}
				// 1. overiť pravidlá
				// 2. ak sa pravidlá nezhodujú, pokračovať ďalšou iteráciou, inak to breaknúť...
				// 3. iba posledné `$at` môže mať komentár /* @messageAt X */ a nesmie obsahovať číslo, kt. sa nachádza v `$msgAt`...
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
