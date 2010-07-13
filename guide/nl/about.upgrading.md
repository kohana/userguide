# Upgraden vanaf 2.3.x

De code van Kohana v3 werkt grotendeels anders dan Kohana 2.3, hier is een lijst van de meeste valkuilen en tips om succesvol te upgraden.

## Naming conventies

In 2.x versies onderscheiden de verschillende soorten van classes (zoals controller, model, ...) zich met elkaar met behulp van achtervoegsels. Mappen in de model / controller mappen hadden geen invloed op de naam van de class.

In 3.0 werd aanpak geschrapt ten gunste van de Zend framework bestandssysteem conventies, waar de naam van de class het pad is naar de class zelf, gescheiden door een underscore in plaats van slashes (dus `/some/class/file.php` bekomt `Some_Class_File`).
Zie de [conventies documentatie](start.conventions) voor meer informatie.

## Input Library

De Input Library is verwijderd in 3.0, er wordt nu aanbevolen om gewoon `$_GET` en `$_POST` te gebruiken.

### XSS Protectie

Als je invoer van gebruikers wilt filteren op XSS kan je [Security::xss_clean] gebruiken om:

	$_POST['description'] = security::xss_clean($_POST['description']);

Je kan ook altijd [Security::xss_clean] gebruiken als filter met de [Validate] library:

	$validation = new Validate($_POST);
	
	$validate->filter('description', 'Security::xss_clean');

### POST & GET

Eén van de grootste functies van de Input Library was als je probeerde een waarde uit een superglobale array te halen en deze bestond bestond niet, dan zou de Input Library een standaard waarde teruggeven dat je kon instellen:

	$_GET = array();
	
	// $id heeft de waarde 1 gekregen
	$id = Input::instance()->get('id', 1);
	
	$_GET['id'] = 25;
	
	// $id heeft de waarde 25 gekregen
	$id = Input::instance()->get('id', 1);

In 3.0 kan je deze functionaliteit nabootsen door [Arr::get] te gebruiken:

	$_GET = array();
	
	// $id heeft de waarde 1 gekregen
	$id = Arr::get($_GET, 'id', 1);
	
	$_GET['id'] = 42;
	
	// $id heeft de waarde 42 gekregen
	$id = Arr::get($_GET, 'id', 1);

## ORM Library

Er zijn redelijk veel grote wijzingingen aangebracht in ORM sedert 2.3. Hier is een lijst met de meest voorkomende upgrade problemen.

### Member variablen

Alle member variablen hebben nu een voorvoegsel gekregen met een underscore (_) en zijn niet langer bereikbaar via `__get()`. Nu moet je een functie aanroepen met de naam van de property zonder de underscore.

Bijvoorbeeld, in 2.3 had je `loaded` en in 3.x is dat nu `_loaded` en heb je nu toegang van buiten de class via `$model->loaded()`.

### Relaties

Als je in 2.3 de gerelateerde objecten van een model wilde herhalen, kon je dat zo doen:

	foreach($model->{relation_name} as $relation)

Maar in 3.0 zal dit niet werken. In de 2.3 serie werden alle queries die gegenereerd werden met behulp van de Database Library gegeneeerd in een globale omgeving, wat betekent dat je niet kon proberen en maken van twee queries. Bijvoorbeeld:

# TODO: GOED VOORBEELD!!!!

Deze query zou mislukken doordat de tweede, inner query alle voorwaarden zou overerven van de eerste, wat zou leiden tot het mislukken.
In 3.0 is dit aangepast door iedere query te laten genereren in zijn eigen omgeving. Let wel dat sommige dingen hierdoor niet gaan werken zoals je verwacht. Bijvoorbeeld:

	foreach(ORM::factory('user', 3)->where('post_date', '>', time() - (3600 * 24))->posts as $post)
	{
		echo $post->title;
	}

[!!] (Zie [de Database tutorial](tutorials.databases) voor de nieuwe query syntax)

In 2.3 zou je verwachten dat dit iterator teruggeeft van alle berichten van een gebruiker met `id` 3 met een `post_date` binnenin de 24 uren, maar in de plaats daarvan zal de WHERE conditie toegepast worden op het user-model en een `Model_Post` worden teruggevens met de joining conditities gespecifieerd.

Om hetzelfde effect te verkrijgen zoals in 2.3, moet je de structuur iets aanpassen:

	foreach(ORM::factory('user', 3)->posts->where('post_date', '>', time() - (36000 * 24))->find_all() as $post)
	{
		echo $post->title;
	}

