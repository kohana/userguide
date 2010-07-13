# Cascading Filesystem

Het Kohana filesysteem heeft hiërarchie van folder-structuur. Wanneer een bestand wordt ingeladen door [Kohana::find_file], dan wordt het gezocht in de volgend volgorde:

Application pad
: Gedefineerd als `APPPATH` in `index.php`. De standaard value hiervan is `application`.

Module paden
: Dit is ingesteld als een associatieve array met behulp van [Kohana::modules] in `APPPATH/bootstrap.php`. Elk van de waarden van de array zal worden gezocht in de volgorde waarin de modules worden toegevoegd.

System pad
: Gedefineerd als `SYSPATH` in `index.php`. De standaard value hiervan is `system`. Alle belangrijkste of "core"-bestanden en classes zijn hier gedefinieerd.

Bestanden die zich hoger bevinden in de volgorde van het inladen van bestanden hebben voorrang op bestanden met dezelfde naam die zich lager bevinden in de volgorde van inladen, dit maakt het mogelijk om ieder bestand te overloaden door een bestand met dezelfde naam in een "hogere" folder te plaatsen:

![Cascading Filesystem Infographic](img/cascading_filesystem.png)

Als je een view bestand hebt met de naam `welcome.php` in de `APPPATH/views` en `SYSPATH/views` folders, dan zal hetgeen uit application worden gereturned als `welcome.php` wordt ingeladen omdat het "hoger" staat in de folderstructuur.

## Types bestanden

De top level folders van de application, module en systeem paden hebben volgende standaard folders:

classes/
:  Alle classes dat je wilt [automatisch inladen](using.autoloading) moeten zich hier
   bevinden. Dit houdt in controllers, models, en alle andere classes. Alle classes moeten 
   de [class naam conventies](about.conventions#classes) volgen.

config/
:  Configuratie bestanden geven een associatieve array van opties terug die je kunt
   inladen via [Kohana::config]. Zie [gebruik van configuratie](using.configuration) voor
   meer informatie.

i18n/
:  Vertalingsbestanden geven een associatieve array van strings terug. Vertalen wordt 
   gedaan door de `__()` methode te gebruiken. Om "Hello, world!" te vertalen in het 
   Spaans zou je de methode `__('Hello, world!')` oproepen met [I18n::$lang] ingesteld op "es-es".
   Zie [gebruik van vertaling](using.translation) voor meer informatie.

messages/
:  Berichtenbestanden geven een associatieve array van strings terug die ingeladen kunnen 
   worden via [Kohana::message]. Messages en i18n bestanden verschillen erin dat messages
   niet worden vertaald, maar altijd geschreven worden in de standaard taal en verwezen worden 
   via een enkelvoudige key. Zie [gebruik van messages](using.messages) voor meer informatie.

views/
:  Views zijn plain PHP files die worden gebruikt om HTML of een ander formaat te genereren. Het view bestand wordt
   ingeladen in in een [View] object en toegewezen variabelen, die het dan zal omzetten naar een HTML fractie. Het is mogelijk om meerder views in elkaar te gebruiken.
   Zie [gebruik van views](usings.views) voor meer informatie.

## Vinden van betanden

Het pad naar eender welk bestand in de folderstructuur kan worden gevonden door het gebruik van [Kohana::find_file]:

    // Vind het volledige pad naar "classes/cookie.php"
    $path = Kohana::find_file('classes', 'cookie');

    // Vind het volledige pad naar "views/user/login.php"
    $path = Kohana::find_file('views', 'user/login');


# Vendor Extensions

Extensies die niet specifiek zijn aan Kohana noemen we "vendor" extensions.
Bijvoorbeeld, als je [DOMPDF](http://code.google.com/p/dompdf) wilt gebruiken,
dan moet je het kopiëren naar `application/vendor/dompdf` en de DOMPDF
autoloading class inladen:

    require Kohana::find_file('vendor', 'dompdf/dompdf/dompdf_config.inc');

Nu kan je DOMPDF gebruiken zonder inladen van andere bestanden:

    $pdf = new DOMPDF;

[!!] Indien je views wilt omzetten in PDFs via DOMPDF, probeer dan de
[PDFView](http://github.com/shadowhand/pdfview) module.
