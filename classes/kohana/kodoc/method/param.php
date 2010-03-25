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
	 * @var  ReflectionParameter  The ReflectionParameter for this property
	 */
	public $param;

	/**
	 * @var  string  the name of this var
	 */
	public $name;

	/**
	 * @var  string  The variable type, retreived from the comment
	 */
	public $type;

	/**
	 * @var  string  The default value of this param
	 */
	public $default;
	
	/**
	 * @var  string  Description of this parameter
	 */
	public $description;
	
	public $byref = false;
	
	public $optional = false;

	public function __construct($method,$param)
	{
		$this->param = new ReflectionParameter($method,$param);
		$this->name = $this->param->name;
		
		if ($this->param->isDefaultValueAvailable())
		{
			$default = $this->param->getDefaultValue();
			
			if ($default === NULL)
			{
				$this->default .= 'NULL ';
			}
			elseif ($default === FALSE)
			{
				$this->default .= 'FALSE ';
			}
			elseif (is_string($default))
			{
				$this->default .= "'".$default."'";
			}
			else
			{
				$this->default .= print_r($default,true);
			}
		}
		
		if ($this->param->isPassedByReference())
		{
			$this->byref = true;
		}
		
		if ($this->param->isOptional())
		{
			$this->optional = true;
		}
	}
	
	public function short()
	{
		$out = '';
		if ($this->byref)
		{
			$out .= '<small>byref</small> ';
		}
		
		if (isset($this->type))
		{
			$out .= '<small>'.$this->type.'</small> ';
		}
		
		if (isset($this->description))
		{
			$out .= '<span class="param" title="'.ucfirst($this->description).'">$'.$this->name.'</span> ';
		}
		else
		{
			$out .= '$'.$this->name.' ';
		}
		
		if ($this->param->isDefaultValueAvailable())
		{
			$out .= '<small>= '.$this->default.'</small> ';
		}
		
		return $out;
	}

} // End Kodoc_Method_Param
