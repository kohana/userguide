<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Kodoc_Method extends Kodoc {

	public $method;

	public $params;

	public $return = array();

	public function __construct($class, $method)
	{
		$this->method = new ReflectionMethod($class, $method);

		$this->class = $parent = $this->method->getDeclaringClass();

		if ($modifiers = $this->method->getModifiers())
		{
			$this->modifiers = '<small>'.implode(' ', Reflection::getModifierNames($modifiers)).'</small> ';
		}

		do
		{
			if ($parent->hasMethod($method) AND $comment = $parent->getMethod($method)->getDocComment())
			{
				// Found a description for this method
				break;
			}
		}
		while ($parent = $parent->getParentClass());

		list($this->description, $tags) = Kodoc::parse($comment);

		if (isset($tags['param']))
		{
			$params = array();

			foreach ($this->method->getParameters() as $i => $param)
			{
				if (isset($tags['param'][$i]))
				{
					if ($param->isDefaultValueAvailable())
					{
						$name = $param->name.' = '.var_export($param->getDefaultValue(), TRUE);
					}
					else
					{
						$name = $param->name;
					}

					preg_match('/^(\S+)(?:\s*(.+))?$/', $tags['param'][$i], $matches);

					$verbose = '<small>'.$matches[1].'</small> ';

					if (isset($matches[2]))
					{
						$verbose .= '<span class="param" title="'.$matches[2].'">$'.$name.'</span>';
					}
					else
					{
						$verbose .= '<span class="param">$'.$name.'</span>';
					}

					$params[] = $verbose;
				}
			}

			$this->params = implode(', ', $params);

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