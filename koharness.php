<?php
// Configuration for koharness - builds a standalone skeleton Kohana app for running unit tests
return array(
	'kohana_version' => '3.4',
	'modules' => array(
		'userguide' => __DIR__,
		'unittest'  => __DIR__ . '/vendor/kohana/unittest'
	),
);
