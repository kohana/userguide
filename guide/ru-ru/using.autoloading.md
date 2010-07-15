# Загрузка классов

Kohana использует все преимущества [автозагрузки](http://php.net/manual/language.oop5.autoload.php) в PHP.
Это позволяет не использовать вызовы [include](http://php.net/include) или [require](http://php.net/require) перед использованием класса. К примеру, когда Вы хотите использовать метод [Cookie::set], Вы всего лишь вызываете:

    Cookie::set('mycookie', 'any string value');

А для получения объекта [Encrypt] просто используйте [Encrypt::instance]:

    $encrypt = Encrypt::instance();

Классы загружаются с помощью метода [Kohana::auto_load], который осуществляет простое преобразование из имени класса в имя файла:

1. Классы располагаются в директории `classes/` [файловой системы](about.filesystem)
2. Все знаки подчеркивания заменяются слэшами
2. Имена файлов должны быть в нижнем регистре

Когда вызывается класс, который еще не был загружен (например `Session_Cookie`), Kohana будет искать файл под именем `classes/session/cookie.php` в файловой системе, с помощью [Kohana::find_file].

## Собственные загрузчики

Системный загрузчик добавляется в файле `application/bootstrap.php` через вызов [spl_autoload_register](http://php.net/spl_autoload_register):

    spl_autoload_register(array('Kohana', 'auto_load'));

Теперь [Kohana::auto_load] будет пытаться загрузить любой несуществующий класс при его первом использовании.

# Прозрачное расширение классов {#class-extension}

[Каскадная файловая система](about.filesystem) поддерживает прозрачное расширение классов. Например, класс [Cookie] определен в `SYSPATH/classes/cookie.php` так:

    class Cookie extends Kohana_Cookie {}

Системные классы Kohana, как и многие модули, используют такое определение, так что практически все классы могут быть расширены. Это делается прозрачно для системы, создайте свой класс в `APPPATH/classes/cookie.php` для добавления собственных методов.

[!!] **Никогда** не изменяйте файлы дистрибутива Kohana. Всегда вносите изменения в классы, используя расширения, так Вы избавитесь от головной боли при обновлении.

К примеру, Вы хотите создать метод, который устанавливает зашифрованную куку с помощью класса [Encrypt]:

    <?php defined('SYSPATH') or die('No direct script access.');

    class Cookie extends Kohana_Cookie {

        /**
         * @var  mixed  default encryption instance
         */
        public static $encryption = 'default';

        /**
         * Sets an encrypted cookie.
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
          * Gets an encrypted cookie.
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

Теперь вызов `Cookie::encrypt('secret', $data)` будет создавать шифрованную куку, которую можно расшифровать так: `$data = Cookie::decrypt('secret')`.

## Многоуровневое расширение {#multiple-extensions}

Если Вы расширяете классы Kohana в модуле, следует поддерживать прозрачное расширение. Вместо того, чтобы наследовать расширение [Cookie] от [Kohana_Cookie], создайте `MODPATH/mymod/encrypted/cookie.php`:

    class Encrypted_Cookie extends Kohana_Cookie {

        // Используйте методы encrypt() and decrypt(), описанные выше

    }

Теперь создайте `MODPATH/mymod/cookie.php`:

    class Cookie extends Encrypted_Cookie {}

Таким образом, пользователи смогут добавлять свои расширения в класс [Cookie], не затрагивая Ваши изменения. Однако, при следующем расширении класса [Cookie] придется наследоваться от `Encrypted_Cookie`, а не от `Kohana_Cookie`.