<?php defined('SYSPATH') or die('No direct script access.');

class Kodoc_Markdown extends MarkdownExtra_Parser {

	/**
	 * @var  string  base url for links
	 */
	public static $base_url = '';

	public function __construct()
	{
		// doLinks is 20, execute just before
		$this->span_gamut['do_add_base_url'] = 19;

		// PHP4 makes me sad.
		parent::MarkdownExtra_Parser();
	}

	/**
	 * Add the current base url to all links.
	 *
	 * @param   string  span text
	 * @return  string
	 */
	public function do_add_base_url($text)
	{
		return preg_replace_callback('-^\[([^\]]+)\]\((\S*)\)$-', array($this, '_do_add_base_url'), $text);
	}

	public function _do_add_base_url($matches)
	{
		if ($matches[2] AND strpos($matches[2], '://') === FALSE)
		{
			// Add the base url to the link URL
			$matches[2] = Kodoc_Markdown::$base_url.$matches[2];
		}

		// Recreate the link
		return "[{$matches[1]}]({$matches[2]})";
	}

} // End Kodoc_Markdown