Dit is ook van toepassing op de `has_one` relaties:

	// Niet correct
	$user = ORM::factory('post', 42)->author;
	// Correct
	$user = ORM::factory('post', 42)->author->find();

### Has and belongs to many relaties

In 2.3 kon je `has_and_belongs_to_many` relaties specifieren. In 3.0 is deze functionaliteit herschreven naar `has_many` *through*.

In het model definieer je een `has_many` relatie met een ander model maar dan voeg je nog een `'through' => 'table'` attribuut aan toe, waar `'table'` de naam is van de trough tabel. Bijvoorbeeld (in de context van posts<>categories):

	$_has_many = array
	(
		'categories' => 	array
							(
								'model' 	=> 'category', // The foreign model
								'through'	=> 'post_categories' // The joining table
							),
	);

Als je Kohana hebt opgezet om een tabel voorvoegsel te gebruiken, dan hoef je geen zorgen te maken om dit voorvoegsel hier te gebruiken bij de tabelnaam.

### Foreign keys

Als je in Kohana 2.x's ORM een foreign key wilde overschrijven moest je de relatie specificeren waaraan het toebehoorde, en de nieuwe foreign key instellen in de member variabele `$foreign_keys`.

In 3.0 moet je nu een `foreign_key` definiëren in de relatie-definitie, zoals hier:

	Class Model_Post extends ORM
	{
		$_belongs_to = 	array
						(
							'author' => array
										(
											'model' 		=> 'user',
											'foreign_key' 	=> 'user_id',
										),
						);
	}

In dit voorbeeld zouden we een `user_id` veld moeten hebben in de tabel posts.



In has_many relaties is de `far_key` het veld in de trough tabel die linkt naar de foreign tabel en is de `foreign key` het veld in de trough tabel die "this" model's tabel linkt naar de trough table.

Stel je de volgende opstelleing voor: "Posts" hebben en behoren tot vele "Categories" via `posts_sections` ("Posts" have and belong to many "Categories" through `posts_sections`)

| categories | posts_sections 	| posts   |
|------------|------------------|---------|
| id		 | section_id		| id	  |
| name		 | post_id			| title   |
|			 | 					| content |

		Class Model_Post extends ORM
		{
			protected $_has_many = 	array(
										'sections' =>	array(
															'model' 	=> 'category',
															'through'	=> 'posts_sections',
															'far_key'	=> 'section_id',
														),
									);
		}
		
		Class Model_Category extends ORM
		{
			protected $_has_many = 	array (
										'posts'		=>	array(
															'model'			=> 'post',
															'through'		=> 'posts_sections',
															'foreign_key'	=> 'section_id',
														),
									);
		}


Uiteraard is de aliasing setup hier een beetje gek, onnodig, maar het is een goed voorbeeld om te tonen hoe het foreign/far key systeem werkt.

### ORM Iterator

Het is ook best te melden dat `ORM_Iterator` nu herschreven is naar `Database_Result`.

Als je een array van ORM objecten met hun keys als index van de array wilt verkrijgen, moet je [Database_Result::as_array] gebruiken, bijvoorbeeld:


		$objects = ORM::factory('user')->find_all()->as_array('id');

Waar `id` de primary key is in de user tabel.

## Router Library

In versie 2 was er een Router library die de main request afhandelde. Het liet je de basisroutes instellen in het `config/routes.php` bestand en het liet je toe om zelfgeschreven regex te gebruiken voor routes, maar het was niet echt flexibel als je iets radicaal wou veranderen.

## Routes

Het routing systeem (nu wordt verwezen naar het request systeem) is een stuk flexibeler in 3.0. Routes zijn nu gedefinieerd in het boostrap bestand (`application/bootstrap.php`) en de de module's init.php (`modules/module_name/init.php`). Het is ook interessant te weten dat routes worden geëvalueerd in de volgorde dat ze worden gedefinieerd. In plaats daarvan specifieer je een patroon voor elke uri, je kan variabelen gebruiken om segmenten aan te duiden (zoals een controller, methode, id).

Bijvoorbeeld, in 2.x zouden deze regexes:

	$config['([a-z]+)/?(\d+)/?([a-z]*)'] = '$1/$3/$1';

de uri `controller/id/method` linken aan `controller/method/id`. In 3.0 gebruik je dit:

	Route::set('reversed','(<controller>(/<id>(/<action>)))')
			->defaults(array('controller' => 'posts', 'action' => 'index'));

