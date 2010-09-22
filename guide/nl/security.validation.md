# Validatie

Validatie kan uitgevoerd worden op elke array met behulp van de [Validate] class. Labels, filters, regels en callbacks kunnen aan het Validate object worden toegevoegd via een array key, zogenaamd een "veldnaam".

labels
:  Een label is een gebruiksvriendelijke leesbare versie van de veldnaam.

filters
:  Een filter wijzigt de waarde van een veld voordat de regels en callbacks worden uitgevoerd.

rules
:  Een regel is een controle op een veld dat "TRUE" of "FALSE" teruggeeft. A rule is a check on a field that returns `TRUE` or `FALSE`. Als een regel `FALSE` teruggeeft, wordt er een error toegevoegd aan het veld.

callbacks
:  Een callback is een zelfgeschreven methode die het gehele Validate object tot zijn beschikking heeft. De return value van een callback wordt genegeerd. In plaats daarvan moet de callback handmatig een error toevoegen aan het object met behulp van [Validate::error] wanneer een fout optreedt.

[!!] Merk op dat de [Validate] callbacks en de [PHP callbacks](http://php.net/manual/language.pseudo-types.php#language.types.callback) niet hetzelfde zijn.

Waneer je `TRUE` als veldnaam gebruikt bij het toevoegen van een filter, regel of callback, dan zal deze worden toegepast op alle velden met een naam.

**Het [Validate] object zal alle velden verwijderen van de array wanneer deze niet specifiek een naam hebben gekregen via een label, filter, regel of callback. Dit voorkomt toegang tot velden die niet gevalideerd zijn als een veiligheidsmaatregel.**

Een validatie object maken wordt gedaan door de [Validate::factory] methode:

    $post = Validate::factory($_POST);

[!!] Het `$post` object zal worden gebruikt voor de rest van deze tutorial. Deze tutorial zal je tonen hoe je een registratie van een nieuwe gebruiker valideert.

### Standaard regels

Validatie heeft standaard altijd enkele regels:

Naam van de regel         | Functie
------------------------- |---------------------------------------------------
[Validate::not_empty]     | Waarde moet een niet-lege waarde zijn
[Validate::regex]         | Waarde moet voldoen aan de reguliere expressie
[Validate::min_length]    | Minimum aantal karakters voor een waarde
[Validate::max_length]    | Maximum aantal karakters voor een waarde
[Validate::exact_length]  | Waarde moet een exact aantal karakters bevatten
[Validate::email]         | Een emailadres is vereist
[Validate::email_domain]  | Controleer of het domein van het email bestaat
[Validate::url]           | Waarde moet een URL zijn
[Validate::ip]            | Waarde moet een IP address zijn
[Validate::phone]         | Waarde moet een telefoonnummer zijn
[Validate::credit_card]   | Waarde moet een credit card zijn
[Validate::date]          | Waarde moet een datum (en tijd) zijn
[Validate::alpha]         | Alleen alpha karakters toegelaten
[Validate::alpha_dash]    | Alleen alpha karakters en koppeltekens toegelaten
[Validate::alpha_numeric] | Alleen alpha karakters en nummers toegelaten
[Validate::digit]         | Waarde moet een geheel getal zijn
[Validate::decimal]       | Waarde moet een decimaal of float getal zijn
[Validate::numeric]       | Alleen nummers toegelaten
[Validate::range]         | Waarde moet zich bevinden binnenin een range
[Validate::color]         | Waarde moet een geldige HEX kleurencode zijn
[Validate::matches]       | Waarde moet gelijk zijn aan een ander veld

[!!] Iedere methode dat bestaat binnenin de [Validate] class kan gebruikt worden als validatie-regel zonder een volledige callback te definiëren. Bijvoorbeeld, `'not_empty'` toevoegen is hetzelfde als `array('Validate', 'not_empty')`.

## Toevoegen van filters

Alle validatie-filters worden gedefineerd als een veldnaam, een methode of een functie (gebruik makend van [PHP callback](http://php.net/manual/language.pseudo-types.php#language.types.callback) syntax) en een array van parameters:

    $object->filter($field, $callback, $parameter);

Filters veranderen de waarde van een veld vooraleer deze gecontoleerd zijn via regels of callbacks.

Indien we het veld "username" willen omvormen naar kleine letters:

    $post->filter('username', 'strtolower');

Als we alle witruimtes voor en na de waarde willen verwijderen voor *alle* velden:

    $post->filter(TRUE, 'trim');

## Toevoegen van regels

Alle validatieregels worden gedefineerd als een veldnaam, een methode of een functie (gebruik makend van [PHP callback](http://php.net/manual/language.pseudo-types.php#language.types.callback) syntax) en een array van parameters:

    $object->rule($field, $callback, $parameter);

Om ons voorbeeld te starten, zullen we validatie uitvoeren op een `$_POST` array die gebruikers registratie gegevens bevat:

    $post = Validate::factory($_POST);

Vervolgens moeten we de POST-informatie met behulp van [Validate] doorlopen. Om te beginnen moeten we een aantal regels toevoegen:

    $post
        ->rule('username', 'not_empty')
        ->rule('username', 'regex', array('/^[a-z_.]++$/iD'))

        ->rule('password', 'not_empty')
        ->rule('password', 'min_length', array('6'))
        ->rule('confirm',  'matches', array('password'))

        ->rule('use_ssl', 'not_empty');

Iedere bestaande PHP functie kan worden gebruikt als regel. Bijvoorbeeld, als we willen controleren of een gebruiker een correcte waarde heeft ingevuld als antwoord op de SSL question:

    $post->rule('use_ssl', 'in_array', array(array('yes', 'no')));

Merk op dat alle array parameters steeds moeten "verpakt" worden door een array! Zonder die array, `in_array` zou worden aangeroepen als `in_array($value, 'yes', 'no')`, wat een PHP error zou teruggeven.

Je kan eigen regels toevoegen met behulp van een [PHP callback](http://php.net/manual/language.pseudo-types.php#language.types.callback]:

    $post->rule('username', 'User_Model::unique_username');

[!!] Momenteel (v3.0.7) is het niet mogelijk om een object te gebruiken als rule, enkel statische methodes en functies.

De methode `User_Model::unique_username()` zal ongeveer gedefinieerd worden als:

    public static function unique_username($username)
    {
        // Controleer of de username al bestaat in de database
        return ! DB::select(array(DB::expr('COUNT(username)'), 'total'))
            ->from('users')
            ->where('username', '=', $username)
            ->execute()
            ->get('total');
    }

[!!] Zelfgeschreven regels laten toe om de vele extra controles te hergebruiken voor verschillende doeleinden. Deze functies zullen meestal bestaan in een model, maar kunnen gedefinieerd worden in elke class.

## Toevoegen van callbacks

Alle validatie-callbacks worden gedefineerd als een veldnaam en een methode of een functie (gebruik makend van [PHP callback](http://php.net/manual/language.pseudo-types.php#language.types.callback) syntax):

    $object->callback($field, $callback);

[!!] In tegenstelling tot filters en regels, kunnen geen parameters worden meegestuurd naar een callback.

Het gebruikers wachtwoord moet gehashed worden indien het gevalideerd is, dus zulen we dit doen met een callback:

    $post->callback('password', array($model, 'hash_password'));

Dit in de veronderstelling dat de `$model->hash_password()` methode er gelijkaardig uitzien als:

    public function hash_password(Validate $array, $field)
    {
        if ($array[$field])
        {
            // Hash het wachtwoord als het bestaat
            $array[$field] = sha1($array[$field]);
        }
    }

# Een volledig voorbeeld

Eerst hewwen we een [View] nodig met daarin een HTML formulier, die we plaatsen in `application/views/user/register.php`:

    <?php echo Form::open() ?>
    <?php if ($errors): ?>
    <p class="message">Er zijn enkele fouten opgelopen, gelieve je ingevoerde gegevens opnieuw te bekijken.</p>
    <ul class="errors">
    <?php foreach ($errors as $message): ?>
        <li><?php echo $message ?></li>
    <?php endforeach ?>
    <?php endif ?>

    <dl>
        <dt><?php echo Form::label('username', 'Gebruikersnaam') ?></dt>
        <dd><?php echo Form::input('username', $post['username']) ?></dd>

        <dt><?php echo Form::label('password', 'Wachtwoord') ?></dt>
        <dd><?php echo Form::password('password') ?></dd>
        <dd class="help">Wachtwoord moet minstens 6 karakters lang zijn.</dd>
        <dt><?php echo Form::label('confirm', 'Bevestig wachtwoord') ?></dt>
        <dd><?php echo Form::password('confirm') ?></dd>

        <dt><?php echo Form::label('use_ssl', 'Gebruik extra veiligheid?') ?></dt>
        <dd><?php echo Form::select('use_ssl', array('yes' => 'Altijd', 'no' => 'Enkel indien nodig'), $post['use_ssl']) ?></dd>
        <dd class="help">Voor uw veiligheid wordt SSL altijd gebruik bij betalingen.</dd>
    </dl>

    <?php echo Form::submit(NULL, 'Registreer') ?>
    <?php echo Form::close() ?>

[!!] Dit voorbeeld maakt veel gebruik van de [Form] helper. Het gebruik van [Form] in plaats van HTML schrijven zorgt ervoor dat alle formuliervelden overweg kunnen met ingevoerde waardes die HTML karakters bevatten. Indien je liever zelf HTML schrijft, gebruik dan zeker [HTML::chars] om gebruikersgegevens te "escapen".

Vervolgens hebben we een controller nodig en een actie om de registratie uit te voeren, we plaatsen dit in `application/classes/controller/user.php`:

    class Controller_User extends Controller {

        public function action_register()
        {
            $user = Model::factory('user');

            $post = Validate::factory($_POST)
                ->filter(TRUE, 'trim')

                ->filter('username', 'strtolower')

                ->rule('username', 'not_empty')
                ->rule('username', 'regex', array('/^[a-z_.]++$/iD'))
                ->rule('username', array($user, 'unique_username'))

                ->rule('password', 'not_empty')
                ->rule('password', 'min_length', array('6'))
                ->rule('confirm',  'matches', array('password'))

                ->rule('use_ssl', 'not_empty')
                ->rule('use_ssl', 'in_array', array(array('yes', 'no')))

                ->callback('password', array($user, 'hash_password'));

            if ($post->check())
            {
                // Data is gevalideerd, registreer de gebruiker
                $user->register($post);

                // Altijd een redirect uitvoeren na een succesvolle POST om herladingsberichten te voorkomen.
                $this->request->redirect('user/profile');
            }

            // Validatie is fout gelopen, verzamel alle errors
            $errors = $post->errors('user');

            // Toon het registratieformulier
            $this->request->response = View::factory('user/register')
                ->bind('post', $post)
                ->bind('errors', $errors);
        }

    }

We hebben ook een user-model nodig, we plaatsen dit in `application/classes/model/user.php`:

    class Model_User extends Model {

        public function register($array)
        {
            // Maak een nieuw gebruikerslijn aan in de database
            $id = DB::insert(array_keys($array))
                ->values($array)
                ->execute();

            // Bewaar het nieuwe gebruikers id in een cookie
            cookie::set('user', $id);

            return $id;
        }

    }

Dat is het, we hebben een volledig gebruikersregistratie voorbeeld afgewerkt dat zorgvuldig ingevoerde gegevens controleert.
