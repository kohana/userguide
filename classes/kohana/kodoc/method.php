<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Kodoc_Method extends Kodoc {

	public function __construct($class, $method)
	{
		$method = new ReflectionMethod($class, $method);

		foreach ($method->getParameters() as $i => $param)
		{
			$this->params[$i] = array('name' => $param->getName(), 'optional' => $param->isOptional());
		}

		$this->source = Kodoc::source($method->getFileName(), $method->getStartLine(), $method->getEndLine());

		$this->_parse($method->getDocComment());
	}

} // End Kodoc_Method