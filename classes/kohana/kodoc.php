<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class documentation generator.
 *
 * @package    Kodoc
 * @author     Kohana Team
 * @copyright  (c) 2008-2009 Kohana Team
 * @license    http://kohanaphp.com/license
 */
class Kohana_Kodoc {

	public static function factory($class)
	{
		return new Kodoc($class);
	}

	public static function menu()
	{
		$classes = Kodoc::classes();

		foreach ($classes as $class)
		{
			if (isset($classes['kohana_'.$class]))
			{
				// Remove extended classes
				unset($classes['kohana_'.$class]);
			}
		}

		ksort($classes);

		$menu = array();

		$route = Route::get('docs/api');

		foreach ($classes as $class)
		{
			$class = Kodoc::factory($class);

			$link = HTML::anchor($route->uri(array('class' => $class->class->name)), $class->class->name);

			if (isset($class->tags['package']))
			{
				foreach ($class->tags['package'] as $package)
				{
					$menu[$package][] = $link;
				}
			}
			else
			{
				$menu['Kohana'][] = $link;
			}
		}

		// Sort the packages
		ksort($menu);

		$output = array('<ol>');

		foreach ($menu as $package => $list)
		{
			// Sort the class list
			sort($list);

			$package = HTML::anchor($route->uri(array('class' => $package)), $package);

			$output[] =
				"<li>$package\n\t<ul><li>".
				implode("</li><li>", $list).
				"</li></ul>\n</li>";
		}

		$output[] = '</ol>';

		return implode("\n", $output);
	}

	public static function classes(array $list = NULL)
	{
		if ($list === NULL)
		{
			$list = Kohana::list_files('classes');
		}

		$classes = array();

		foreach ($list as $name => $path)
		{
			if (is_array($path))
			{
				$classes += Kodoc::classes($path);
			}
			else
			{
				// Remove "classes/" and the extension
				$class = substr($name, 8, -(strlen(EXT)));

				// Convert slashes to underscores
				$class = str_replace('/', '_', strtolower($class));

				$classes[$class] = $class;
			}
		}

		return $classes;
	}

	public static function parse($comment)
	{
		// Normalize all new lines to \n
		$comment = str_replace(array("\r\n", "\n"), "\n", $comment);

		// Remove the phpdoc open/close tags and split
		$comment = array_slice(explode("\n", $comment), 1, -1);

		// Tag content
		$tags = array();

		foreach ($comment as $i => $line)
		{
			// Remove all leading whitespace
			$line = preg_replace('/^\s*\* ?/m', '', $line);

			// Search this line for a tag
			if (preg_match('/^@(\S+)(?:\s*(.+))?$/', $line, $matches))
			{
				// This is a tag line
				unset($comment[$i]);

				$name = $matches[1];
				$text = isset($matches[2]) ? $matches[2] : '';

				switch ($name)
				{
					case 'license':
						if (strpos($text, '://') !== FALSE)
						{
							// Convert the lincense into a link
							$text = HTML::anchor($text);
						}
					break;
					case 'copyright':
						if (strpos($text, '(c)') !== FALSE)
						{
							// Convert the copyright sign
							$text = str_replace('(c)', '&copy;', $text);
						}
					break;
					case 'throws':
						$text = HTML::anchor(Route::get('docs/api')->uri(array('class' => $text)), $text);
					break;
					case 'uses':
						if (preg_match('/^([a-z_]+)::([a-z_]+)$/i', $text, $matches))
						{
							// Make a class#method API link
							$text = HTML::anchor(Route::get('docs/api')->uri(array('class' => $matches[1])).'#'.$matches[2], $text);
						}
					break;
				}

				// Add the tag
				$tags[$name][] = $text;
			}
			else
			{
				// Overwrite the comment line
				$comment[$i] = (string) $line;
			}
		}

		// Concat the comment lines back to a block of text
		if ($comment = trim(implode("\n", $comment)))
		{
			// Parse the comment with Markdown
			$comment = Markdown($comment);
		}

		return array($comment, $tags);
	}

	public static function source($file, $start, $end)
	{
		if ( ! $file)
		{
			return FALSE;
		}

		$file = file($file, FILE_IGNORE_NEW_LINES);

		$file = array_slice($file, $start - 1, $end - $start + 1);

		if (preg_match('/^(\s+)/', $file[0], $matches))
		{
			$padding = strlen($matches[1]);

			foreach ($file as & $line)
			{
				$line = substr($line, $padding);
			}
		}

		return implode("\n", $file);
	}

	public $class;

	public $modifiers;

	public $description;

	public $tags = array();

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
		$props = array();

		foreach ($this->class->getProperties() as $property)
		{
			if ($property->isPublic())
			{
				$props[] = new Kodoc_Property($this->class->name, $property->name);
			}
		}

		return $props;
	}

	public function methods()
	{
		$methods = array();

		foreach ($this->class->getMethods() as $method)
		{
			$methods[] = new Kodoc_Method($this->class->name, $method->name);
		}

		return $methods;
	}

} // End Kodoc
