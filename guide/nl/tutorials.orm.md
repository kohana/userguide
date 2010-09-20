# ORM {#top}

Kohana 3.0 bevat een krachtige ORM-module die het "active record"-patroon gebruikt en database introspectie gebruikt om kolominformatie te bepalen van een model.

De ORM-module is opgenomen in de Kohana 3.0 installatie maar moet worden ingeschakeld vooraleer je het kunt gebruiken. In je `application/bootstrap.php` bestand moet je de oproen naar [Kohana::modules] aanpassen en de ORM-module insluiten:

	Kohana::modules(array(
		...
		'orm' => MODPATH.'orm',
		...
	));

## Configuratie {#configuration}

ORM vergt weinig configuratie om aan de slag te kunnen. Breid uw model classes uit met ORM om de module te kunnen gebruiken:

	class Model_User extends ORM
	{
		...
	}

In het voorbeeld hierboven zal het model zoeken naar een tabel `users` in de standaard database.

### Model Configuratie Properties

De volgende eigenschappen worden gebruikt om ieder model te configureren:

Type      | Eigenschap          |  Omschrijving                        | Standaard waarde
----------|---------------------|--------------------------------------| -------------------------
`string`  |  _table_name        | Tabelnaam om te gebruiken            | `singular model name`
`string`  | _db                 | Naam van de database om te gebruiken | `default`
`string`  | _primary_key        | Kolom die dient als primary key      | `id`
`string`  | _primary_val        | Kolom die dient als primary value    | `name`
`bool`    | _table_names_plural | Zijn de tabelnamen meervoudig?       | `TRUE`
`array`   | _sorting            | Array met kolom => volgorde          | `primary key => ASC`
`string`  | _foreign_key_suffix | Achtervoegsel voor foreign keys      | `_id`

## Het gebruik van ORM

### Een Record inladen

Om een instantie van een model aan te maken, kan je de [ORM::factory] methode of [ORM::__construct] gebruiken:

	$user = ORM::factory('user');
	// of
	$user = new Model_User();

De constructor en factory methodes accepteren ook een primary key waarde om het gegeven model's data in te laden:

	// Laad gebruiker met ID 5
	$user = ORM::factory('user', 5);

	// Kijk of de gebruiker succesvol werd ingeladen
	if ($user->loaded()) { ... }

Je kan optioneel een array met keys => value paren meegeven om een data object in te laden die voldoet aan de gegeven criteria:

	// Laad een gebruiker met email joe@example.com
	$user = ORM::factory('user', array('email' => 'joe@example.com'));

### Zoeken naar één of meerdere records

ORM ondersteunt de meeste krachtige [Database] methoden voor het doorzoeken van gegevens van uw model. Zie de `_db_methods` eigenschap voor een volledige lijst van ondersteunde methode oproepen. Records worden opgehaald met behulp van de [ORM::find] en [ORM::find_all] functies.

	// Dit zal de eerste actieve gebruiker nemen met de naam Bob
	$user = ORM::factory('user')
		->where('active', '=', TRUE)
		->where('name', '=', 'Bob')
		->find();

	// Dit zal alle gebruikers nemen met de naam Bob
	$users = ORM::factory('user')
		->where('name', '=', 'Bob')
		->find_all();

Wanneer je een lijst van modellen ontvangt met behulp van [ORM::find_all], kan je deze doorlopen zoals je doet met database resultaten:

	foreach ($users as $user)
	{
		...
	}

Een zeer handige functie van ORM is de [ORM::as_array] methode die het record zal teruggeven als een array. Indien je dit gebruikt met [ORM::find_all], zal een array van alle records worden teruggegeven. Een goed voorbeeld van wanneer dit nuttig is, is voor select in het HTML formulier:

	// Toon een dropdown/select met daarin alle gebruikersnamen (id als value van de options)
	echo Form::select('user', ORM::factory('user')->find_all()->as_array('id', 'username'));

### Het aantal records tellen

Gebruik [ORM::count_all] om het aantal records terug te geven voor een bepaalde query.

	// Aantal actieve gebruikers
	$count = ORM::factory('user')->where('active', '=', TRUE)->count_all();

