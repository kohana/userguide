<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Kodoc_Property extends Kodoc {

	public $property;

	public $modifiers = 'public';

	public $type;

	public function __construct($class, $property)
	{
		$this->property = new ReflectionProperty($class, $property);

		list($description, $tags) = Kodoc::parse($this->property->getDocComment());

		$this->description = $description;

		if ($modifiers = $this->property->getModifiers())
		{
			$this->modifiers = implode(' ', Reflection::getModifierNames($modifiers));
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
	}

} // End Kodoc_Property
