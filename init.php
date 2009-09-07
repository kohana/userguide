<?php defined('SYSPATH') or die('No direct script access.');

Route::set('docs/api', 'api(/<class>)', array('class' => '[a-zA-Z_]+'))
	->defaults(array(
		'controller' => 'userguide',
		'action'     => 'api',
		'class'      => NULL,
	));

Route::set('docs/guide', 'guide(/<lang>)(/<page>)', array(
		'lang' => '[a-z]{2}',
		'page' => '.+',
	))
	->defaults(array(
		'controller' => 'userguide',
		'action'     => 'docs',
		'lang'       => 'en',
		'page'       => 'index',
	));

Route::set('docs/media', 'media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'userguide',
		'action'     => 'media',
		'file'       => NULL,
	));