Als je het totaal aantal gebruikers wilt tellen voor een bepaalde query, terwijl je enkel een bepaalde set van deze gebruikers wilt tonen, gebruik dan de [ORM::reset] methode met `FALSE` vooraleer je `count_all` gebruikt:

	$user = ORM::factory('user');

	// Totaal aantal gebruikers (reset FALSE zorgt ervoor dat het query object dat het query object niet geleegd wordt)
	$count = $user->where('active', '=', TRUE)->reset(FALSE)->count_all();

	// Geef enkel de eerste 10 resultaten terug van deze resultaten
	$users = $user->limit(10)->find_all();

### Properties van een model aanspreken

Alle model properties zijn toegankelijk via de `__get` en `__set` magic methodes. 

	$user = ORM::factory('user', 5);
	
	// Geef de gebruikersnaam terug
	echo $user->name;

	// Verander de gebruiker zijn naam
	$user->name = 'Bob';

Voor het opslaan van gegevens/properties die niet bestaan in de tabel van het model, kan je gebruik maken van het `_ignored_columns` data member. De gegevens zullen worden opgeslagen in het interne `_object` member, maar zal worden genegeerd op database-niveau.

	class Model_User extends ORM
	{
		...
		protected $_ignored_columns = array('field1', 'field2', …);
		...
	}

Meerdere key => value paren kunnen worden ingesteld door gebruik te maken van de [ORM::values] methode.

	$user->values(array('username' => 'Joe', 'password' => 'bob'));

### Aanmaken en opslaan van records

De methode [ORM::save] wordt gebruikt om zowel nieuwe records aan te maken als het upaten van bestaande.

	// Nieuw record aanmaken
	$user = ORM::factory('user');
	$user->name = 'Nieuwe gebruiker';
	$user->save();

	// Aanpassen van een bestaand record
	$user = ORM::factory('user', 5);
	$user->name = 'Gebruiker 2';
	$user->save();

	// Controleer of het record opgeslagen is
	if ($user->saved()) { ... }

Je kan meerdere records tegelijk veranderen met de [ORM::save_all] methode:

	$user = ORM::factory('user');
	$user->name = 'Bob';

	// Verander bij alle actieve gebruikers de naam naar 'Bob'
	$user->where('active', '=', TRUE)->save_all();

#### Gebruik `Updated` en `Created` kolommen

De `_updated_column` en `_created_column` members staan ter beschikking om automatisch aangepast te worden wanneer een model wordt gecreëerd of aangepast. Ze worden standaard niet gebruikt. Om ze te gebruiken:

	// date_created is de kolom die wordt gebruikt om de aanmaak datum op te slaan. Gebruik format => TRUE om een timestamp op te slaan
	protected $_created_column = array('date_created', 'format' => TRUE);

	// date_modified is de kolom die wordt gebruikt om de datum op te slaan wanneer het item is aangepast. In dit geval wordt een string gebruikt om een date() formaat te specificeren
	protected $_updated_column = array('date_modified', 'format' => 'm/d/Y');

### Verwijderen van records

Records worden verwijderd met [ORM::delete] en [ORM::delete_all]. Deze methoden werken op dezelfde manier als het opslaan van records zoals hierboven beschreven, met de uitzondering dat [ORM::delete] nog een optionele parameter heeft, het `id` van het record om te verwijderen. Anders wordt het huidig ingeladen record verwijderd.

### Relaties

