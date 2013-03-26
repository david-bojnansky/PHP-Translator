<?php

/**
 * Lorem Ipsum
 */

namespace Umberi\Translator\Tokenizer; use Iterator;

/**
 * Lorem Ipsum
 */
final class TokenIterator implements Iterator {
	/**
	 * Lorem Ipsum
	 *
	 * @var int
	 */
	private $i = 0;

	/**
	 * Lorem Ipsum
	 *
	 * @var array
	 */
	public $toks;

	/**
	 * Lorem Ipsum
	 *
	 * @var void|bool
	 */
	public $translate;

	/**
	 * Lorem Ipsum
	 *
	 * @var int
	 */
	public $messageAt = 0;

	/**
	 * Lorem Ipsum
	 *
	 * @param string $src
	 *   Lorem Ipsum
	 * @return void
	 */
	public function __construct($src) {
		$this->toks = token_get_all($src);
	}

	/**
	 * Lorem Ipsum
	 *
	 * @return int
	 */
	public function key() {
		return $this->i;
	}

	/**
	 * Lorem Ipsum
	 *
	 * @return array|string
	 */
	public function current() {
		return $this->toks[ $this->i ];
	}

	/**
	 * Lorem Ipsum
	 *
	 * @return void
	 */
	public function next() {
		$this->translate = null;
		$this->messageAt = 0;
		do {
			if (++$this->i && (!$this->valid() || is_string($this->toks[ $this->i ]))) {
				return;
			}

			/**
			 * Lorem Ipsum
			 *
			 * @var int
			 */
			$tok = $this->toks[ $this->i ][0];

			if ($tok == T_WHITESPACE || $tok == T_DOC_COMMENT) {
				continue;
			}
			if ($tok != T_COMMENT) {
				return;
			}

			/**
			 * Lorem Ipsum
			 *
			 * @var string
			 */
			$con = $this->toks[ $this->i ][1];

			/**
			 * Lorem Ipsum
			 *
			 * @var array
			 */
			$m = array();

			if (preg_match('~^/\\*[*\\s]*@translate(?:[*\\s]*\\s[*\\s]*(?i)(?P<flag>false|no|true|yes))?[*\\s]*\\*/$~AD', $con, $m)) {
				$this->translate = !isset($m['flag']) || !in_array($m['flag'], array('false', 'no'));
				return;
			}
			if (preg_match('~^/\\*[*\\s]*@messageAt[*\\s]*\\s[*\\s]*(?P<at>[1-9]\\d?|100)[*\\s]*\\*/$~AD', $con, $m)) {
				$this->messageAt = (int) $m['at'];
				return;
			}
		} while (true);
	}

	/**
	 * Lorem Ipsum
	 *
	 * @return bool
	 */
	public function valid() {
		return array_key_exists($this->i, $this->toks);
	}

	/**
	 * Lorem Ipsum
	 *
	 * @return void
	 */
	public function rewind() {
		$this->i = 0;
	}
}
