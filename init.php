<?php

// User guide pages, in modules
Route::set('docs/guide', 'guide(/<module>(/<page>))', ['module' => '[-\w]+', 'page' => '[-\w]+'])
	->defaults(['controller' => 'Userguide', 'action' => 'docs']);

// Static file serving (CSS, JS, images)
Route::set('docs/media', 'guide-media/<file>', ['file' => '[-\w\.\/]+'])
	->defaults(['controller' => 'Userguide', 'action' => 'media']);

// API Browser, if enabled
if (Kohana::$config->load('userguide.api_browser'))
{
	Route::set('docs/api', 'guide-api/<class>', ['class' => '\w+'])
		->defaults(['controller' => 'Userguide', 'action' => 'api']);
}

// Register the autoloader for Markdown
spl_autoload_register(function ($class) {
	if (in_array($class, ['Markdown_Parser', 'MarkdownExtra_Parser']))
	{
		require Kohana::find_file('vendor', 'markdown/markdown');
	}
});
