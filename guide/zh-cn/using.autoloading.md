# 类的加载

Kohana 需要使用 PHP 自身的[自动加载](http://php.net/manual/language.oop5.autoload.php)。这个消除了不用调用 [include](http://php.net/include) 和 [require](http://php.net/require) 之前就可以使用类文件。例如，让你想使用 [Cookie::set] 方法时，你只需要：

    Cookie::set('mycookie', 'any string value');

或者要加载一个 [Encrypt] 的实例化，只需要调用 [Encrypt::instance]：

    $encrypt = Encrypt::instance();

类也可以通过 [Kohana::auto_load] 方法加载，这使得从简单的类名称转换为文件名：

1. 类必须放置在[文件系统](start.filesystem)的 `classes/` 目录
2. 任何下划线字符转换为斜线
2. 文件名必须是小写的

当调用一个尚未加载类（比如，`Session_Cookie`），通过使用 [Kohana::find_file] 方法可以让 Kohana 搜索文件系统查找名为 `classes/session/cookie.php` 的文件。

## 自动加载器

在 `application/bootstrap.php` 配置文件默认使用 [spl_autoload_register](http://php.net/spl_autoload_register) 开启了自动加载器。

    spl_autoload_register(array('Kohana', 'auto_load'));

在此类第一次使用的时候，这让 [Kohana::auto_load] 尝试去加载任意的不存在类。

# Transparent Class Extension {#class-extension}

The [cascading filesystem](about.filesystem) allows transparent class extension. For instance, the class [Cookie] is defined in `SYSPATH/classes/cookie.php` as:

    class Cookie extends Kohana_Cookie {}

The default Kohana classes, and many extensions, use this definition so that almost all classes can be extended. You extend any class transparently, by defining your own class in `APPPATH/classes/cookie.php` to add your own methods.

[!!] You should **never** modify any of the files that are distributed with Kohana. Always make modifications to classes using extensions to prevent upgrade issues.

For instance, if you wanted to create method that sets encrypted cookies using the [Encrypt] class:

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

Now calling `Cookie::encrypt('secret', $data)` will create an encrypted cookie which we can decrypt with `$data = Cookie::decrypt('secret')`.

## Multiple Levels of Extension {#multiple-extensions}

If you are extending a Kohana class in a module, you should maintain transparent extensions. Instead of making the [Cookie] extension extend Kohana, you can create `MODPATH/mymod/encrypted/cookie.php`:

    class Encrypted_Cookie extends Kohana_Cookie {

        // Use the same encrypt() and decrypt() methods as above

    }

And create `MODPATH/mymod/cookie.php`:

    class Cookie extends Encrypted_Cookie {}

This will still allow users to add their own extension to [Cookie] with your extensions intact. However, the next extension of [Cookie] will have to extend `Encrypted_Cookie` instead of `Kohana_Cookie`.
