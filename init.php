<?php defined('SYSPATH') or die('No direct script access.');

Route::set('docs/api', 'guide/api(/<class>)', array('class' => '[a-zA-Z0-9_]+'))
	->defaults(array(
		'controller' => 'userguide',
		'action'     => 'api',
		'class'      => NULL,
	));

Route::set('docs/guide', 'guide(/<page>)', array(
		'page' => '.+',
	))
	->defaults(array(
		'controller' => 'userguide',
		'action'     => 'docs',
		'page'       => 'start',
	));

Route::set('docs/media', 'media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'userguide',
		'action'     => 'media',
		'file'       => NULL,
	));
