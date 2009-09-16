<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana user guide and api browser.
 *
 * @package    Kodoc
 * @author     Kohana Team
 */
class Controller_Userguide extends Controller_Template {

	public $template = 'userguide/template';

	public function before()
	{
		if ($this->request->action === 'media')
		{
			// Do not template media files
			$this->auto_render = FALSE;
		}
		else
		{
			if (isset($_GET['lang']))
			{
				$lang = $_GET['lang'];

				// Load the accepted language list
				$translations = array_keys(Kohana::message('userguide', 'translations'));

				if (in_array($lang, $translations))
				{
					// Set the language cookie
					Cookie::set('userguide/language', $lang, Date::YEAR);
				}

				// Reload the page
				$this->request->redirect($this->request->uri);
			}

			// Set the language
			$this->_lang = Cookie::get('userguide/language', 'en');

			// Use customized Markdown parser
			define('MARKDOWN_PARSER_CLASS', 'Kodoc_Markdown');

			// Load Markdown support
			require Kohana::find_file('vendor', 'markdown/markdown');

			// Set the base URL for links and images
			Kodoc_Markdown::$base_url  = URL::site(Route::get('docs/guide')->uri(array('lang' => $this->_lang, 'page' => NULL))).'/';
			Kodoc_Markdown::$image_url = URL::site(Route::get('docs/media')->uri()).'/';
		}

		parent::before();
	}

	public function action_docs()
	{
		// Get the path for this page
		$file = $this->file($page = $this->request->param('page'));

		if ( ! $file)
		{
			throw new Kohana_Exception('User guide page not found: :page',
				array(':page' => $page));
		}

		// Set the page title
		$this->template->title = $this->title($page);

		// Parse the page contents into the template
		$this->template->content = Markdown(file_get_contents($file));

		// Attach the menu to the template
		$this->template->menu = Markdown(file_get_contents($this->file('menu')));

		// Bind the breadcrumb
		$this->template->bind('breadcrumb', $breadcrumb);

		// Get the docs URI
		$guide = Route::get('docs/guide');

		// Add the breadcrumb
		$breadcrumb = array();
		$breadcrumb[$guide->uri(array('page' => NULL))] = __('User Guide');

		if (strpos($page, '.'))
		{
			list($section) = explode('.', $page);

			$breadcrumb[$guide->uri(array('page' => $section))] = $this->title($section);
		}

		$breadcrumb[] = $this->template->title;
	}

	public function action_api()
	{
		// Get the class from the request
		$class = $this->request->param('class', 'Kohana');

		// Set the template title
		$this->template->title = $class;

		$this->template->content = View::factory('userguide/api/class')
			->set('doc', Kodoc::factory($class))
			->set('route', $this->request->route);

		// Attach the menu to the template
		$this->template->menu = Kodoc::menu();

		// Bind the breadcrumb
		$this->template->bind('breadcrumb', $breadcrumb);

		// Get the docs URI
		$guide = Route::get('docs/guide');

		// Add the breadcrumb
		$breadcrumb = array();
		$breadcrumb[$guide->uri(array('page' => NULL))] = __('User Guide');
		$breadcrumb[$this->request->route->uri()] = $this->title('api');
		$breadcrumb[] = $this->template->title;
	}

	public function action_media()
	{
		// Get the file path from the request
		$file = $this->request->param('file');

		// Find the file extension
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		// Remove the extension from the filename
		$file = substr($file, 0, -(strlen($ext) + 1));

		if ($file = Kohana::find_file('media', $file, $ext))
		{
			// Send the file content as the response
			$this->request->response = file_get_contents($file);
		}
		else
		{
			// Return a 404 status
			$this->request->status = 404;
		}

		// Set the content type for this extension
		$this->request->headers['Content-Type'] = File::mime_by_ext($ext);
	}

	public function after()
	{
		if ($this->auto_render)
		{
			// Get the media route
			$media = Route::get('docs/media');

			// Add styles
			$this->template->styles = array(
				$media->uri(array('file' => 'css/print.css'))  => 'print',
				$media->uri(array('file' => 'css/screen.css')) => 'screen',
				$media->uri(array('file' => 'css/kodoc.css'))  => 'screen',
			);

			// Add scripts
			$this->template->scripts = array(
				'http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js',
				$media->uri(array('file' => 'js/kodoc.js')),
			);

			// Add languages
			$this->template->lang = $this->_lang;
			$this->template->translations = Kohana::message('userguide', 'translations');
		}

		return parent::after();
	}

	public function file($page)
	{
		if ( ! ($file = Kohana::find_file('guide', "{$this->_lang}/$page", 'md')))
		{
			// Use the default file
			$file = Kohana::find_file('guide', $page, 'md');
		}

		return $file;
	}

	public function title($page)
	{
		$file = $this->file('menu');

		if ($file AND $text = file_get_contents($file))
		{
			if (preg_match('~\[([^\]]+)\]\('.preg_quote($page).'\)~mu', $text, $matches))
			{
				// Found a title for this link
				return $matches[1];
			}
		}

		return $page;
	}

} // End Userguide
