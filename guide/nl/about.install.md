# Installatie

1. Download de laatste **stabiele** release van de [Kohana website](http://kohanaframework.org/)
2. Unzip het gedownloade pakket om de `kohana` folder aan te maken
3. Upload de inhoud van deze folder naar je webserver
4. Open `application/bootstrap.php` en maak de volgende aanpassingen:
	- Stel de standaard [timezone](http://php.net/timezones) in voor je applicatie
	- Stel de `base_url` in de [Kohana::init] methode in om te verwijzen naar de locatie van de kohana folder op je server
6. Zorg ervoor dat de `application/cache` en `application/logs` folders schrijfrechten hebben voor de web server
7. Test je installatie door de URL te openen in je favoriete browser dat je hebt ingesteld als `base_url`

[!!] Afhankelijk van je platform is het mogelijk dat de installatie subfolders hun rechten verloren hebben tijdens de zip extractie. Chmod ze allemaal met 755 door het commando `find . -type d -exec chmod 0755 {} \;` uit te voeren in de root van je Kohana installatie.

Je zou de installatie pagina moeten zien. Als het errors toont, zal je ze moeten aanpassen vooraleer je verder kunt gaan.

![Install Page](img/install.png "Voorbeeld van de installatie pagina")

Eens je installatie pagina zegt dat je omgeving goed is ingesteld dan moet je de `install.php` pagina hernoemen of verwijderen in de root folder. Je zou nu de de Kohana welcome pagina moeten zien:

![Welcome Page](img/welcome.png "Voorbeeld van welcome pagina")

## Een productie-omgeving opzetten

Er zijn enkele dingen dat je best doet met je applicatie vooraleer je deze in productie plaatst:

1. Bekijk de [configuratie pagina](about.configuration) in de documentatie. 
   Dit omvat het grootste gedeelte van de globale instellingen dat zouden moeten veranderen bij andere omgevingen. 
   Als algemene regel, zet je best caching aan en zet je profiling uit ([Kohana::init] settings) voor sites in productie. 
   Route caching kan ook helpen als je heel wat routes hebt.
2. Catch alle exceptions in `application/bootstrap.php`, zodat gevoelige gegevens niet kan worden gelekt door stack traces. 
   Zie onderstaand voorbeeld van Shadowhand's wingsc.com broncode.
3. Zet APC of een andere soort opcode caching aan. Dit is het enige en eenvoudigste manier om de performantie te verbeteren dat je kunt doen in PHP zelf. Hoe complexer je applicatie, hoe groter het voordeel van opcode caching.

		/**
		 * Set the environment string by the domain (defaults to 'development').
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
			if ( Kohana::$environment == 'development' )
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
