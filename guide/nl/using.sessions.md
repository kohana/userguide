# Gebruik van Sessies en Cookies

Kohana biedt een paar classes die het gemakkelijk maken om te werken met cookies en sessies. Op een hoog niveau, zowel sessies en cookies geven dezelfde functionaliteit. Ze laten de ontwikkelaar toe om tijdelijke of blijvende informatie over een specifieke klant voor later op te slaan.

Cookies moeten worden gebruikt voor de opslag van niet-private gegevens die persistent is voor een lange periode van tijd. Bijvoorbeeld het opslaan van een gebruikers-id of een taalvoorkeur. Gebruik de [Cookie] class voor het verkrijgen en instellen van cookies.

[!!] Kohana gebruikt "ondertekende" cookies. Elke cookie die wordt opgeslagen wordt gecombineerd met een veilige hash om een wijziging van de cookie te voorkomen. Deze hash wordt gegenereerd met behulp van [Cookie:: salt], die de [Cookie::$salt] property gebruikt. Je moet [deze instelling] (using.configuration) veranderen wanneer je applicatie live staat.

Sessies worden gebruikt voor het opslaan van tijdelijke of prive-gegevens. Zeer gevoelige gegevens moeten worden opgeslagen met behulp van de [Session] class met de "database" of "native" adapters. Bij gebruik van de "cookie"-adapter, moet de sessie altijd worden versleuteld.

