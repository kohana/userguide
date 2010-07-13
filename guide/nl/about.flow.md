# Request Flow

Iedere applicatie volgt de zelfde flow:

1. Applicatie start vanaf `index.php`.
2. De application, module, en system paden worden ingesteld.
3. Error reporting niveaus worden ingesteld.
4. Install file wordt geladen, als het bestaat.
5. De [Kohana] class wordt ingeladen.
6. Het bootstrap bestand, `APPPATH/bootstrap.php`, wordt geinclude.
7. [Kohana::init] wordt aangeroepen, deze stelt error handling, caching, en logging in.
8. [Kohana_Config] lezers en [Kohana_Log] schrijvers worden toegevoegd.
9. [Kohana::modules] wordt aangeroepen om additionele modules te activeren.
    * Module paden worden toegevoegd aan het [cascading filesystem](about.filesystem).
    * Includen van `init.php` bestand, als het bestaat. 
    * Het `init.php` bestand kan een extra omgevingsinstellingen instellen, waaronder het toevoegen van routes.
10. [Route::set] wordt verschillende keren opgeroepen om de [applicaties routes](using.routing) te definiëren.
11. [Request::instance] wordt opgeroepen om het request-proces te starten.
    1. Iedere route controleren dat is ingesteld tot er een overeenkomst is gevonden.
    2. Conroller instantie wordt gecreeërd en het request wordt doorgeven eraan.
    3. De [Controller::before] methode wordt aangeroepen.
    4. De controller action wordt aangeroepen, deze genereerd de request response.
    5. De [Controller::after] methode wordt aangeroepen.
        * De 5 bovenstaande stappen kunnen verschillende keren worden herhaald wanneer je [HMVC sub-requests](about.mvc) gebruikt.
12. De basis [Request] response wordt getoond

## index.php

Kohana volgt een [front controller] pattern, dit betekent dat alle requests worden gezonden naar `index.php`. Dit laat een zeer eenvoudig [bestandsstructuur](about.filesystem) design toe. In `index.php` zijn er enkele zeer basis configuratie opties mogelijk. je kan de `$application`, `$modules`, en `$system` paden veranderen en het error reporting level instellen.

De `$application` variabele laat je toe om de folder in te stellen die al je application bestanden bevat. Standaard is dit `application`. De `$modules` variabele laat je toe om de folder in te stellen die alle module bestanden bevat. De `$system` variabele laat je toe om de folder in te stellen die alle Kohana bestanden bevat.

Je kan deze drie folders overal naartoe verplaatsen. Bijvoorbeeld, als je folderstructuur zo is ingesteld:

    www/
        index.php
        application/
        modules/
        system/

Dan kan je de folders uit de webroot verplaatsen:

    application/
    modules/
    system/
    www/
        index.php

Dan moet je de instellingen in `index.php` veranderen naar:

    $application = '../application';
    $modules     = '../modules';
    $system      = '../system';

Nu kan geen enkele van deze folders worden bereikt via de webserver. Het is niet noodzakelijk om deze verandering te maken, maar het maakt het wel mogelijk om de folders te delen met meerdere applicaties, de mogelijkheden zijn enorm.

[!!] Er is een veiligheidscontrole bovenaan elke Kohana file om te voorkomen dat het wordt uitgevoerd zonder het gebruik van de front controller. Maar natuurlijk is het veiliger om de application, modules, en system folders te verplaatsen naar een locatie dat niet kan worden bereikt via het web.

### Error Reporting

Standaard toont Kohana alle errors, zo ook strikte warnings. Dit wordt ingesteld door [error_reporting](http://php.net/error_reporting):

    error_reporting(E_ALL | E_STRICT);

Als je applicatie live staat en in productie is, een meer conversatieve instelling is aangeraden, zoals het negeren van notices:

    error_reporting(E_ALL & ~E_NOTICE);

Als je een wit scherm krijgt wanneer een error is opgetreden, dan zal uw host waarschijnlijk het tonen van errors hebben uitgeschakeld. Je kan dit terug aanzetten door deze lijn toe te voegen juist achter je `error_reporting` call:

    ini_set('display_errors', TRUE);

Errors zouden **altijd** moeten worden getoond, zelf in productie, omdat het je toelaat om [exception en error handling](debugging.errors) te gebruiken om een mooie error pagina te tonen in plaats van een wit scherm als een error voorkomt.