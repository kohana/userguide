<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Kohana user guide and api browser.
 *
 * @package    Kohana/Userguide
 * @category   Controllers
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
			$this->guide = Route::get('docs/guide');

			if (defined('MARKDOWN_PARSER_CLASS'))
			{
				throw new Kohana_Exception('Markdown parser already registered. Live documentation will not work in your environment.');
			}

			// Use customized Markdown parser
			define('MARKDOWN_PARSER_CLASS', 'Kodoc_Markdown');

			if ( ! class_exists('Markdown', FALSE))
			{
				// Load Markdown support
				require Kohana::find_file('vendor', 'markdown/markdown');
			}

			// Set the base URL for links and images
			Kodoc_Markdown::$base_url  = URL::site($this->guide->uri()).'/';
			Kodoc_Markdown::$image_url = URL::site($this->media->uri()).'/';
		}

		parent::before();
	}
	
	// List all modules that have userguides
	public function index()
	{
		$this->template->title = "Userguide";
		$this->template->breadcrumb = array('User Guide');
		$this->template->content = View::factory('userguide/index', array('modules' => Kohana::config('userguide.modules')));
		$this->template->menu = View::factory('userguide/menu', array('modules' => Kohana::config('userguide.modules')));
	}
	
	// Display an error if a page isn't found
	public function error($message)
	{
		$this->request->status = 404;
		$this->template->title = "Userguide - Error";
		$this->template->content = View::factory('userguide/error',array('message' => $message));

		// If we are in a module and that module has a menu, show that, otherwise use the index page menu
		if ($module = $this->request->param('module') AND $menu = $this->file($module.'/menu'))
		{
			// Namespace the markdown parser
			Kodoc_Markdown::$base_url  = URL::site($this->guide->uri()).'/'.$module.'/';
			Kodoc_Markdown::$image_url = URL::site($this->media->uri()).'/'.$module.'/';
		
			$this->template->menu = Markdown(file_get_contents($menu));
			$this->template->breadcrumb = array(
				$this->guide->uri() => 'User Guide',
				$this->guide->uri(array('module' => $module)) => Kohana::config('userguide.modules.'.$module.'.name'),
				'Error'
			);
		}
		else
		{
			$this->template->menu = View::factory('userguide/menu',array('modules' => Kohana::config('userguide.modules')));
			$this->template->breadcrumb = array($this->guide->uri() => 'User Guide','Error');
		}
	}

	public function action_docs()
	{
		$module = $this->request->param('module');
		$page = $this->request->param('page');

		// Trim trailing slash
		$page = rtrim($page, '/');

		// If no module specified, show the user guide index page, which lists the modules.
		if ( ! $module)
		{
			return $this->index();
		}
		
		// Prevent "guide/module" and "guide/module/index" from having duplicate content
		if ( $page == 'index')
		{
			return $this->error(__('Userguide page not found'));
		}
		
		// If a module is set, but no page is specified, default to index.
		if ( ! $page )
		{
			$page = 'index';
		}

		// Find the markdown file for this page
		$file = $this->file($module.'/'.$page);

		// If it's not found, show the error page
		if ( ! $file)
		{
			return $this->error(__('Userguide page not found'));
		}
		
		// Namespace the markdown parser
		Kodoc_Markdown::$base_url  = URL::site($this->guide->uri()).'/'.$module.'/';
		Kodoc_Markdown::$image_url = URL::site($this->media->uri()).'/'.$module.'/';

		// Set the page title
		$this->template->title = $page == 'index' ? Kohana::config('userguide.modules.'.$module.'.name') : $this->title($page);

		// Parse the page contents into the template
		$this->template->content = Markdown(file_get_contents($file));

		// Attach this module's menu to the template
		$this->template->menu = Markdown(file_get_contents($this->file($module.'/menu')));
		
		// Bind the breadcrumb
		$this->template->bind('breadcrumb', $breadcrumb);
		
		// Bind the copyright
		$this->template->copyright = Kohana::config('userguide.modules.'.$module.'.copyright');

		// Add the breadcrumb trail
		$breadcrumb = array();
		$breadcrumb[$this->guide->uri()] = __('User Guide');
		$breadcrumb[$this->guide->uri(array('module' => $module))] = Kohana::config('userguide.modules.'.$module.'.name');
		$breadcrumb[] = $this->template->title;
	}

	public function action_api()
	{
		// Enable the missing class autoloader
		spl_autoload_register(array('Kodoc_Missing', 'create_class'));

		// Get the class from the request
		$class = $this->request->param('class');

		if ($class)
		{
			try
			{
				$_class = Kodoc_Class::factory($class);

				if ( ! Kodoc::show_class($_class))
					throw new Exception(__('That class is hidden'));
			}
			catch (Exception $e)
			{
				return $this->error(__('API Reference: Class not found.'));
			}
			
			$this->template->title = $class;

			$this->template->content = View::factory('userguide/api/class')
				->set('doc', Kodoc::factory($class))
				->set('route', $this->request->route);
		}
		else
		{
			$this->template->title = __('Table of Contents');

			$this->template->content = View::factory('userguide/api/toc')
				->set('classes', Kodoc::class_methods())
				->set('route', $this->request->route);
		}

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
		// Generate and check the ETag for this file
		//$this->request->check_cache(sha1($this->request->uri));

		// Get the file path from the request
		$file = $this->request->param('file');

		// Find the file extension
		$ext = pathinfo($file, PATHINFO_EXTENSION);

		// Remove the extension from the filename
		$file = substr($file, 0, -(strlen($ext) + 1));

		if ($file = Kohana::find_file('media/guide', $file, $ext))
		{
			// Send the file content as the response
			$this->request->response = file_get_contents($file);
		}
		else
		{
			// Return a 404 status
			$this->request->status = 404;
		}

		// Set the proper headers to allow caching
		$this->request->headers['Content-Type']   = File::mime_by_ext($ext);
		$this->request->headers['Content-Length'] = filesize($file);
		$this->request->headers['Last-Modified']  = date('r', filemtime($file));
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
				$media->uri(array('file' => 'css/reset.css')) => 'screen',
				$media->uri(array('file' => 'css/kodoc.css'))  => 'screen',
				$media->uri(array('file' => 'css/shCore.css')) => 'screen',
				$media->uri(array('file' => 'css/shThemeKodoc.css')) => 'screen',
			);

			// Add scripts
			$this->template->scripts = array(
				$media->uri(array('file' => 'js/jquery.min.js')),
				$media->uri(array('file' => 'js/kodoc.js')),
				// Syntax Highlighter
				$media->uri(array('file' => 'js/shCore.js')),
				$media->uri(array('file' => 'js/shBrushPhp.js')),
			);

			// Add languages
			$this->template->translations = Kohana::message('userguide', 'translations');
		}

		return parent::after();
	}

	public function file($page)
	{
		return Kohana::find_file('guide', $page, 'md');
	}

	public function section($page)
	{
		$markdown = $this->_get_all_menu_markdown();
		
		if (preg_match('~\*{2}(.+?)\*{2}[^*]+\[[^\]]+\]\('.preg_quote($page).'\)~mu', $markdown, $matches))
		{
			return $matches[1];
		}
		
		return $page;
	}

	public function title($page)
	{
		$markdown = $this->_get_all_menu_markdown();
		
		if (preg_match('~\[([^\]]+)\]\('.preg_quote($page).'\)~mu', $markdown, $matches))
		{
			// Found a title for this link
			return $matches[1];
		}
		
		return $page;
	}
	
	protected function _get_all_menu_markdown()
	{
		// Only do this once per request...
		static $markdown = '';
		
		if (empty($markdown))
		{
			// Get core menu items
			$file = $this->file($this->request->param('module').'/menu');
	
			if ($file AND $text = file_get_contents($file))
			{
				$markdown .= $text;
			}
			
		}
		
		return $markdown;
	}

} // End Userguide
