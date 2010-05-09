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

# Loading Files

The path to any file within the filesystem can be found by calling [Kohana::find_file]:

    // Find the full path to "classes/cookie.php"
    $path = Kohana::find_file('classes', 'cookie');

    // Find the full path to "views/user/login.php"
    $path = Kohana::find_file('views', 'user/login');

## Classes

All classes within the filesystem can be [autoloaded](about.autoloading) without
having to call [include](http://php.net/include) or [require](http://php.net/require).

For instance, when you want to use the [Cookie::set] method, you just call:

    Cookie::set('mycookie', 'any string value');

### Transparent Class Extension

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

#### Multiple Levels of Extension

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

## Views

Most HTML is generated within by a [View] object, which loads a view file
from the `views/` directory. To load a view, create a new object with the
relative path to the view file with [View::factory]:

    // Loads "views/welcome.php" as a new view object
    $view = View::factory('welcome');

Once view file has been loaded into a view object, you can assign variables
to the view using the [View::set] and [View::bind] methods.


    // Make the $user variable to available in the view
    $view->bind('user', $user);

[!!] The only difference between `set()` and `bind()` is that `bind()` assigns
the variable by reference. If you `bind()` a variable before it has been defined,
the variable will be created as `NULL`.

Unlike version 2.x of Kohana, the view is not loaded within the context of
the [Controller], so you will not be able to access `$this` as the controller
that loaded the view. Passing the controller to the view must be done explictly:

    // Make $this available as $controller in the view
    $view->bind('controller', $this);

To display a view, you just [echo](http://php.net/echo) the view object or
assign it as a [Request::$response]:

    // Display the view right now
    echo $view;

Within a controller, you should always assign the view as the request response:

    // Use this view as the request
    $this->request->response = $view;

If you want to include another view within a view, there are two choices.
By calling [View::factory] you can sandbox the included view. This means
that you will have to provide all of the variables to the view using [View::set]
or [View::bind]:

    // Only the $user variable will be available in "views/user/login.php"
    <?php echo View::factory('user/login')->bind('user', $user) ?>

The other option is to include the view directly, which makes all of the current
variables available to the included view:

    // Any variable defined in this view will be included in "views/message.php"
    <?php include Kohana::find_file('views', 'user/login') ?>

Of course, you can also load an entire [Request] within a view:

    <?php echo Request::factory('user/login')->execute() ?>

This is an example of [HMVC](about.mvc), which makes it possible to create and
read calls to other URLs within your application.

# Vendor Extensions

We call extensions that are not specific to Kohana "vendor" extensions.
For example, if you wanted to use [DOMPDF](http://code.google.com/p/dompdf),
you would copy it to `application/vendor/dompdf` and include the DOMPDF
autoloading class:

    require Kohana::find_file('vendor', 'dompdf/dompdf/dompdf_config.inc');

Now you can use DOMPDF without loading any more files:

    $pdf = new DOMPDF;

[!!] If you want to convert views into PDFs using DOMPDF, try the
[PDFView](http://github.com/shadowhand/pdfview) module.