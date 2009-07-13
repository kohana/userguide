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
		if (($file = Kohana::find_file('guide', $this->_lang.'/'.$name, 'md')) === FALSE)
		{
			// Get the untranslated file
			$file = Kohana::find_file('guide', $name, 'md');
		}

		return $file;
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
		if (strpos($page, 'classes.') === 0)
		{
			$file = $this->find_file('classes');
		}
		else
		{
			$file = $this->find_file('menu');
		}

		if ($file AND $text = file_get_contents($file))
		{
			if (preg_match('~\[([^\]]+)\]\('.preg_quote($page).'\)~mu', $text, $matches))
			{
				// Found a title for this link
				return $matches[1];
			}
		}

		return FALSE;
	}

} // End Kodoc_Guide
