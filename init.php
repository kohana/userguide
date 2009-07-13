<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Add the Kodoc user guide, live api, and media assets routes.
 */

Route::set('kodoc_api', 'kodoc/api(/<class>)', array(
		'class' => '[a-zA-Z_]',
	))
	->defaults(array(
		'controller' => 'kodoc',
		'action'     => 'api',
	));

Route::set('kodoc_guide', 'kodoc/guide((/<language>)/<page>)', array(
		'action'   => '(?:api|guide)',
		'language' => '[a-z-]{2,4}',
		'page'     => '.+',
	))
	->defaults(array(
		'controller' => 'kodoc',
		'action'     => 'guide',
	));

Route::set('kodoc_media', 'kodoc/media/<file>', array(
		'file' => '.+',
	))
	->defaults(array(
		'controller' => 'kodoc',
		'action'     => 'media',
	));