[!!] Voor meer informatie over de beste manieren van werken met sessie-variabelen, zie [the seven deadly sins of sessions](http://lists.nyphp.org/pipermail/talk/2006-December/020358.html).

# Het opslaan, ophalen en verwijderen van gegevens

[Cookie] en [Session] bieden een zeer gelijkaardige API voor het opslaan van gegevens. Het belangrijkste verschil tussen hen is dat sessies benaderd kunnen worden met behulp van een object, en cookies met behulp van een statische class.

De sessie instantie benaderen wordt gedaan met de [Session::instance] methode:

    // Verkrijg de sessie instantie
    $session = Session::instance();

Bij het gebruik van sessies, kan je alle huidige sessiegegevens krijgen met behulp van de [Session::as_array] methode:

    // Verkrijg alle sessiegegevens als een array
    $data = $session->as_array();

Je kan dit ook gebruiken om de `$_SESSION` global te overladen om data te krijgen en in te stellen in verlijkbare manier zoals standaard PHP:

    // Overlaad $_SESSION met sessiegegevens
    $_SESSION =& $session->as_array();
    
    // Stel de sessiegegevens in
    $_SESSION[$key] = $value;

## Gegevens opslaan {#setting}

Het opslaan van sessie- of cookie-gegevens wordt gedaan met behulp van de `set`-methode:

    // Sla sessiegegevens op
    $session->set($key, $value);

    // Sla cookiegegevens op
    Cookie::set($key, $value);

    // Sla een gebruikers id op
    $session->set('user_id', 10);
    Cookie::set('user_id', 10);

## Verkrijgen van gegevens {#getting}

Verkrijgen van sessie- of cookie-gegevens wordt gedaan met behulp van de `get`-methode:

    // Verkrijg sessiegegevens
    $data = $session->get($key, $default_value);

    // Verkrijg cookiegegevens
    $data = Cookie::get($key, $default_value);

    // Verkrijg het gebruikers id
    $user = $session->get('user_id');
    $user = Cookie::get('user_id');

## Verwijderen van gegevens {#deleting}

Het verwijderen van sessie- of cookie-gegevens wordt gedaan met behulp van de `delete`-methode:

    // Verwijderen van sessiegegevens
    $session->delete($key);

    // Verwijderen van cookiegegevens
    Cookie::delete($key);

    // Verwijder een gebruikers id
    $session->delete('user_id');
    Cookie::delete('user_id');

# Configuratie {#configuration}

Zowel cookies als sessies hebben verschillende configuratie-instellingen die van invloed zijn hoe gegevens worden opgeslagen. Controleer altijd deze instellingen voordat u uw applicatie live zet, omdat veel van die instellingen een rechtstreeks effect zal hebben op de veiligheid van uw applicatie.

## Cookie Instellingen {#cookie-settings}

Al de cookie instellingen worden verandert met behulp van statische properties. Je kan deze instellingen veranderen in `bootstrap.php` of door een [class extension](using.autoloading#class-extension) te gebruiken.

De meest belangrijke instelling is [Cookie::$salt], die wordt gebruikt om veilig te ondertekenen. Deze waarde zou moeten gewijzigd en geheim gehouden worden:

    Cookie::$salt = 'Uw geheim is veilig bij mij';

[!!] Door het veranderen van deze waarde zullen alle bestaande cookies niet meer geldig zijn.

Standaard worden cookies bewaard tot het browservenster wordt gesloten. Om een specifieke leeftijd te gebruiken, verander de [Cookie::$expiration] instelling:

    // Stel in dat cookies vervallen na één week
    Cookie::$expiration = 604800;

    // Alternatief voor het gebruik van getallen, voor meer duidelijkheid
    Cookie::$expiration = Date::WEEK;

Het path waarvan de cookie kan worden opgevraagd kan worden beperkt met behulp van de [Cookie::$path] instelling.

    // Enkel cookies toelaten wanneer je gaat naar /public/*
    Cookie::$path = '/public/';

Het domein waarvan de cookie kan worden geopend kan ook worden beperkt, met behulp van de [Cookie::$domain] instelling.

    // Enkel cookies toelaten voor www.example.com
    Cookie::$domain = 'www.example.com';

Als u de cookie toegankelijk wilt maken op alle subdomeinen, gebruik dan een punt aan het begin van het domein.

    // Cookies toegankelijk maken voor example.com en *.example.com
    Cookie::$domain = '.example.com';

Als je de cookie alleen wilt kunnen benaderen via een beveiligde (HTTPS) verbinding, gebruik dan de [Cookie::$secure] instelling.

    // Cookies enkel toegangekijk maken via een beveiligde verbinding
    Cookie::$secure = TRUE;
    
    // Cookies toegankelijk maken voor elke verbinding
    Cookie::$secure = FALSE;

Om te voorkomen dat cookies worden geopend met behulp van Javascript, kunt u de [Cookie::$httponly] instelling aanpassen.

    // Maak cookies niet toegankelijk via Javascript
    Cookie::$httponly = TRUE;

## Sessie Adapters {#adapters}

Bij het maken van of het aanroepen van een instantie van de [Sessie] class kan je kiezen welke sessie adapter je wilt gebruiken. De sessie adapters die beschikbaar zijn voor je:

Native
: Slaat sessiegegevens op in de standaard locatie voor uw web server. De opslaglocatie is gedefinieerd door [session.save_path](http://php.net/manual/session.configuration.php#ini.session.save-path) in `php.ini` of gedefinieerd door [ini_set](http://php.net/ini_set).

Database
: Slaat de sessiesgegevens op in een database tabel door gebruik te maken van de [Session_Database] class. De [Database] module is vereist.

Cookie
: Slaat de sessiegegevens op in een cookie door gebruikt te maken van de [Cookie] class. **Sessies hebben een 4KB limiet wanneer je deze adapter gebruikt.**

De standaard adapter kan ingesteld worden door de waarde aan te passen van [Session::$default]. De standaard adapter is "native".

[!!] Zoals bij cookies bekent een "lifetime" instelling van "0" dat de sessie zal vervallen bij het sluiten van de het browservenster.

### Sessie Adapter Instellingen

Je kan configuratie-instellingen voor elk van de sessie adapters instellen door het creëren van een sessie configuratiebestand in `APPPATH/config/session.php`. Het volgende voorbeeld van een configuratie bestand definiëert alle instellingen voor elke adapter:

    return array(
        'native' => array(
            'name' => 'session_name',
            'lifetime' => 43200,
        ),
        'cookie' => array(
            'name' => 'cookie_name',
            'encrypted' => TRUE,
            'lifetime' => 43200,
        ),
        'database' => array(
            'name' => 'cookie_name',
            'encrypted' => TRUE,
            'lifetime' => 43200,
            'group' => 'default',
            'table' => 'table_name',
            'columns' => array(
                'session_id'  => 'session_id',
        		'last_active' => 'last_active',
        		'contents'    => 'contents'
            ),
            'gc' => 500,
        ),
    );

#### Native Adapter {#adapter-native}

Type      | Instelling | Omschrijving                                        | Standaard
----------|------------|-----------------------------------------------------|-----------
`string`  | name       | naam van de sessie                                  | `"session"`
`integer` | lifetime   | aantal seconden dat de sessie moet bestaan          | `0`

#### Cookie Adapter {#adapter-cookie}

Type      | Instelling | Omschrijving                                        | Standaard
----------|------------|-----------------------------------------------------|-----------
`string`  | name       | naam van de cookie om de sessiegegevens op te slaan | `"session"`
`boolean` | encrypted  | de sessiegegevens coderen met [Encrypt]?            | `FALSE`
`integer` | lifetime   | aantal seconden dat de sessie moet bestaan          | `0`

#### Database Adapter {#adapter-database}

Type      | Instelling | Omschrijving                                        | Standaard
----------|------------|-----------------------------------------------------|-----------
`string`  | group      | [Database::instance] groep naam                     | `"default"`
`string`  | table      | de tabelnaam waar de gegevens worden in opgeslagen  | `"sessions"`
`array`   | columns    | associatieve array met kolom aliassen               | `array`
`integer` | gc         | 1:x kans dat de garbage collection uitgevoerd wordt | `500`
`string`  | name       | naam van de cookie om de sessiegegevens op te slaan | `"session"`
`boolean` | encrypted  | de sessiegegevens coderen met [Encrypt]?            | `FALSE`
`integer` | lifetime   | aantal seconden dat de sessie moet bestaan          | `0`

##### Tabel Schema

Je moet de sessie-opslag tabel in de database aanmaken. Dit is het standaard schema:

    CREATE TABLE  `sessions` (
        `session_id` VARCHAR(24) NOT NULL,
        `last_active` INT UNSIGNED NOT NULL,
        `contents` TEXT NOT NULL,
        PRIMARY KEY (`session_id`),
        INDEX (`last_active`)
    ) ENGINE = MYISAM;

##### Tabel kolommen

Je kunt de namen van kolommen aanpassen om overeen te komen met een bestaand database-schema. De standaard waarde is hetzelfde als de key waarde.

session_id
: de naam van de "id" kolom

last_active
: UNIX timestamp van het laatste tijdstip dat de sessie werd aangepast

contents
: sessiongegevens opgeslaan in een serialized string, en optioneel gecodeerd
