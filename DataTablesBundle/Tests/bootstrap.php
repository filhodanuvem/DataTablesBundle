<?php

	$autoload = __DIR__.'/../../../../vendor/autoload.php';
	if (!file_exists($autoload)) {
		$autoload = __DIR__.'/../../vendor/autoload.php';
		if (!file_exists($autoload)) {
			die('');
		}
	}

 	

	include $autoload;


