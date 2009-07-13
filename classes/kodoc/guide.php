<?php defined('SYSPATH') or die('No direct script access.');

class Kodoc_Guide extends Kodoc {

	/**
	 * Create a new guide instance.
	 *
	 * @param   string  guide language
	 * @return  Kodoc_Guide
	 */
	public static function factory($lang = NULL)
	{
		return new Kodoc_Guide($lang);
	}

	// Current language
	protected $_lang;

	public function __construct($lang = NULL)
	{
		if ( ! empty($lang))
		{
			// Set the language code
			$this->_lang = $lang;
		}

		// Load Markdown
		parent::__construct();

		// Set the base URL for links
		Kodoc_Markdown::$base_url = URL::site(Route::get('kodoc_guide')->uri(array('language' => $lang))).'/';
	}

	public function find_file($name)
	{
		if ($this->_lang)
		{
			$name = $this->_lang.'/'.$name;
		}

		return Kohana::find_file('guide', $name, 'md');
	}

	public function page($name)
	{
		if ($file = $this->find_file($name))
		{
			return Markdown(file_get_contents($file));
		}
		else
		{
			return FALSE;
		}
	}

	public function page_title($page)
	{
		if ($menu = $this->find_file('menu'))
		{
			if (preg_match('~\[([^\]]+)\]\('.preg_quote($page).'\)~mu', file_get_contents($menu), $matches))
			{
				// Found a title for this link
				return $matches[1];
			}
		}

		return FALSE;
	}

} // End Kodoc_Guide
