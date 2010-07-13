<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	// Default userguide page.
	'default_page' => 'about.kohana',

	// Default the userguide language.
	'lang'         => 'en-us',
	
	// Enable the API browser.  TRUE or FALSE
	'api_browser'  => TRUE,
	
	// Enable these packages in the API browser.  TRUE for all packages, or a string of comma seperated packages, using 'None' for a class with no @package
	// Example: 'api_packages' => 'Kohana,Kohana/Database,Kohana/ORM,None',
	'api_packages' => TRUE,
);
