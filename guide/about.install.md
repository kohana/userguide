# Installation

1. Download the latest **stable** release from the [Kohana website](http://kohanaframework.org/).
2. Unzip the downloaded package to create a `kohana` directory.
3. Upload the contents of this folder to your webserver.
4. Open `application/bootstrap.php` and make the following changes:
	- Set the default [timezone](http://php.net/timezones) for your application.
	- Set the `base_url` in the [Kohana::init] call to reflect the location of the kohana folder on your server.
6. Make sure the `application/cache` and `application/logs` directories are writable by the web server.
7. Test your installation by opening the URL you set as the `base_url` in your favorite browser.

[!!] Depending on your platform, the installation's subdirs may have lost their permissions thanks to zip extraction. Chmod them all to 755 by running `find . -type d -exec chmod 0755 {} \;` from the root of your Kohana installation.

You should see the installation page. If it reports any errors, you will need to correct them before continuing.

![Install Page](img/install.png "Example of install page")

Once your install page reports that your environment is set up correctly you need to either rename or delete `install.php` in the root directory. You should then see the Kohana welcome page:

![Welcome Page](img/welcome.png "Example of welcome page")

## Setting up a production environment

There are a few things you'll want to do with your application before moving into production.

1. See the [Configuration page](about.configuration) in the docs. 
   This covers most of the global settings that would change between environments. 
   As a general rule, you should enable caching and disable profiling ([Kohana::init] settings) for production sites. 
   [Route caching](api/Route#cache) can also help if you have a lot of routes.
2. Catch all exceptions in `application/bootstrap.php`, so that sensitive data is cannot be leaked by stack traces.
   See the example below which was taken from Shadowhand's [wingsc.com source](http://github.com/shadowhand/wingsc).
3. Turn on APC or some kind of opcode caching. 
   This is the single easiest performance boost you can make to PHP itself. The more complex your application, the bigger the benefit of using opcode caching.

[!!] Note: The default bootstrap will set Kohana::$environment = $_ENV['KOHANA_ENV'] if set. Docs on how to supply this variable are available in your web server's documentation (e.g. [Apache](http://httpd.apache.org/docs/1.3/mod/mod_env.html#setenv), [Lighttpd](http://redmine.lighttpd.net/wiki/1/Docs:ModSetEnv#Options), [Nginx](http://wiki.nginx.org/NginxHttpFcgiModule#fastcgi_param)). This is considered better practice than many alternative methods to set Kohana::$enviroment.

		/**
		 * Set the environment string by the domain (defaults to Kohana::DEVELOPMENT).
		 */
		Kohana::$environment = ($_SERVER['SERVER_NAME'] !== 'localhost') ? Kohana::PRODUCTION : Kohana::DEVELOPMENT;
		/**
		 * Initialise Kohana based on environment
		 */
		Kohana::init(array(
			'base_url'   => '/',
			'index_file' => FALSE,
			'profile'    => Kohana::$environment !== Kohana::PRODUCTION,
			'caching'    => Kohana::$environment === Kohana::PRODUCTION,
		));
		
		/**
		 * Execute the main request using PATH_INFO. If no URI source is specified,
		 * the URI will be automatically detected.
		 */
		$request = Request::instance($_SERVER['PATH_INFO']);
		
		try
		{
			// Attempt to execute the response
			$request->execute();
		}
		catch (Exception $e)
		{
			if (Kohana::$environment === Kohana::DEVELOPMENT)
			{
				// Just re-throw the exception
				throw $e;
			}
		
			// Log the error
			Kohana::$log->add(Kohana::ERROR, Kohana::exception_text($e));
		
			// Create a 404 response
			$request->status = 404;
			$request->response = View::factory('template')
			  ->set('title', '404')
			  ->set('content', View::factory('errors/404'));
		}
		
		if ($request->send_headers()->response)
		{
			// Get the total memory and execution time
			$total = array(
			  '{memory_usage}' => number_format((memory_get_peak_usage() - KOHANA_START_MEMORY) / 1024, 2).'KB',
			  '{execution_time}' => number_format(microtime(TRUE) - KOHANA_START_TIME, 5).' seconds');
			
			// Insert the totals into the response
			$request->response = str_replace(array_keys($total), $total, $request->response);
		}
		
		
		/**
		 * Display the request response.
		 */
		echo $request->response;

