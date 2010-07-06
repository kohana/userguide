# General Configuration

Kohana uses both static properties and files for configuration. Static properties are typically used for static classes, such as [Cookie], [Security], and [Upload]. Files are typically used for objects such as [Database], [Encrypt], and [Session].

Static properties can be set in `APPPATH/bootstrap.php` or by [class extension](using.autoloading#class-extension). The benefit of static properties is that no additional files need to be loaded. The problem with this method is that it causes the class to be loaded when the property is set, if you do not use an extension. However, using extensions will overload extensions made in modules. It is generally recommended to do static property configuration in the bootstrap.

[!!] When using opcode caching, such as [APC](http://php.net/apc) or [eAccelerator](http://eaccelerator.net/), class loading time is significantly reduced. It is highly recommended to use opcode caching with *any* production website, no matter the size.

## Initial Settings

Every new Kohana installation will require changing [Kohana::init] settings in `APPPATH/bootstrap.php`. Any setting that is not set will use the default setting. These settings can be accessed and modified later by using the static property of the [Kohana] class. For instance, to get the current character set, read the [Kohana::$charset] property.

## Security Settings

There are several settings which need to be changed to make Kohana secure. The most important of these is [Cookie::$salt], which is used to create a "signature" on cookies that prevents them from being modified outside of Kohana.

If you plan to use the [Encrypt] class, you will also need to create an `encrypt` configuration file and set the encryption `key` value. The encryption key should include letters, numbers, and symbols for the best security.

[!!] **Do not use a hash for the encryption key!** Doing so will make the encryption key much easier to crack.

# Configuration Files {#config-files}

Configuration files are slightly different from other files within the [cascading filesystem](about.filesystem) in that they are **merged** rather than overloaded. This means that all configuration files with the same file path are combined to produce the final configuration. The end result is that you can overload *individual* settings rather than duplicating an entire file.

Configuration files are plain PHP files, stored in the `config/` directory, which return an associative array:

    <?php defined('SYSPATH') or die('No direct script access.');

    return array(
        'setting' => 'value',
        'options' => array(
            'foo' => 'bar',
        ),
    );

If the above configuration file was called `myconf.php`, you could access it using:

    $config = Kohana::config('myconf');
    $options = $config['options'];

[Kohana::config] also provides a shortcut for accessing individual keys from configuration arrays using "dot paths".

Get the "options" array:

    $options = Kohana::config('myconf.options');

Get the "foo" key from the "options" array:

    $foo = Kohana::config('myconf.options.foo');

Configuration arrays can also be accessed as objects, if you prefer that method:

    $options = Kohana::config('myconf')->options;

Please note that you can only access the top level of keys as object properties, all child keys must be accessed using standard array syntax:

    $foo = Kohana::config('myconf')->options['foo'];
