<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Kodoc extends Controller_Template {

	/**
	 * @var  object  Kodoc instance
	 */
	public $kodoc;

	public $template = 'kodoc/template';

	public function before()
	{
		if ($this->request->action === 'media')
		{
			// Do not template media files
			$this->auto_render = FALSE;
		}

		return parent::before();
	}

	public function action_guide($lang = NULL, $page = NULL)
	{
		// Create a new guide instance
		$this->kodoc = Kodoc_Guide::factory($lang);

		// Load the requested page content
		$this->template->content = $this->kodoc->page($page);

		// Set the page title
		$this->template->title = $this->kodoc->page_title($page);
	}

	public function action_api()
	{
		throw new Kohana_Exception('API is not implemented yet');
	}

	public function action_media($file)
	{
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
			// Attach the menu to the template
			$this->template->menu = $this->kodoc->page('menu');

			// Get the media route
			$media = Route::get('kodoc_media');

			// Add styles
			$this->template->styles = array(
				$media->uri(array('file' => 'css/print.css'))  => 'print',
				$media->uri(array('file' => 'css/screen.css')) => 'screen',
				$media->uri(array('file' => 'css/kodoc.css'))  => 'screen',
			);
		}

		return parent::after();
	}

} // End Kodoc