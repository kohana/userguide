<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Kodoc_Method extends Kodoc {

	public $method;

	public $return = array();

	public function __construct($class, $method)
	{
		$this->method = $method = new ReflectionMethod($class, $method);

		$this->class = $method->getDeclaringClass();

		list($description, $tags) = Kodoc::parse($method->getDocComment());

		$this->description = $description;

		if (isset($tags['param']))
		{
			$params = $tags['param'];

			unset($tags['param']);
		}

		if (isset($tags['return']))
		{
			foreach ($tags['return'] as $return)
			{
				if (preg_match('/^(\S*)(?:\s*(.+?))?$/', $return, $matches))
				{
					$this->return[] = array($matches[1], isset($matches[2]) ? $matches[2] : '');
				}
			}

			unset($tags['return']);
		}

		$this->tags = $tags;
	}

} // End Kodoc_Method