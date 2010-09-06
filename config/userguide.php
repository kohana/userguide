<?php defined('SYSPATH') OR die('No direct access allowed.');

return array
(
	// Default the userguide language.
	'lang'         => 'en-us',
	
	// Modules array for User Guide section.  Each module adds itself to this array.  This does not affect whether they show up in the API
	'modules' => array(),
	
	// Enable the API browser.  TRUE or FALSE
	'api_browser'  => TRUE,
	
	// Enable these packages in the API browser.  TRUE for all packages, or a string of comma seperated packages, using 'None' for a class with no @package
	// Example: 'api_packages' => 'Kohana,Kohana/Database,Kohana/ORM,None',
	'api_packages' => TRUE,
);