ORM ondersteunt zeer goed relateies. Ruby heeft een [goede tutorial omtrent relaties](http://api.rubyonrails.org/classes/ActiveRecord/Associations/ClassMethods.html).

#### Belongs-To en Has-Many

We gaan er van uit dat we werken met een school dat veel (has many) studenten heeft. Iedere student kan enkel maar tot één school behoren (belong to). Dan zullen de relaties  als volgt gedefinieerd worden:

	// In het model "school"
	protected $_has_many = array('students' => array());

	// In het model "student"
	protected $_belongs_to = array('school' => array());

Om een student zijn school te verkrijgen gebruik je:

	$school = $student->school;

Om een school zijn studenten te verkrijgen gebruik je:

	// Merk op dat find_all is vereist na "students"
	$students = $school->students->find_all();

	// Om resultaten te "filteren":
	$students = $school->students->where('active', '=', TRUE)->find_all();

Standaard zal ORM willen zoeken naar een `school_id` model in de studenten tabel. Dit kan worden overschreven door gebruik te maken van het `foreign_key` attribuut:

	protected $_belongs_to = array('school' => array('foreign_key' => 'schoolID'));
	
De foreign key moet overschreven worden in zowel het student als school model.

#### Has-One

Has-One is een speciale versie van Has-Many, het enige verschil is dat er maar één enkel record is. In het bovenstaande voorbeeld zou iedere school maar één student hebben (al is dat wel een slecht voorbeeld).

	// In het model "school"
	protected $_has_one = array('student' => array());

Je moet niet zoals bij Belongs-To de `find` methode gebruiken wanneer je verwijst naar een het Has-One gerelateerd object, dit gebeurt automatisch.

#### Has-Many "Through"

De Has-Many "through" relatie (ook bekend als Has-And-Belongs-To-Many) wordt gebruikt in het geval dat één object gerelateerd is met meerdere objecten van verschillende types en omgekeerd. Bijvoorbeeld, een student kan verschillende klassen volgen en een klass kan verschillende studenten hebben. In dit geval wordt een derde tabel gebruikt en een model die dienst doet als `pivot`. In dit geval noemen we het pivot object/model `enrollment` (=inschrijving).

	// In het model "student"
	protected $_has_many = array('classes' => array('through' => 'enrollment'));

	// In het model "class"
	protected $_has_many = array('students' => array('through' => 'enrollment'));

De inschrijvingstabel (`enrollment`) moet twee foreign keys hebben, een voor `class_id` en de andere voor `student_id`. Deze kunnen worden overschreven door `foreign_key` en `far_key` te gebruiken bij het definiëren van de relatie. Bijvoorbeeld:

	// In het model "student" (de foreign key verwijst naar dit model [student], terwijl de far key verwijst naar het andere model [class])
	protected $_has_many = array('classes' => array('through' => 'enrollment', 'foreign_key' => 'studentID', 'far_key' => 'classID'));

	// In het model "class"
	protected $_has_many = array('students' => array('through' => 'enrollment', 'foreign_key' => 'classID', 'far_key' => 'studentID'));

Het inschrijvings model (enrollment) zal als volgt gedefinieerd worden:

	// Het model "enrollment" hoort bij zowel "student" als "class"
	protected $_belongs_to = array('student' => array(), 'class' => array());

Om de gerelateerde objecten te bereiken, gebruik je:

	// Om de klassen van een student te verkrijgen
	$student->classes->find_all();

	// Om studenten te verkrijven vanuit de klas
	$class->students->find_all();

### Validatie
	
ORM werkt nauw samen met de [Validate] library. ORM biedt de volgende members aan voor validatie

* _rules
* _callbacks
* _filters
* _labels

#### `_rules`
	
	protected $_rules = array
	(
		'username' => array('not_empty' => array()),
		'email'    => array('not_empty' => array(), 'email' => array()),
	);

`username` zal gecontroleerd worden om zeker niet leeg te zijn. `email` zal ook gecontroleerd worden om te verzekeren dat het een geldig emailadres is. De lege arrays die als values worden meegestuurd, kunnen worden gebruikt om optionele parameters mee te geven aan deze functie aanroepen.

#### `_callbacks`
	
	protected $_callbacks = array
	(
		'username' => array('username_unique'),
	);

`username` zal worden meegestuurd naar een callback methode `username_unique`. Als de methode bestaat in het huidige model, zal het worden gebruikt, anders zal een globale functie worden opgeroepen. Hier is een voorbeeld van z'n methode:

	public function username_unique(Validate $data, $field)
	{
		// Logica om te controleren of de gebruikersnaam uniek is
		...
	}

#### `_filters`

	protected $_filters = array
	(
		TRUE       => array('trim' => array()),
		'username' => array('stripslashes' => array()),
	);

`TRUE` slaat erop dat de `trim` filter wordt gebruikt voor alle velden. `username` zal ook gefilterd worden door `stripslashes` vooraleer het gevalideerd wordt. De lege arrays die als values worden meegestuurd, kunnen worden gebruikt om optionele parameters mee te geven aan deze filter-functie aanroepen.
	
#### Controleren of een Object Valid is

Gebruik [ORM::check] om te kijken of het object momenteel valid is.

	// Een object zijn values instellen en dan controleren of het valid is
	if ($user->values($_POST)->check())
	{
		$user->save();
	}

Je kan de `validate()` methode gebruiken om een model zijn validatie object aan te roepen.

	// Een optionele filter manueel toevoegen
	$user->validate()->filter('username', 'trim');