[!!] Iedere uri moet een unieke naam hebben (in dit geval `reversed`), de reden hiervoor wordt nader uitgelegd in de [url tutorial](tutorials.urls).

Slashes geven dynamische secties weer die zouden moeten worden ontleed in variabelen. Haakjes geven optionele secties aan die niet vereist zijn. Als je met een route enkel uris die beginnen met admin wilt aanspreken kan je dit gebruiken:

	Rouse::set('admin', 'admin(/<controller>(/<id>(/<action>)))');

En als je wilt een dat een gebruiker een controller specificeert:

	Route::set('admin', 'admin/<controller>(/<id>(/<action>))');

Kohana maakt geen gebruik van `default defaults`. Als je wilt dat Kohana ervan uit gaat dat de standaard actie 'index' is, dan moet je dat ook zo instellen! Dit kan je doen via [Route::defaults]. Als je zelfgeschreven regex wilt gebruiken voor uri segmenten dan moet je ene array met `segment => regex` meegeven, bijvoorbeeld:

	Route::set('reversed', '(<controller>(/<id>(/<action>)))', array('id' => '[a-z_]+'))
			->defaults(array('controller' => 'posts', 'action' => 'index'))

Dit zou de `id` waarde forceren om te bestaan uit kleine letters van a tot z en underscores.

### Actions

Nog één ding dat belangrijk is om te melden, is dat methoden in een controller die toegankelijk moeten zijn via een url nu "actions" worden genoemd. Ze krijgen een voorvoegsel 'action_'. Bijvoorbeeld in het bovenstaande voorbeeld, als de user de url `admin/posts/1/edit` aanroept dan is de actie `edit` maar is de methode die wordt aangeroepen in de controller `action_edit`.  Zie de [url tutorial](tutorials.urls) voor meer informatie.

## Sessies

De volgende methoden worden niet meer ondersteund: Session::set_flash(), Session::keep_flash() or Session::expire_flash(), inde plaats daarvan gebruik je nu [Session::get_once].

## URL Helper

Er zijn maar een aantal kleinere dingen veranderd in de url helper. `url::redirect()` werd vervangen door `$this->request->redirect()` (binnenin controllers) en `Request::instance()->redirect()`.

`url::current` werd nu vervangen door `$this->request->uri()` 

## Valid / Validation

Deze twee classes zijn nu samengevoegd in één enkele class met de naam `Validate`.

De syntax om arrays te valideren is een klein beetje gewijzigd:

	$validate = new Validate($_POST);
	
	// Pas een filter toe op alle waarden in de array
	$validate->filter(TRUE, 'trim');
	
	// Om enkel rules te definiëren gebruik je rule()
	$validate
		->rule('field', 'not_empty')
		->rule('field', 'matches', array('another_field'));
	
	// Om meerdere rules te definiëren voor een veld gebruik je rules(), je geeft een array mee met `passing an array of rules => params als tweede argument
	$validate->rules('field', 	array(
									'not_empty' => NULL,
									'matches'	=> array('another_field')
								));

De 'required' rule is ook verandert van naam. Nu wordt voor de duidelijkheid 'not_empty' gebruikt.

## View Library

Er zijn enkele kleine wijzigingen aangebracht aan de View library die de moeite zijn om even te melden.

In 2.3 werden views gegenereerd binnenin de scope van de controller, dit liet je toe om `$this` te gebruiken als referentie naar de controller vanuit je view, dit is verandert in 3.0. Views worden nu gegenereerd in een lege scope. Als je nog `$this` wilt gebruiken in je view, moet je een referentie leggen via [View::bind]: `$view->bind('this', $this)`.

Het moet wel gezegd worden dat dit een *erg* slechte manier van werken is omdat het je view koppelt aan de controller wat tegenhoud om deze view opnieuw te gebruiken. Het is aan te raden om de noodzakelijke variabelen voor je view als volgt door te sturen:

	$view = View::factory('my/view');
	
	$view->variable = $this->property;
	
	// OF als je dit wilt "chainen"
	
	$view
		->set('variable', $this->property)
		->set('another_variable', 42);
		
	// NIET aangeraden
	$view->bind('this', $this);

Omdat de view gegenereerd wordt in een lege scope, is `Controller::_kohana_load_view` nu overtollig. Als je de view moet aanpassen vooraleer het word gegenereerd (bijvoorbeeld om een menu te gereneren over de gehele site) kan je [Controller::after] gebruiken.

	Class Controller_Hello extends Controller_Template
	{
		function after()
		{
			$this->template->menu = '...';
			
			return parent::after();
		}
	}
