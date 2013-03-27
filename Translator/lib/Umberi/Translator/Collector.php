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
	const TYPE_ALL = 7;

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
		$this->funcs[] = array(
			'type'  => $type,
			'name'  => $name,
			'msgAt' => $messageAt,
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

			/**
			 * Lorem Ipsum 
			 *
			 * @var array
			 */
			$prevTok = $getPrevTok($i);

			if ($toks[ $i ][0] == T_CONSTANT_ENCAPSED_STRING) {
				$i++;
				if (empty($prevTok) || $prevTok[1] != '(') {
					continue;
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
				$type = self::TYPE_FUNCTION;

				if (!empty($prevTok)) {
					if ($prevTok[0] == T_OBJECT_OPERATOR) {
						$type = self::TYPE_METHOD;
					} elseif ($prevTok[0] == T_DOUBLE_COLON) {
						$type = self::TYPE_STATIC_METHOD;
					}
				}
				if ($type & $func['type'] && preg_match("~^{$func['name']}$~ADi", $toks[ $i ][1])) {
					$continue = true;

					/**
					 * Lorem Ipsum
					 *
					 * @var int
					 */
					$msgAt = $func['msgAt'];

					break;
				}
			}
			$i++;
			if (!$continue) {
				continue;
			}
			$nextTok = $getNextTok($i);
			if (empty($nextTok)) {
				break;
			}
			if ($nextTok[1] == ')') {
				$i++;
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
			$rules = array(
				array(T_CONSTANT_ENCAPSED_STRING, ')'),
				array(T_CONSTANT_ENCAPSED_STRING, ','),
				array(T_COMMENT, T_CONSTANT_ENCAPSED_STRING, ')'),
				array(T_COMMENT, T_CONSTANT_ENCAPSED_STRING, ','),
			);

			/**
			 * Lorem Ipsum
			 *
			 * @var array
			 */
			$params = array();

			/**
			 * @var int $j
			 *   Lorem Ipsum
			 * @var int $p
			 *   Lorem Ipsum
			 */
			for ($j = $i + 1, $p = 1 ; isset($toks[ $j ]) ; $j++) {
				if ($toks[ $j ][1] == ',' && $p++) {
					continue;
				}
				if ($toks[ $j ][1] == ')' && $lvl--) {
					break;
				}
				if ($toks[ $j ][1] == '(') {
					$lvl++;
				}
			}

			/**
			 *
			 */
			for ($j = $i, $param = 1 ; $j < count($toks) ; $j++) {
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
