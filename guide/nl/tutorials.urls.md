# Routes, URLs en Links

Dit onderdeel zal je een basis idee geven achter Kohana's request routing, de generatie van url's en links.

## Routing

Zoals gezegd in de [Request Flow](about.flow) sectie, wordt een request afgehandeld door de [Request] class die een juiste [Route] vindt en de juiste controller inlaadt om het request af te handelen. Dit systeem biedt veel flexibiliteit en een logische manier van werken.

Als je kijkt in `APPPATH/bootstrap.php` zal je zien dat de volgende code onmiddelijk wordt aangeroepen vooraleer de request wordt toegewezen aan [Request::instance]:

    Route::set('default', '(<controller>(/<action>(/<id>)))')
      ->defaults(array(
        'controller' => 'welcome',
        'action'     => 'index',
      ));

Dit stelt de `default` route in voor een uri met het formaat `(<controller>(/<action>(/<id>)))`. De karakters omringd met `<>` zijn *keys* en de karakters omringd met `()` zijn optionele onderdelen van de uri. In dit geval is de gehele uri optioneel, zodat bij een lege uri de standaard controller en actie worden uitgevoerd wat ervoor zou zorgen dat de `Controller_Welcome` class wordt ingeladen en eventueel wordt de methode `action_index` aangeroepen om de request af te handelen.

Merk op dat in Kohana routes, alle karakters zijn toegestaan behalve `()<>` en de `/`, die hebben namelijk een speciale betekenis. In de standaard route wordt de "/" gebruikt als scheidingsteken, maar zolang de reguliere expressie logisch en doordacht is, kan je kiezen hoe je routes er laat uitzien.

### Folders

Om je controllers wat meer te gaan organiseren kan je ervoor kiezen om ze te plaatsen in subfolders. Een veel voorkomend geval is voor een backend van je website:

    Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
      ->defaults(array(
        'directory'  => 'admin',
        'controller' => 'home',
        'action'     => 'index',
      ));

Deze route vereist dat de uri moet beginnen met `admin` en dat de folder statisch wordt toegewezen aan `admin` in de standaard instellingen van de route. Een request naar `admin/users/create` zal nu de `Controller_Admin_Users` class laden en de methode `action_create` aanroepen.

### Patronen

Het Kohana route systeem gebruikt perl compatibele reguliere expressies in zijn vergelijkings proces. Standaar worden de *keys* (omringd door `<>`) vergeleken met `[a-zA-Z0-9_]++` maar je kan je eigen patronen definiëren voor elke key door een associatieve array mee te geven als extra argument aan [Route::set] met daarin de keys and patronen. We kunnen het vorige voorbeeld uitbreiden met een admin sectie en een filialen (affliates) sectie. Je kan deze in verschillende routes specificeren of je kan iets doen zoals dit:

    Route::set('sections', '<directory>(/<controller>(/<action>(/<id>)))',
      array(
        'directory' => '(admin|affiliate)'
      ))
      ->defaults(array(
        'controller' => 'home',
        'action'     => 'index',
      ));
      
Dit zorgt voor twee secties van uw site, 'admin' en 'affiliate', deze laten je toe om de controllers te organiseren in subfolders voor elk maar dat ze nog steeds blijven werken als de standaard route.

### Meer Route voorbeelden

Er zijn oneindig veel andere mogelijkheden voor routes. Hier zijn er enkele:

    /*
     * Authenticatie
     */
    Route::set('auth', '<action>',
      array(
        'action' => '(login|logout)'
      ))
      ->defaults(array(
        'controller' => 'auth'
      ));
      
    /*
     * Multi-formaat feeds
     *   452346/comments.rss
     *   5373.json
     */
    Route::set('feeds', '<user_id>(/<action>).<format>',
      array(
        'user_id' => '\d+',
        'format' => '(rss|atom|json)',
      ))
      ->defaults(array(
        'controller' => 'feeds',
        'action' => 'status',
      ));
    
    /*
     * Statische pagina's
     */
    Route::set('static', '<path>.html',
      array(
        'path' => '[a-zA-Z0-9_/]+',
      ))
      ->defaults(array(
        'controller' => 'static',
        'action' => 'index',
      ));
      
    /*
     * Je houdt niet van slashes?
     *   EditGallery:bahamas
     *   Watch:wakeboarding
     */
    Route::set('gallery', '<action>(<controller>):<id>',
      array(
        'controller' => '[A-Z][a-z]++',
        'action'     => '[A-Z][a-z]++',
      ))
      ->defaults(array(
        'controller' => 'Slideshow',
      ));
      
    /*
     * Vlug zoeken
     */
    Route::set('search', ':<query>', array('query' => '.*'))
      ->defaults(array(
        'controller' => 'search',
        'action' => 'index',
      ));

