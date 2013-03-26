<?php

$config['n'] = 2;

/**
 * @param  int $n
 * @return int
 */
$config['calc'] = function ($n) {
	return $n == 1 ? 0 : 1;
};

return $config;
