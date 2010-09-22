# Installatie

1. Download de laatste **stabiele** release van de [Kohana website](http://kohanaframework.org/).
2. Unzip het gedownloade pakket om de `kohana` folder aan te maken.
3. Upload de inhoud van deze folder naar je webserver.
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
   [Route caching](api/Route#cache) kan ook helpen als je heel wat routes hebt.
2. Catch alle exceptions in `application/bootstrap.php`, zodat gevoelige gegevens niet kan worden gelekt door stack traces. 
   Zie onderstaand voorbeeld van Shadowhand's [wingsc.com broncode](http://github.com/shadowhand/wingsc).
3. Zet APC of een andere soort opcode caching aan. Dit is het enige en eenvoudigste manier om de performantie te verbeteren dat je kunt doen in PHP zelf. Hoe complexer je applicatie, hoe groter het voordeel van opcode caching.

[!!] Opmerking: De standaard bootstrap zal Kohana::$environment = $_ENV['KOHANA_ENV'] instellen indien ingesteld. Documentatie hoe je deze variable moet invullen kan je vinden in je webservers documentatie (e.g. [Apache](http://httpd.apache.org/docs/1.3/mod/mod_env.html#setenv), [Lighttpd](http://redmine.lighttpd.net/wiki/1/Docs:ModSetEnv#Options), [Nginx](http://wiki.nginx.org/NginxHttpFcgiModule#fastcgi_param))). Deze manier wordt als beste beschouwd in vergelijking met de alternatieve manieren om Kohana::$environment in te stellen.

		/**
		 * Stel de omgeving in aan de hand van het domein (standaard Kohana::DEVELOPMENT).
		 */
		Kohana::$environment = ($_SERVER['SERVER_NAME'] !== 'localhost') ? Kohana::PRODUCTION : Kohana::DEVELOPMENT;
		/**
		 * Initialiseer Kohana op basis van de omgeving
		 */
		Kohana::init(array(
			'base_url'   => '/',
			'index_file' => FALSE,
			'profile'    => Kohana::$environment !== Kohana::PRODUCTION,
			'caching'    => Kohana::$environment === Kohana::PRODUCTION,
		));
		
		/**
		 * Voer de algemene request uit met PATH_INFO. Als er geen URI is gespecifeerd,
		 * dan zal de URI automatisch worden gedetecteerd.
		 */
		$request = Request::instance($_SERVER['PATH_INFO']);
		
		try
		{
			// Propeer het request uit te voeren
			$request->execute();
		}
		catch (Exception $e)
		{
			if (Kohana::$environment == Kohana::DEVELOPMENT)
			{
				// Just re-throw the exception
				throw $e;
			}
		
			// De error loggen
			Kohana::$log->add(Kohana::ERROR, Kohana::exception_text($e));
		
			// Maak een 404 uitvoer
			$request->status = 404;
			$request->response = View::factory('template')
			  ->set('title', '404')
			  ->set('content', View::factory('errors/404'));
		}
		
		if ($request->send_headers()->response)
		{
			// Verkrijg totaal aantal geheugen en snelheids tijd
			$total = array(
			  '{memory_usage}' => number_format((memory_get_peak_usage() - KOHANA_START_MEMORY) / 1024, 2).'KB',
			  '{execution_time}' => number_format(microtime(TRUE) - KOHANA_START_TIME, 5).' seconds');
			
			// Stel de totalen in, in de uitvoer
			$request->response = str_replace(array_keys($total), $total, $request->response);
		}
		
		
		/**
		 * Toon de uitvoer dan het request.
		 */
		echo $request->response;
