# Databases {#top}

Kohana 3.0 heeft een goede module ingebouwd om te kunnen werken met databases. Standaard ondersteund de database module drivers voor [MySQL](http://php.net/mysql) en [PDO](http://php.net/pdo).

De database module zit bij de Kohana 3.0 installatie maar het moet nog worden ingesteld vooraleer je het kan gebruiken. In je `application/bootstrap.php` bestand moet je de aanroep naar [Kohana::modules] aanpassen en de database module eraan toevoegen:

    Kohana::modules(array(
        ...
        'database' => MODPATH.'database',
        ...
    ));

## Configuratie {#configuration}

Nadat de module is ingesteld moet je een configuratie bestand aanmaken zodat de module weet hoe het moet connecteren met je database. Een voorbeeld configuratie bestand kan je vinden in `modules/database/config/database.php`.

De structuur van een database configuratie groep, genoemd "instantie", ziet er als volgt uit:

    string INSTANCE_NAME => array(
        'type'         => string DATABASE_TYPE,
        'connection'   => array CONNECTION_ARRAY,
        'table_prefix' => string TABLE_PREFIX,
        'charset'      => string CHARACTER_SET,
        'profiling'    => boolean QUERY_PROFILING,
    ),

[!!] Meerdere instanties van deze instellingen kunnen worden gedefinieerd binnen het configuratie bestand.

Het verstaan van elk van deze instellingen is belangrijk.

INSTANCE_NAME
:  Connecties kunnen elke naam hebben, maar je moet minstens één connectie hebben met de naam "default".

DATABASE_TYPE
:  Eén van de geïnstalleerde database drivers. Kohana heeft standaard de "mysql" en "pdo" drivers.

CONNECTION_ARRAY
:  Specifieke driver opties om te connecteren naar je database. (Driver opties worden uitgelegd [beneden](#connection_settings).)

TABLE_PREFIX
:  Voorvoegsel dat wordt toegevoegd aan al je tabelnamen door de [query builder](#query_building).

QUERY_PROFILING
:  Zet [profiling](debugging.profiling) aan van database queries.

### Voorbeeld

Het voorbeeld bestand hieronder toont 2 MySQL connecties, een lokale en één op afstand (=remote).

    return array
    (
        'default' => array
        (
            'type'       => 'mysql',
            'connection' => array(
                'hostname'   => 'localhost',
                'username'   => 'dbuser',
                'password'   => 'mijnwachtwoord',
                'persistent' => FALSE,
                'database'   => 'mijn_db_naam',
            ),
            'table_prefix' => '',
            'charset'      => 'utf8',
            'profiling'    => TRUE,
        ),
        'remote' => array(
            'type'       => 'mysql',
            'connection' => array(
                'hostname'   => '55.55.55.55',
                'username'   => 'remote_user',
                'password'   => 'mijnwachtwoord',
                'persistent' => FALSE,
                'database'   => 'mijn_remote_db_naam',
            ),
            'table_prefix' => '',
            'charset'      => 'utf8',
            'profiling'    => TRUE,
        ),
    );

### Connectie instellingen {#connection_settings}

Iedere database driver heeft verschillende connectie instellingen.

#### MySQL

Een MySQL database accepteert de volgende opties in de `connection` array:

Type      | Optie      |  Omschrijving              | Standaard Waarde
----------|------------|----------------------------| -------------------------
`string`  | hostname   | Hostname van de database   | `localhost`
`integer` | port       | Poort nummer               | `NULL`
`string`  | socket     | UNIX socket                | `NULL`
`string`  | username   | Database gebruikersnaam    | `NULL`
`string`  | password   | Database wachtwoord        | `NULL`
`boolean` | persistent | Persistente connecties     | `FALSE`
`string`  | database   | Database naam              | `kohana`

#### PDO

Een PDO database accepteert de volgende opties in de `connection` array:

Type      | Optie      |  Omschrijving              | Standaard Waarde
----------|------------|----------------------------| -------------------------
`string`  | dsn        | PDO data source identifier | `localhost`
`string`  | username   | Database gebruikersnaam    | `NULL`
`string`  | password   | Database wachtwoord        | `NULL`
`boolean` | persistent | Persistente connecties     | `FALSE`

[!!] Als je PDO gebruikt en je bent niet zeker wat je moet gebruiken voor de `dsn` optie, bekijk dan [PDO::__construct](http://php.net/pdo.construct).

## Connecties en Instanties {#connections}

Iedere configuratie groep verwijst naar een database instantie. Iedere instantie kan worden aangesproken via [Database::instance]:

    $default = Database::instance();
    $remote  = Database::instance('remote');

Om de database los te koppelen, moet je gewoonweg het object vernietigen:

    unset($default, Database::$instances['default']);

Om all database instanties in één keer los te koppelen, gebruik je:

    Database::$instances = array();

## Het maken van Queries {#making_queries}

Er zijn twee verschillende manieren om queries te maken. De eenvoudigste manier om een query te maken is het gebruik van [Database_Query], via [DB::query]. Deze queries worden "prepared statements" genoemd en laat je toe om query parameters instellen die automatisch worden "geescaped". De tweede manier om een query te maken is door deze op te bouwen met behulp van methode-aanroepen. Dit wordt gedaan met behulp van de [query builder](#query_building).

[!!] Alle queries worden uitgevoerd via de `execute` methode, deze verwacht een [Database] object of een instantienaam. Zie [Database_Query::execute] voor meer informatie.

### Prepared Statements

Het gebruik van prepared statements laat je toe om SQL queries manueel te schrijven terwijl de query waarden nog steeds automatisch worden "geescaped" om [SQL injectie](http://wikipedia.org/wiki/SQL_Injection) te voorkomen. Een query aanmaken is relatief gemakkelijk:

    $query = DB::query(Database::SELECT, 'SELECT * FROM users WHERE username = :user');

De [DB::query] factory methode creëert een nieuwe [Database_Query] class voor ons, zodat "methode-chaining" mogelijk is. De query bevat een `:user` parameter, die we kunnen toewijzen aan een waarde:

    $query->param(':user', 'john');

[!!] Parameter namen kunnen elke string zijn aangezien worden vervangen via het gebruik van [strtr](http://php.net/strtr). Het wordt ten zeerste aanbevolen om **geen** dollar tekens te gebruiken als parameter namen om verwarring te voorkomen.

Als je de SQL wilt tonen dat zal worden uitgevoerd, moet je het object gewoonweg casten naar een string:

    echo Kohana::debug((string) $query);
    // Zou moeten tonen:
    // SELECT * FROM users WHERE username = 'john'

Je kan ook altijd de `:user` parameter aanpassen door de [Database_Query::param] opnieuw aan te roepen:

    $query->param(':user', $_GET['search']);

[!!] Indien je meerdere paramters in één keer wilt instellen kan je dat doen met [Database_Query::parameters].

Eénmaal je iets hebt toegewezen aan elke parameter, kan je de query uitvoeren:

    $query->execute();

Het is ook mogelijk om een parameter te "verbinden" met een variabele, door het gebruik van een [variabele referentie]((http://php.net/language.references.whatdo)). Dit kan extreem gebruikvol zijn wanneer je dezelfde query meerdere keren moet uitvoeren:

    $query = DB::query(Database::INSERT, 'INSERT INTO users (username, password) VALUES (:user, :pass)')
        ->bind(':user', $username)
        ->bind(':pass', $password);

    foreach ($new_users as $username => $password)
    {
        $query->execute();
    }

In het bovenstaand voorbeeld worden de variabelen `$username` en `$password` gewijzigd in iedere loop van het `foreach` statement. Wanneer de parameter verandert, veranderen infeite de `:user` en `:pass` query parameters. Het zorgvuldig gebruik van parameter binding kan een pak code besparen.

### Query Building {#query_building}

Het maken van dynamische queries via objecten en methodes zorgt ervoor dat queries zeer snel kunnen worden geschreven op een agnostische manier. Query building voegt ook identifier (tabel en kolom naam) en value quoting toe.

[!!] Op dit moment, is het niet mogelijk om query building te combineren met prepared statements.

#### SELECT

Elk type database query wordt vertegenwoordigd door een andere class, elk met hun eigen methoden. Bijvoorbeeld, om een SELECT-query te maken, gebruiken we [DB::select]:

    $query = DB::select()->from('users')->where('username', '=', 'john');

Standaard zal [DB::select] alle kolommen selecteren (`SELECT * ...`), maar je kan ook specificeren welke kolommen je wilt teruggeven:

    $query = DB::select('username', 'password')->from('users')->where('username', '=', 'john');

Neem nu een minuut de tijd om te kijken wat deze methode-keten doet. Eerst maken we een selectie object met behulp van [DB::select]. Vervolgens stellen we tabel(len) in door de `from` methode te gebruiken. Als laatste stap zoeken we voor specifieke records door gebruik te maken van de `where` methode. We kunnen de SQL tonen dat zal worden uitgevoerd door deze te casten naar een string:

    echo Kohana::debug((string) $query);
    // Zou moeten tonen:
    // SELECT `username`, `password` FROM `users` WHERE `username` = 'john'

Merk op hoe de kolom en tabel namen automatisch worden "geescaped", eveneens de waarden? Dit is een van de belangrijkste voordelen van het gebruik van de query builder.

Het is mogelijk om `AS` aliassen te maken wanneer je iets selecteert:

    $query = DB::select(array('username', 'u'), array('password', 'p'))->from('users');

Deze query zal de volgende SQL genereren:

    SELECT `username` AS `u`, `password` AS `p` FROM `users`

#### INSERT

Om records aan te maken in de database gebruik je [DB::insert] om een INSERT query aan te maken:

    $query = DB::insert('users', array('username', 'password'))->values(array('fred', 'p@5sW0Rd'));

Deze query zal de volgende SQL genereren:

    INSERT INTO `users` (`username`, `password`) VALUES ('fred', 'p@5sW0Rd')

#### UPDATE

Om een bestaande record aan te passen gebruik je [DB::update] om een UPDATE query aan te maken:

    $query = DB::update('users')->set(array('username' => 'jane'))->where('username', '=', 'john');

Deze query zal de volgende SQL genereren:

    UPDATE `users` SET `username` = 'jane' WHERE `username` = 'john'

#### DELETE

Om een bestaande record te verwijderen gebruik je [DB::delete] om een DELETE query aan te maken:

    $query = DB::delete('users')->where('username', 'IN', array('john', 'jane'));

Deze query zal de volgende SQL genereren:

    DELETE FROM `users` WHERE `username` IN ('john', 'jane')

#### Database Functies {#database_functions}

Uiteindelijk zal je waarschijnlijk uitdraaien in een situatie waar je beroep moet doen op `COUNT` of een andere database functie binnenin je query. De query builder ondersteunt deze functies op twee manieren. De eerste mogelijkheid is met behulp van aanhalingstekens binnenin de aliassen:

    $query = DB::select(array('COUNT("username")', 'total_users'))->from('users');

Dit ziet er bijna precies hetzelfde uit als een standaard "AS" alias, maar let op hoe de kolom naam is verpakt in dubbele aanhalingstekens. Iedere keer als er een waarde met dubbele aanhalingstekens verschijnt binnenin een kolom naam, wordt **alleen** het gedeelte binnen de dubbele aanhalingstekens "geescaped". Deze query zal de volgende SQL genereren:

    SELECT COUNT(`username`) AS `total_users` FROM `users`

#### Complexe Expressies

Aliassen met aanhalingstekens zullen de meeste problemen oplossen, maar van tijd tot tijd kan je in een situatie komen waar je een complexe expressie kunt gebruiken. In deze gevallen moet je een database expressie gebruiken die je kan creëren met [DB::expr]. Een database expressie wordt als directe input genomen en er wordt geen "escaping" uitgevoerd.
