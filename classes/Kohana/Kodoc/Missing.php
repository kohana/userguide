<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Set [Kodoc_Missing::create_class] as an autoloading to prevent missing classes
 * from crashing the api browser. Classes that are missing a parent will
 * extend this class, and get a warning in the API browser.
 *
 * @package    Kohana/Userguide
 * @category   Undocumented
 * @author     Kohana Team
 * @copyright  (c) 2008-2014 Kohana Team
 * @license    http://kohanaframework.org/license
 */
abstract class Kohana_Kodoc_Missing {

	/**
	 * Creates classes when they are otherwise not found.
	 *
	 *     Kodoc::create_class('ThisClassDoesNotExist');
	 *
	 * [!!] All classes created will extend [Kodoc_Missing].
	 *
	 * @param   string  $class  Class name
	 * @return  boolean
	 */
	public static function create_class($class)
	{
		if ( ! class_exists($class))
		{
			// Create a new missing class, @TODO use class_alias
			eval("class {$class} extends Kodoc_Missing {}");
		}
		return TRUE;
	}

}
