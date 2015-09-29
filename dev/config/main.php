<?php

$regex = 'alexa|dns|index|ip|link|ping|pr|site|speed|whois';

return array(

	'components' => array(
		'cache' => array(
			'class' => 'CFileCache',
			'cachePath' => 'cache',
			'directoryLevel' => 1,
		),
		'db' => array(
			'connectionString' => 'mysql:host=localhost;dbname=cc.la',
			'username' => 'root',
			'password' => ''
		),
		'urlManager' => array(
			'urlFormat' => 'path',
			'rules' => array(
				'<c:'. $regex . '>/<a:\w+>/<q>' => '<c>/<a>',
				'<c:'. $regex . '>/<a:\w+>' => '<c>/<a>',
				'<c:'. $regex . '>/<q>' => '<c>/index',
				'<c:'. $regex . '>' => '<c>/index',
			)
		)
	),
	'defaultController' => 'home'

);