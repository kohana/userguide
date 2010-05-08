# Cascading Filesystem

The Kohana filesystem is a heirarchy of directory structure. When a file is
loaded by [Kohana::find_file], it is searched in the following order:

Application Path
: Defined as `APPPATH` in `index.php`. The default value is `application`.

Module Paths
: This is set as an associative array using [Kohana::modules] in `APPPATH/bootstrap.php`.
  Each of the values of the array will be searched in the order that the modules
  are added.

System Path
: Defined as `SYSPATH` in `index.php`. The default value is `system`. All of the
  main or "core" files and classes are defined here.

Files that are in directories higher up the include path order take precedence
over files of the same name lower down the order, which makes it is possible to
overload any file by placing a file with the same name in a "higher" directory:

![Cascading Filesystem Infographic](img/cascading_filesystem.png)

If you have a view file called `welcome.php` in the `APPPATH/views` and
`SYSPATH/views` directories, the one in application will be returned when
`welcome.php` is loaded because it is at the top of the filesystem.

# Transparent Class Extension

The cascading filesystem also allows transparent class extension. For instance,
the class [Cookie] is defined in `SYSPATH/classes/cookie.php` as:

    class Cookie extends Kohana_Cookie {}

The default Kohana classes, and many extensions, use this definition so that
almost all classes can be over loaded. You extend any class transparently,
by defining your own class in `APPPATH/classes/cookie.php` to add your own methods.
For instance, if you wanted to create method that sets encrypted cookies using
the [Encrypt] class:

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

Now calling `Cookie::encrypt('secret', $data)` will create an encrypted cookie
which we can decrypt with `$data = Cookie::decrypt('secret')`.

## Multiple Levels of Extension

If you are extending a Kohana class in a module, you should maintain
transparent extensions. Instead of making the [Cookie] extension extend Kohana,
you can create `MODPATH/mymod/encrypted/cookie.php`:

    class Encrypted_Cookie extends Kohana_Cookie {

        // Use the same encrypt() and decrypt() methods as above

    }

And create `MODPATH/mymod/cookie.php`:

    class Cookie extends Encrypted_Cookie {}

This will still allow users to add their own extension to [Cookie] with your
extensions intact. However, the next extension of [Cookie] will have to extend
`Encrypted_Cookie` instead of `Kohana_Cookie`.
