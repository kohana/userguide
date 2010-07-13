# Laden van Classes

Kohana maakt dankbaar gebruik van PHP [autoloading](http://php.net/manual/language.oop5.autoload.php). Dit zorgt ervoor dat je niet [include](http://php.net/include) of [require](http://php.net/require) moet gebruiken vooraleer je de klasse kunt gebruiken. Bijvoorbeeld als je de [Cookie::set] method wilt gebruiken doe je:

    Cookie::set('mycookie', 'any string value');

Of om een [Encrypt] instantie in te laten, gewoon [Encrypt::instance] aanroepen:

    $encrypt = Encrypt::instance();

Classes worden ingeladen via de [Kohana::auto_load] methode, deze maakt een simpele conversie van de class naam naar de naam van het bestand:

1. Classes worden geplaatst in de `classes/` folder van het [bestandssysteem](about.filesystem)
2. Ieder underscore karakter wordt omgezet naar een slash.
2. De bestandsnaam is met kleine letters

Wanneer je een class aanroept die nog niet is ingeladen (vb. `Session_Cookie`), zal Kohana zoeken in het bestandssysteem via [Kohana::find_file] voor een bestand met de naam `classes/session/cookie.php`.

## Zelfgeschreven Autoloaders

De standaard autoloader wordt ingesteld in `application/bootstrap.php` via [spl_autoload_register](http://php.net/spl_autoload_register):

    spl_autoload_register(array('Kohana', 'auto_load'));

Dit laat [Kohana::auto_load] toe om te proberen eender welke class in te laden dat nog niet bestaat wanneer de class voor het eerst wordt gebruikt.

# Transparante Class Uitbreiding {#class-extension}

Het [cascading bestandssyteem](about.filesystem) laat transparante class uitbreiding toe. Bijvoorbeeld, de class [Cookie] is gedefinieerd in `SYSPATH/classes/cookie.php` als:

    class Cookie extends Kohana_Cookie {}

De standaard Kohana classes, en vele uitbreidingen, gebruiken deze manier van definiëren zodat bijna alle classes kunnen worden uitgebreid. Je kan elke class transparant uitbreiden, door een eigen class te definiëren in `APPPATH/classes/cookie.php` om je eigen methodes toe te voegen.

[!!] Je past best **nooit** bestanden aan die standaard in Kohana zitten. Maak aanpassingen aan classes altijd door ze uit te breiden om upgrade-problemen te vermijden.

Bijvoorbeeld, als je een methode wilt maken dat gecodeerde cookies maakt via de [Encrypt] class:

    <?php defined('SYSPATH') or die('No direct script access.');

    class Cookie extends Kohana_Cookie {

        /**
         * @var  mixed  default encryption instance
         */
        public static $encryption = 'default';

        /**
         * Maakt een gecodeerde cookie aan.
         *
         * @uses  Cookie::set
         * @uses  Encrypt::encode
         */
         public static function encrypt($name, $value, $expiration = NULL)
         {
             $value = Encrypt::instance(Cookie::$encrpytion)->encode((string) $value);

             parent::set($name, $value, $expiration);
         }

         /**
          * Krijg de inhoud van een gecodeerde cookie.
          *
          * @uses  Cookie::get
          * @uses  Encrypt::decode
          */
          public static function decrypt($name, $default = NULL)
          {
              if ($value = parent::get($name, NULL))
              {
                  $value = Encrypt::instance(Cookie::$encryption)->decode($value);
              }

              return isset($value) ? $value : $default;
          }

    } // End Cookie

Als je nu `Cookie::encrypt('secret', $data)` aanroept zal die een een gecodeerde cookie aanmaken die je kan decoderen met `$data = Cookie::decrypt('secret')`.

## Meerdere niveau's van uitbreidingen {#multiple-extensions}

Als je een Kohana class in een module uitbreidt, maak je best gebruik van transparante uitbreidingen. In plaats van de [Cookie] uitbreiding Kohana te laten uitbreiden, kan je `MODPATH/mymod/encrypted/cookie.php` aanmaken:

    class Encrypted_Cookie extends Kohana_Cookie {

        // Gebruik de encrypt() en decrypt() methodes van hierboven

    }

En maak `MODPATH/mymod/cookie.php` aan:

    class Cookie extends Encrypted_Cookie {}

Dit laat nog steeds toe om gebruikers hun eigen uitbreidingen te laten doen op [Cookie] zodat jouw uitbreidingen nog behouden blijven. Let wel, de volgende uitbreiding van [Cookie] zal `Encrypted_Cookie` moeten uitbreiden in plaats van `Kohana_Cookie`.
