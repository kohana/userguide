<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class method parameter documentation generator.
 *
 * @package    Userguide
 * @author     Kohana Team
 * @copyright  (c) 2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_Kodoc_Method_Param extends Kodoc {

	/**
	 * @var  object  ReflectionParameter for this property
	 */
	public $param;

	/**
	 * @var  string  name of this var
	 */
	public $name;

	/**
	 * @var  string  variable type, retrieved from the comment
	 */
	public $type;

	/**
	 * @var  string  default value of this param
	 */
	public $default;

	/**
	 * @var  string  description of this parameter
	 */
	public $description;

	public $byref = false;

	/**
	 * @var  boolean  is the parameter optional?
	 */
	public $optional = FALSE;

	public function __construct($method, $param)
	{
		$this->param = new ReflectionParameter($method, $param);

		$this->name = $this->param->name;

		if ($this->param->isDefaultValueAvailable())
		{
			$this->default = Kohana::dump($this->param->getDefaultValue());
		}

		if ($this->param->isPassedByReference())
		{
			$this->byref = true;
		}

		if ($this->param->isOptional())
		{
			$this->optional = TRUE;
		}
	}

	public function short()
	{
		$out = '';

		if (isset($this->type))
		{
			$out .= '<small>'.$this->type.'</small> ';
		}

		if ($this->byref)
		{
			$out .= '<small><abbr title="passed by reference">&</abbr></small> ';
		}

		if (isset($this->description))
		{
			$out .= '<span class="param" title="'.ucfirst($this->description).'">$'.$this->name.'</span> ';
		}
		else
		{
			$out .= '$'.$this->name.' ';
		}

		if ($this->default)
		{
			$out .= '<small>= '.$this->default.'</small> ';
		}

		return $out;
	}

} // End Kodoc_Method_Param
