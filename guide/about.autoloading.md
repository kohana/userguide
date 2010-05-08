# Autoloading

Kohana takes advantage of PHP [autoloading](http://php.net/manual/language.oop5.autoload.php). This removes the need to call [include](http://php.net/include) or [require](http://php.net/require) before using a class.

Classes are loaded via the [Kohana::auto_load] method, which makes a simple conversion from class name to file name:

1. Classes are placed in the `classes/` directory of the [filesystem](about.filesystem)
2. Any underscore characters are converted to slashes
2. The filename is lowercase

When calling a class that has not been loaded (eg: `Session_Cookie`), Kohana will search the filesystem using [Kohana::find_file] for a file named `classes/session/cookie.php`.

## Custom Autoloaders

The default autoloader is enabled in `application/bootstrap.php`, like this:

    spl_autoload_register(array('Kohana', 'auto_load'));

This adds the [Kohana::auto_load] method to the enabled auto loading callbacks. You can add your own using [spl_autoload_register](http://php.net/spl_autoload_register).