Routes worden vergeleken in de gespecifieerde volgorde dus wees er van bewust dat als je routes insteld nadat de modules zijn ingeladen, een module een route kan specifiëren dat voor een conflict zorgt met een route van jezelf. Dit is ook de reden waarom de standaard route als laatste wordt ingesteld, zodat zelfgeschreven routes eerst worden getest.

### Request Parameters

De directory, controller en action kunnen worden benaderd via de [Request] instantie op de volgende manieren:

    $this->request->action;
    Request::instance()->action;

Alle andere gespecifieerde keys in een route kunnen worden benaderd van binnenin de controller via:

    $this->request->param('key_name');
    
De [Request::param] methode heeft een optioneel tweede argument om een standaard waarde terug te geven indien de key niet is ingesteld door de route. Indien er geen argumenten worden gegeven, worden alle keys als teruggegeven als een associatieve array.

### Conventie

De gebruikelijke conventie is je eigen routes te plaatsen in het `MODPATH/<module>/init.php` bestand van je module als de routes bij een module horen, of gewoonweg te plaatsen in het `APPPATH/bootstrap.php` bestand boven de standaard route als de routes specifiek voor de applicatie zijn. Natuurlijk kunnen ze ook worden geimporteerd vanuit een extern bestand of zelfs dynamisch gegenereerd worden.

## URLs

Naast Kohana's sterke routing mogelijkheden zitten er ook enkele methodes in om URLs te genereren voor je routes' uris. Je kan je uris altijd specificeren als een string door gebruik te maken van [URL::site] om een volledige URL te maken:

    URL::site('admin/edit/user/'.$user_id);

Kohana biedt echter ook een methode om de URI genereren op basis van de route's definitie. Dit is zeer handig als je routing ooit zou veranderen omdat het je zou verlossen van om overal uw code te veranderen waar je de URI als string hebt gespecificeerd. Hier is een voorbeeld van dynamische generatie die overeenkomt met het `feeds`-route voorbeeld van hierboven:

    Route::get('feeds')->uri(array(
      'user_id' => $user_id,
      'action' => 'comments',
      'format' => 'rss'
    ));

Laten we zeggen dat je later zou besluiten om die route definitie meer verstaanbaar te maken door ze te veranderen in `feeds/<user_id>(/<action>).<format>`. Wanneer je je code hebt geschreven met de uri generatie methode van hierboven dan zal je niets moeten veranderen aan je code! Wanneer een deel van de URI tussen haakjes staat en waarvoor er geen waarde is meegegeven voor uri generatie en er geen standaard waarde is meegegeven in de route, dan zal dat stuk verwijderd worden van de uri. Een voorbeeld hiervan is het `(/<id>)` deel van de standaard route, dit zal niet worden opgenomen in de gegenereerde uri als er geen id is voorzien.

De methode [Request::uri] zal er één zijn dat je regelmatig zult gebruiken, het heeft dezelfde functionaliteit als hierboven maar het gaat gebruikt de huidige route, directory, controller en action. Als onze huidige route de standaard route is en de uri `users/list` is, dan kunnen we het volgende doen om uris te genereren in het formaat `users/view/$id`:

    $this->request->uri(array('action' => 'view', 'id' => $user_id));

Of een meer aangeraden methode voor in een view:

    Request::instance()->uri(array('action' => 'view', 'id' => $user_id));

## Links

[!!] Nog geen informatie beschikbaar.
