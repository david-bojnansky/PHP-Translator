<?php

/**
 * Lorem Ipsum
 */

namespace Umberi\Translator\Collector;

/**
 * Lorem Ipsum
 */
final class PHP {
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
	 * @param string $filename
	 *   Lorem Ipsum
	 * @return array
	 *   Lorem Ipsum
	 */
	private function tokenize($filename) {
		/**
		 * Lorem Ipsum
		 *
		 * @var array
		 */
		$tokens = array();

		foreach (token_get_all(file_get_contents($filename)) as $token) {
			if (is_string($token)) {
				$tokens[] = array(T_CHARACTER, $token, $filename, 0, null);
				continue;
			}
			if ($token[0] == T_WHITESPACE) {
				continue;
			}
			if ($token[0] != T_COMMENT && $token[0] != T_DOC_COMMENT) {
				$tokens[] = array($token[0], $token[1], $filename, $token[2], null);
				continue;
			}
			if (preg_match('~^/\\*[*\\s]*@catch[*\\s]*\\*/$~AD', $token[1])) {
				$tokens[] = array(T_COMMENT, $token[1], $filename, $token[2], array('catch' => true));
				continue;
			}
		}
		return $tokens;
	}

	/**
	 * Lorem Ipsum
	 *
	 * @param string $filename
	 *   Lorem Ipsum
	 * @return Umberi\Translator\Collector\PHP
	 */
	private function scan($filename) {
		/**
		 * Lorem Ipsum
		 *
		 * @var array
		 */
		$tokens = $this->tokenize($filename);

		/**
		 * Lorem Ipsum
		 *
		 * @param int $i
		 *   Lorem Ipsum
		 * @param bool $skipComment
		 *   Lorem Ipsum
		 * @param int $shift
		 *   Lorem Ipsum
		 * @uses array $tokens
		 *   Lorem Ipsum
		 * @return array
		 *   Lorem Ipsum
		 */
		$getPrevToken = function ($i, $skipComment = true, $shift = 0) use ($tokens) {
			do {
				if (!isset($tokens[ --$i ])) {
					return array();
				}
				if ((!$skipComment || $tokens[ $i ][0] != T_COMMENT) && $shift-- <= 0) {
					return $tokens[ $i ];
				}
			} while (true);
		};

		/**
		 * Lorem Ipsum
		 *
		 * @param int $i
		 *   Lorem Ipsum
		 * @param bool $skipComment
		 *   Lorem Ipsum
		 * @param int $shift
		 *   Lorem Ipsum
		 * @uses array $tokens
		 *   Lorem Ipsum
		 * @return array
		 *   Lorem Ipsum
		 */
		$getNextToken = function ($i, $skipComment = true, $shift = 0) use ($tokens) {
			do {
				if (!isset($tokens[ ++$i ])) {
					return array();
				}
				if ((!$skipComment || $tokens[ $i ][0] != T_COMMENT) && $shift-- <= 0) {
					return $tokens[ $i ];
				}
			} while (true);
		};

		/**
		 * @var int $i
		 *   Lorem Ipsum
		 * @var array $currToken
		 *   Lorem Ipsum
		 */
		foreach ($tokens as $i => $currToken) {
			if ($currToken[0] != T_CONSTANT_ENCAPSED_STRING) {
				continue;
			}

			/**
			 * Lorem Ipsum
			 *
			 * @var array
			 */
			$prevToken = $getPrevToken($i, false);

			if (empty($prevToken)) {
				continue;
			}
			switch ($prevToken[0]) {
				case T_CHARACTER:
					if ($prevToken[1] != '(') {
						continue 2;
					}
					$prevToken = $getPrevToken($i, true, 1);
					if (!empty($prevToken)) {
						if (in_array($prevToken[0], array(
							T_CASE,         // NEMUSÍ BYŤ - Záleží od zvyku programátora, či používa zátvorky aj tam, kde nie sú povinné.
							T_ECHO,         // NEMUSÍ BYŤ - Záleží od zvyku programátora, či používa zátvorky aj tam, kde nie sú povinné.
							T_ELSEIF,       //   MUSÍ BYŤ
							T_EVAL,         //   MUSÍ BYŤ
							T_EXIT,         //   MUSÍ BYŤ
							T_IF,           //   MUSÍ BYŤ
							T_PRINT,        // NEMUSÍ BYŤ - Záleží od zvyku programátora, či používa zátvorky aj tam, kde nie sú povinné.
							T_INCLUDE,      // NEMUSÍ BYŤ - Záleží od zvyku programátora, či používa zátvorky aj tam, kde nie sú povinné. Avšak, načo by niekto chcel prekladať názov súboru? Skôr MUSÍ BYŤ.
							T_INCLUDE_ONCE, // NEMUSÍ BYŤ - Záleží od zvyku programátora, či používa zátvorky aj tam, kde nie sú povinné. Avšak, načo by niekto chcel prekladať názov súboru? Skôr MUSÍ BYŤ.
							T_REQUIRE,      // NEMUSÍ BYŤ - Záleží od zvyku programátora, či používa zátvorky aj tam, kde nie sú povinné. Avšak, načo by niekto chcel prekladať názov súboru? Skôr MUSÍ BYŤ.
							T_REQUIRE_ONCE, // NEMUSÍ BYŤ - Záleží od zvyku programátora, či používa zátvorky aj tam, kde nie sú povinné. Avšak, načo by niekto chcel prekladať názov súboru? Skôr MUSÍ BYŤ.
							T_RETURN,       // NEMUSÍ BYŤ - Záleží od zvyku programátora, či používa zátvorky aj tam, kde nie sú povinné.
							T_STRING,       //   MUSÍ BYŤ
							T_SWITCH,       //   MUSÍ BYŤ
							T_VARIABLE,     //   MUSÍ BYŤ
							T_WHILE,        //   MUSÍ BYŤ
						))) {
							continue 2;
						}
						if (in_array($prevToken[1], array(
							']', //   MUSÍ BYŤ
							'}', //   MUSÍ BYŤ
						))) {
							if ($prevToken[1] != '}') {
								continue 2;
							}
							$prevToken = $getPrevToken($i, true, 2);
							if (!empty($prevToken) && !in_array($prevToken[1], array(
								';', //   MUSÍ BYŤ
								'}', //   MUSÍ BYŤ
							))) {
								continue 2;
							}
						}
					}

					/**
					 * Lorem Ipsum
					 *
					 * @var array
					 */
					$nextToken = $getNextToken($i);

					if (empty($nextToken)) {
						break 2;
					}
					if ($nextToken[1] != ')') {
						continue 2;
					}
					break;

				case T_COMMENT:
					if (!array_key_exists('catch', $prevToken[4]) || !$prevToken[4]['catch']) {
						continue 2;
					}
					break;

				default:
					continue 2;
			}
			if (strlen($currToken[1]) <= 2) {
				continue;
			}
			$this->msgs[] = array(
				'type' => substr($currToken[1], 0,  1),
				'msg'  => substr($currToken[1], 1, -1),
				'file' => $currToken[2],
				'line' => $currToken[3],
			);
		}
		return $this;
	}

	/**
	 * Lorem Ipsum
	 *
	 * @return Umberi\Translator\Collector\PHP
	 */
	public function trigger($f) {
		$this->scan($f);
		return $this->msgs;
		// return $this;
	}
}
