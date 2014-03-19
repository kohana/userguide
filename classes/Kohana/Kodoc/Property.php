<?php defined('SYSPATH') OR die('No direct script access.');
/**
 * Class property documentation generator.
 *
 * @package    Kohana/Userguide
 * @category   Base
 * @author     Kohana Team
 * @copyright  (c) 2008-2014 Kohana Team
 * @license    http://kohanaframework.org/license
 */
class Kohana_Kodoc_Property extends Kodoc {

	/**
	 * @var  object  ReflectionProperty
	 */
	public $property;

	/**
	 * @var  string   Modifiers: public, private, static, etc
	 */
	public $modifiers = 'public';

	/**
	 * @var  string  Variable type, retrieved from the comment
	 */
	public $type;

	/**
	 * @var  string  Value of the property
	 */
	public $value;

	/**
	 * @var  string  Default value of the property
	 */
	public $default;

	/**
	 * 
	 */
	public function __construct($class, $property, $default = NULL)
	{
		$property = new ReflectionProperty($class, $property);

		list($description, $tags) = Kodoc::parse($property->getDocComment());

		$this->description = $description;

		if ($modifiers = $property->getModifiers())
		{
			$this->modifiers = '<small>'.implode(' ', Reflection::getModifierNames($modifiers)).'</small> ';
		}

		if (isset($tags['var']))
		{
			if (preg_match('/^(\S*)(?:\s*(.+?))?$/s', $tags['var'][0], $matches))
			{
				$this->type = $matches[1];
				if (isset($matches[2]))
				{
					$this->description = Kodoc_Markdown::markdown($matches[2]);
				}
			}
		}

		$this->property = $property;

		// Show the value of static properties, but only if they are public 
		// or we are php 5.3 or higher and can force them to be accessible
		$valid_version = version_compare(PHP_VERSION, '5.3', '>=');
		if ($property->isStatic() AND ($property->isPublic() OR $valid_version))
		{
			if ($valid_version)
			{
				// Force the property to be accessible
				$property->setAccessible(TRUE);
			}
			// Don't debug the entire object, just say what kind of object it is
			if (is_object($property->getValue($class)))
			{
				$this->value = '<pre>object '.get_class($property->getValue($class)).'()</pre>';
			}
			else
			{
				$this->value = Debug::vars($property->getValue($class));
			}
		}
		// Store the defult property
		$this->default = Debug::vars($default);;
	}

}
