<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kohana_Kodoc {

	public static function method($name)
	{
		list($class, $method) = explode('::', $name);

		return new Kodoc_Method($class, $method);
	}

	public static function source($file, $start, $end)
	{
		$file = file($file, FILE_IGNORE_NEW_LINES);
		
		return implode("\n", array_slice($file, $start - 1, $end - 1));
	}

	public $source = '';

	public $docs = '';

	protected function _parse($comment)
	{
		$comment = $this->_strip($comment);

		if (preg_match_all('/\{?@link\s+(\S+)(?:\s+(.+))?\}?/m', $comment, $matches, PREG_SET_ORDER))
		{
			$replace = array();
			foreach ($matches as $match)
			{
				$replace[$match[0]] = '<a href="'.$match[1].'">'.(isset($match[2]) ? $match[2] : $match[1]).'</a>';
			}

			$comment = strtr($comment, $replace);
		}

		if (preg_match_all("/@param\s+(\S+)(?: +([^\n]+))?$/m", $comment, $matches, PREG_SET_ORDER))
		{
			$replace = array();
			foreach ($matches as $i => $match)
			{
				$replace[$match[0]] = '';
				if (isset($this->params[$i]))
				{
					$this->params[$i]['type'] = $match[1];

					if (isset($match[2]))
					{
						$this->params[$i]['description'] = $match[2];
					}
				}
			}

			$comment = strtr($comment, $replace);
		}

		$this->docs = $comment;
	}

	protected function _strip($comment)
	{
		// Remove the comment opening and closing lines: /**, */
		$comment = implode("\n", array_slice(explode("\n", $comment), 1, -1));

		// Remove all leading whitespace
		$comment = preg_replace('/^\s*\* ?/m', '', $comment);

		return $comment;
	}

} // End Kodoc
