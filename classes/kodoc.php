<?php defined('SYSPATH') or die('No direct script access.');

abstract class Kodoc {

	public function __construct()
	{
		// Use customized Markdown parser
		define('MARKDOWN_PARSER_CLASS', 'Kodoc_Markdown');

		// Load Markdown support
		require Kohana::find_file('vendor', 'markdown/markdown');
	}

} // End Kodoc
