<?php defined('SYSPATH') or die('No direct script access.');

class Kohana_Kodoc {

	public static function factory($class)
	{
		return new Kodoc($class);
	}

	public static function classes()
	{
		$classes = Kohana::list_files('classes');

		echo Kohana::debug($classes);exit;
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
		$file = file($file, FILE_IGNORE_NEW_LINES);

		return implode("\n", array_slice($file, $start - 1, $end - 1));
	}

	public $class;

	public $description = '';

	public $tags = array();

	public function __construct($class)
	{
		$class = $parent = new ReflectionClass($class);

		do
		{
			if ($description = $parent->getDocComment())
			{
				list($body, $tags) = Kodoc::parse($description);

				// Set the description
				$this->description = $body;

				// Set the tags
				$this->tags = $tags;

				// Found a description for this class
				break;
			}
		}
		while ($parent = $parent->getParentClass());

		$this->class = $class;
	}

	public function properties()
	{
		$props = array();

		foreach ($this->class->getProperties() as $property)
		{
			$props[] = new Kodoc_Property($this->class->name, $property->name);
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
