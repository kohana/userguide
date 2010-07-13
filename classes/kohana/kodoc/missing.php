<?php defined('SYSPATH') or die('No direct script access.');
/**
 * [!!] This class, or a class parent, could not be found or loaded. This could
 * be caused by a missing module or other dependancy. The documentation for
 * class will not be complete!
 *
 * @package    Kohana/Userguide
 * @category   Undocumented
 * @author     Kohana Team
 * @since      3.0.7
 */
abstract class Kohana_Kodoc_Missing {

	/**
	 * Creates classes when they are otherwise not found.
	 *
	 *     Kodoc::create_class('ThisClassDoesNotExist');
	 *
	 * [!!] All classes created will extend [Kodoc_Missing].
	 *
	 * @param   string   class name
	 * @return  boolean
	 * @since   3.0.7
	 */
	public static function create_class($class)
	{
		if ( ! class_exists($class))
		{
			// Create a new missing class
			eval("class {$class} extends Kodoc_Missing {}");
		}

		return TRUE;
	}

} // End Kohana_Kodoc_Missing
