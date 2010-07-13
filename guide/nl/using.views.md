# Het gebruik van Views

Views zijn bestanden die de visuele informatie bevatten voor je applicatie. Dit is meestal HTML, CSS en Javascript maar kan van alles zijn die je nodig hebt zoals XML of JSON voor AJAX output. Het doel van views is om deze informatie af te scheiden van de applicatie logica zodat je nettere code hebt en deze gemakkelijker kunt hergebruiken.

Hoewel dit waar is, kunnen views zelf ook code bevatten die je gebruikt om gegevens te tonen die je meegestuurd hebt met de view. Bijvoorbeeld, het loopen door een array met producten en voor elk product een nieuwe tabelrij tonen. Views zijn nog altijd PHP bestanden dus kan je erin coderen zoals je normaal zou doen.

# Aanmaken van View bestanden

De View bestanden worden opgeslagen in de `views` folder van het [bestandssysteem](about.filesystem). Je kan ook subfolders aanmaken in de `views` folder om je bestanden meer te organiseren. Alle mogelijkheden uit de volgende voorbeelden zijn goed:

    APPPATH/views/home.php
    APPPATH/views/pages/about.php
    APPPATH/views/products/details.php
    MODPATH/error/views/errors/404.php
    MODPATH/common/views/template.php

## Inladen van Views

[View] objecten worden gewoonlijk aangemaakt binnenin een [Controller] via de [View::factory] methode. De view wordt dan gewoonlijk aan de [Request::$response] property toegewezen of aan een andere view.

    public function action_about()
    {
        $this->request->response = View::factory('pages/about');
    }

Wanneer een view wordt toegewezen aan de [Request::$response], zoals in bovenstaand voorbeeld, dan zal het automatisch worden gerenderd wanneer noodzakelijk. Om het gerenderde resultaat van een view te verkrijgen kan je de [View::render] methode aanspreken of gewoon laten casten naar een string. Wanneer een view gerenderd is, wordt de view ingeladen en wordt de HTML gegenereerd.

    public function action_index()
    {
        $view = View::factory('pages/about');

        // View wordt gerenderd
        $about_page = $view->render();

        // Of gewoon laten casten naar een string
        $about_page = (string) $view;

        $this->request->response = $about_page;
    }

## Variabelen in Views

Eenmaal een view is ingeladen, kunnen variabelen eraan toegewezen worden door de [View::set] en [View::bind] methodes.

    public function action_roadtrip()
    {
        $view = View::factory('user/roadtrip')
            ->set('places', array('Rome', 'Paris', 'London', 'New York', 'Tokyo'));
            ->bind('user', $this->user);

        // De view zal de variabelen $places en $user hebben
        $this->request->response = $view;
    }

[!!] Het enige verschil tussen `set()` en `bind()` is dat `bind()` de variabele toewijst via referentie. Als je een variabele `bind()` vooraleer ze gedefineerd is, zal de variable als `NULL` worden gecreëerd.

### Globale Variabelen

Een applicatie kan verschillende views hebben die toegang hebben tot dezelfde variabelen. Bijvoorbeeld, een titel van een pagina wil je zowel tonen in de header van je template als in de body van de pagina inhoud. Je kan variabelen creëren dat toegankelijk zijn in elke view dankzij de [View::set_global] en [View::bind_global] methoden.

    // Wijs $page_title toe aan alle views
    View::bind_global('page_title', $page_title);

Als de applicatie drie views heeft die gerenderd zijn voor de home-pagina: `template`, `template/sidebar` en `pages/home`. Eerst zal je een abstracte controller maken om de template te maken:

    abstract class Controller_Website extends Controller_Template {

        public $page_title;

        public function before()
        {
            parent::before();

            // Maak $page_title toegankelijk in alle views
            View::bind_global('page_title', $this->page_title);

            // Laad $sidebar in de template als een view
            $this->template->sidebar = View::factory('template/sidebar');
        }

    }

Dan moet de home controller de `Controller_Website` uitbreiden:

    class Controller_Home extends Controller_Website {

        public function action_index()
        {
            $this->page_title = 'Home';

            $this->template->content = View::factory('pages/home');
        }

    }

## Views in Views

Als je een andere view wilt gebruiken in een view heb je twee keuzes. Door [View::factory] aan te roepen kan je de opgenomen view sandboxen. Dit betekent dat je alle variabelen moet meegeven aan de view door middel van [View::set] of [View::bind]:

    // Enkel de $user variabele zal toegankelijk zijn in "views/user/login.php"
    <?php echo View::factory('user/login')->bind('user', $user) ?>

De andere optie is om de view rechtstreeks in te voegen, dat maakt alle huidige variabelen beschikbaar in de ingesloten view:

    // Elke variabele gedefinieerd in deze view zal worden ingesloten in "views/message.php"
    <?php include Kohana::find_file('views', 'user/login') ?>

Natuurlijk kan je ook een volledige [Request] inladen in een view:

    <?php echo Request::factory('user/login')->execute() ?>

Dit is een voorbeeld van [HMVC](about.mvc), dit maakt het mogelijk om aanroepingen te maken en te lezen via andere URLs binnenin je applicatie.

# Upgraden van v2.x

In tegenstelling tot versie 2.x van Kohana, wordt de view niet ingeladen in de context van de [Controller], dus is het niet mogelijk om `$this` aan te spreken als controller binnenin de view. De controller doorgeven aan de view moet nu expliciet worden gedaan:

    $view->bind('controller', $this);
