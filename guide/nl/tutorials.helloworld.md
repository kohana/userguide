# Hello, World

Aangezien bijna ieder framework een soort van "hello world" voorbeeld heeft, zou het onbeleefd van ons zijn om deze traditie te doorbreken!

We gaan starten met het maken van een zeer basis hello world, om vervolgens uit te breiden om het MVC principe te volgen.

## Tot op het bot

Eerst moeten we een controller maken dat Kohana kan gebruiken om de request af te handelen.

Maak het bestand `application/classes/controller/hello.php` in uw applicatie folder en zorg voor deze code erin:

    <?php defined('SYSPATH') OR die('No Direct Script Access');

	Class Controller_Hello extends Controller
	{
		function action_index()
		{
			echo 'hello, world!';
		}
	}

Eens bekijken wat er hier allemaal gebeurd:

`<?php defined('SYSPATH') OR die('No Direct Script Access');`
:	Je zou de eerste tag moeten herkennen als een php openings-tag (indien niet, leer je best [php](http://php.net)). Wat volgt is een kleine controle dat er voor zorgt dat dit bestand enkel kan uitgevoerd worden indien het ingesloten is in Kohana. Op die manier kunnen mensen er niet direct naartoe surfen.

`Class Controller_Hello extends Controller`
:	Deze lijn declareert onze controller, iedere controller class moet een voorvoegsel `Controller_` hebben en een met-underscore-afgescheiden path naar de folder waarin de controller zich bevindt (zie [Conventies en codeerstijl](about.conventions) voor meer informatie). Iedere controller moet ook de basis `Controller` class uitbreiden, deze zorgt voor een standaard structuur voor controllers.


`function action_index()`
:	Dit definieerd de "index" actie van onze controller. Kohana zal proberen deze actie aan te roepen als de gebruiker geen actie heeft gespecifieerd. (Zie [Routes, URLs en Links](tutorials.urls))

`echo 'hello, world!';`
:	En dit is de lijn die zorgt voor de weergave van onze zin

Als je nu je browser opent en suft naar http://localhost/index.php/hello zou je zoiets moeten zien:

![Hello, World!](img/hello_world_1.png "Hello, World!")

## Dit was al goed maar we kunnen beter

Wat we deden in de vorige paragraaf was een goed voorbeeld van hoe gemakkelijk het is om een *zeer* elementaire Kohana applicatie te maken. (In feite is het zo basis, dat je het nooit meer opnieuw mag maken!)

Als je ooit al eens gehoord hebt over MVC, dan zal je jezelf waarschijlijk al gerealiseerd hebben dat content tonen aan de hand van "echo" tegenstrijdig is met de principes van MVC.

De goede manier van coderen met een MVC framework is het gebruik van _views_ om je applicatie te visualiseren en de controller laten doen waar hij goed in is, het controleren van de flow van het request!

Laten we onze originele controller lichtjes aanpassen:

    <?php defined('SYSPATH') OR die('No Direct Script Access');

	Class Controller_Hello extends Controller_Template
	{
		public $template = 'site';

		function action_index()
		{
			$this->template->message = 'hello, world!';
		}
	}

`extends Controller_Template`
:	We breiden nu uit van de template controller, dit maakt het meer logisch om views te gebruiken in onze controller.

`public $template = 'site';`
:	De template controller moet weten welke template we willen gebruiken. Het zal automatisch de view inladen die gedefinieerd is in deze variabele en het view object eraan toewijzen.

`$this->template->message = 'hello, world!';`
:	`$this->template` is een referentie naar het view object voor onze site template. Wat we hier doen is een variabele "message", met waarde "hello, world", toewijzen aan de view.

Laten we nu proberen onze code uit te voeren...

<div>{{userguide/examples/hello_world_error}}</div>

Voor de één of andere reden geeft Kohana een error en toont het niet ons cool bericht.

Als we kijken naar het error-bericht kunnen we zien dat de View library onze site template niet kon vinden, waarschijnlijk omdat we er nog geen aangemaakt hebben - *doh*!

Laten we het view bestand `application/views/site.php` aanmaken voor ons bericht:

	<html>
		<head>
			<title>We've got a message for you!</title>
			<style type="text/css">
				body {font-family: Georgia;}
				h1 {font-style: italic;}

			</style>
		</head>
		<body>
			<h1><?php echo $message; ?></h1>
			<p>We just wanted to say it! :)</p>
		</body>
	</html>

Als we de pagina vernieuwen dan kunnen we de vruchten zien van ons *zwaar" werk:

![hello, world! We just wanted to say it!](img/hello_world_2.png "hello, world! We just wanted to say it!")

## Stage 3 – Profit!

In deze tutorial heb je geleerd hoe je een controller maakt en een view gebruikt om je logica te scheiden van het visuele.

Dit is natuurlijk een zeer elementaire inleiding over het werken met Kohana en toont zelfs niet de sterkte van het framework voor wanneer je applicaties hiermee ontwikkelt.
