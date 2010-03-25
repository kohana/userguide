<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class documentation generator.
 *
 * @package    Userguide
 * @author     Kohana Team
 * @copyright  (c) 2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_Kodoc_Class extends Kodoc {

	/**
	 * @var  ReflectionClass The ReflectionClass for this class
	 */
	public $class;

	/**
	 * @var  string  modifiers like abstract, final
	 */
	public $modifiers;
	
	/**
	 * @var  string  description of the class from the comment
	 */
	public $description;

	/**
	 * @var  array  array of tags, retreived from the comment
	 */
	public $tags = array();

	/**
	 * @var  array  array of this classes constants
	 */
	public $constants = array();

	public function __construct($class)
	{
		$this->class = $parent = new ReflectionClass($class);

		if ($modifiers = $this->class->getModifiers())
		{
			$this->modifiers = '<small>'.implode(' ', Reflection::getModifierNames($modifiers)).'</small> ';
		}

		if ($constants = $this->class->getConstants())
		{
			foreach ($constants as $name => $value)
			{
				$this->constants[$name] = Kohana::debug($value);
			}
		}

		do
		{
			if ($comment = $parent->getDocComment())
			{
				// Found a description for this class
				break;
			}
		}
		while ($parent = $parent->getParentClass());

		list($this->description, $this->tags) = Kodoc::parse($comment);
	}

	public function properties()
	{
		$props = $this->class->getProperties();

		sort($props);

		foreach ($props as $key => $property)
		{
			if ($property->isPublic())
			{
				$props[$key] = new Kodoc_Property($this->class->name, $property->name);
			}
			else
			{
				unset($props[$key]);
			}
		}

		return $props;
	}

	public function methods()
	{
		$methods = $this->class->getMethods();

		usort($methods,array($this,'_method_sort'));

		foreach ($methods as $key => $method)
		{
			$methods[$key] = new Kodoc_Method($this->class->name, $method->name);
		}

		return $methods;
	}
	
	protected function _method_sort($a,$b)
	{
		/*
		echo kohana::debug('a is '.$a->class.'::'.$a->name,'b is '.$b->class.'::'.$b->name,
						   'are the classes the same?',$a->class == $b->class,'if they are, the result is:',strcmp($a->name,$b->name),
						   'is a this class?',$a->name == $this->class->name,-1,
						   'is b this class?',$b->name == $this->class->name,1,
						   'otherwise, the result is:',strcmp($a->class,$b->class)
						   );
		*/
		
		
		// If both methods are defined in the same class, just compare the method names
		if ($a->class == $b->class)
			return strcmp($a->name,$b->name);

		// If one of them was declared by this class, it needs to be on top
		if ($a->name == $this->class->name)
			return -1;
		if ($b->name == $this->class->name)
			return 1;

		// Otherwise, get the parents of each methods declaring class, then compare which function has more "ancestors"
		$adepth = 0;
		$bdepth = 0;
		
		$parent = $a->getDeclaringClass();
		do
		{
			$adepth++;
		}
		while ($parent = $parent->getParentClass());
		
		$parent = $b->getDeclaringClass();
		do
		{
			$bdepth++;
		}
		while ($parent = $parent->getParentClass());
		
		return $bdepth - $adepth;
	}
}