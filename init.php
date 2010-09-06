<?php defined('SYSPATH') or die('No direct script access.');

// Static file serving (CSS, JS, images)
Route::set('docs/media', 'guide/media(/<file>)', array('file' => '.+'))
	->defaults(array(
		'controller' => 'userguide',
		'action'     => 'media',
		'file'       => NULL,
	));

// API Browser, if enabled
if (Kohana::config('userguide.api_browser') === TRUE)
{
	Route::set('docs/api', 'guide/api(/<class>)', array('class' => '[a-zA-Z0-9_]+'))
		->defaults(array(
			'controller' => 'userguide',
			'action'     => 'api',
			'class'      => NULL,
		));
}

// User guide pages, in modules
Route::set('docs/guide', 'guide(/<module>(/<page>))', array(
		'page' => '.+',
	))
	->defaults(array(
		'controller' => 'userguide',
		'action'     => 'docs',
		'module'     => '',
	));