<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class property documentation generator.
 *
 * @package    Kodoc
 * @author     Kohana Team
 * @copyright  (c) 2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_Kodoc_Property extends Kodoc {

	public $property;

	public $modifiers = 'public';

	public $type;

	public $value;

	public function __construct($class, $property)
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
			if (preg_match('/^(\S*)(?:\s*(.+?))?$/', $tags['var'][0], $matches))
			{
				$this->type = $matches[1];

				if (isset($matches[2]))
				{
					$this->description = $matches[2];
				}
			}
		}

		$this->property = $property;

		if ($property->isStatic())
		{
			$this->value = Kohana::debug($property->getValue($class));
		}
	}

} // End Kodoc_Property
