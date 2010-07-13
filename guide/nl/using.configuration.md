# Algemene Configuratie

Kohana gebruikt zowel static properties als bestanden worden gebruikt voor de configuratie. Static properties zijn worden meestal gebruikt voor static classes, zoals [Cookie], [Security] en [Upload]. Bestanden worden meestal gebruikt voor objecten zoals [Database], [Encrypt] en [Session].

Static properties kunnen ingesteld worden in `APPPATH/bootstrap.php` of door [class uitbreding](using.autoloading#class-extension). Het voordeel van static properties is dat er geen extra bestanden moeten worden ingeladen. Het probleem met deze methode is dat de class ingeladen word wanneer een property is ingesteld, als je geen uitbreiding gebruikt. Echter, met gebruik van uitbreidingen worden uitbreidingen uit modules overladen. Het is aanbevolen om static property te gebruiken voor configuraties in de bootstrap.

[!!] Wanneer je opcode caching gebruikt, zoals [APC](http://php.net/apc) of [eAccelerator](http://eaccelerator.net/), dan is het inladen van classes merkbaar vermindert. Het is dan ook streng aanbevolen om opcode caching te bruiken bij *elke* website in productie, of die nu groot of klein is.

## Noodzakelijke instellingen

Bij iedere nieuwe Kohana installatie is het vereist om de [Kohana::init] instellingen aan te passen in `APPPATH/bootstrap.php`. Iedere instelling die niet specifiek is ingesteld zal de standaard instelling gebruiken. Deze instellingen kunnen aangeroepen worden en/of aangepast worden op een later tijdstip door de static property van de [Kohana] class te gebruiken. Bijvoorbeeld, om de huidige karakterset te verkrijgen lees je de [Kohana::$charset] property in.

## Veiligheids instellingen

Er zijn verschillende instellingen dat je moet veranden om Kohana veilig te maken. De belangrijkste is [Cookie::$salt], deze wordt gebruikt om een "handtekening" te maken op cookies zodat ze niet kunnen worden aangepast van buiten Kohana.

Als je de [Encrypt] class wilt gebruiken, maak je best ook een `encrypt` configuratie bestand en stel je een encryption `key` in. Deze key bevat best letters, nummers en symbolen om de veiligheid te optimaliseren.

[!!] **Gebruik geen hash als encryption key!** Indien je dit doet zal de encryption key gemakkelijker te kraken zijn.

# Configuratie bestanden {#config-files}

Configuratie bestanden zijn licht anders dan andere bestanden in het [cascading bestandssyteem](about.filesystem). Configuratie bestanden worden **gemerged** in plaats van overladen. Dit wil zeggen dat alle configuratie bestanden hetzelfde path worden gecombineerd om יי configuratie te vormen. Wat wil zeggen dat je *individuele* instellingen kan overladen in plaats van een volledig bestand te dupliceren. 

Configuratie bestanden zijn pure PHP bestanden, opgeslaan in de `config/` folder, die een associatieve array teruggeven:

    <?php defined('SYSPATH') or die('No direct script access.');

    return array(
        'setting' => 'value',
        'options' => array(
            'foo' => 'bar',
        ),
    );

Als het bovenstaande bestand `myconf.php` werd genoemd, dan kon je deze benaderen via:

    $config = Kohana::config('myconf');
    $options = $config['options'];

[Kohana::config] biedt ook een shortcut om individuele keys van configuratie arrays te benaderen door gebruik te maken van "dot paths".

Verkrijg de "options" array:

    $options = Kohana::config('myconf.options');

Verkrijg de "foo" key van de "options" array:

    $foo = Kohana::config('myconf.options.foo');

Configuratie arrays kunnen ook worden benaderd als objecten, indien je deze manier wilt gebruiken:

    $options = Kohana::config('myconf')->options;

Let wel, je kan enkel keys op het bovenste niveau aanspreken als object properties, alle lagere keys moeten benaderd worden via de standaard array syntax:

    $foo = Kohana::config('myconf')->options['foo'];
