<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana user guide and api browser.
 *
 * @package    Userguide
 * @author     Kohana Team
 */
class Controller_Userguide extends Controller_Template {

	public $template = 'userguide/template';

	// Routes
	protected $media;
	protected $api;
	protected $guide;

	public function before()
	{
		if ($this->request->action === 'media')
		{
			// Do not template media files
			$this->auto_render = FALSE;
		}
		else
		{
			// Grab the necessary routes
			$this->media = Route::get('docs/media');
			$this->api   = Route::get('docs/api');
			$this->guide = Route::get('docs/guide');

			if (isset($_GET['lang']))
			{
				$lang = $_GET['lang'];

				// Load the accepted language list
				$translations = array_keys(Kohana::message('userguide', 'translations'));

				if (in_array($lang, $translations))
				{
					// Set the language cookie
					Cookie::set('userguide_language', $lang, Date::YEAR);
				}

				// Reload the page
				$this->request->redirect($this->request->uri);
			}

			// Set the translation language
			I18n::$lang = Cookie::get('userguide_language', Kohana::config('userguide')->lang);

			// Use customized Markdown parser
			define('MARKDOWN_PARSER_CLASS', 'Kodoc_Markdown');

			// Load Markdown support
			require Kohana::find_file('vendor', 'markdown/markdown');

			// Set the base URL for links and images
			Kodoc_Markdown::$base_url  = URL::site($this->guide->uri()).'/';
			Kodoc_Markdown::$image_url = URL::site($this->media->uri()).'/';
		}

		parent::before();
	}

	public function action_docs()
	{
		$page = $this->request->param('page');

		if ( ! $page)
		{
			// Redirect to the default page
			$this->request->redirect($this->guide->uri(array('page' => 'about.kohana')));
		}

		$file = $this->file($page);

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

		// Add the breadcrumb
		$breadcrumb = array();
		$breadcrumb[$this->guide->uri()] = __('User Guide');
		$breadcrumb[] = $this->section($page);
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
		$breadcrumb[$this->guide->uri(array('page' => NULL))] = __('User Guide');
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
			$this->template->translations = Kohana::message('userguide', 'translations');
		}

		return parent::after();
	}

	public function file($page)
	{
		if ( ! ($file = Kohana::find_file('guide', I18n::$lang.'/'.$page, 'md')))
		{
			// Use the default file
			$file = Kohana::find_file('guide', $page, 'md');
		}

		return $file;
	}

	public function section($page)
	{
		$file = $this->file('menu');

		if ($file AND $text = file_get_contents($file))
		{
			if (preg_match('~\*{2}(.+?)\*{2}[^*]+\[[^\]]+\]\('.preg_quote($page).'\)~mu', $text, $matches))
			{
				return $matches[1];
			}
		}

		return $page;
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
