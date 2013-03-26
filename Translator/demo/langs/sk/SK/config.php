<?php

$config['n'] = 3;

/**
 * @param  int $n
 * @return int
 */
$config['calc'] = function ($n) {
	return $n == 1 ? 0 : ($n >= 2 && $n <= 4 ? 1 : 2);
};

return $config;
