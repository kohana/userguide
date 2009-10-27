# General Configuration

[!!] todo, description of benefits of static properties for configuration

## Core Configuration

The first configuration task of any new Kohana installation is changing the [Kohana::init] settings in `application/bootstrap.php`. These settings are:

`boolean` errors
:   Use internal error and exception handling? (Default `TRUE`) Set to `FALSE` to disable the Kohana
    error and exception handlers.

`boolean` profile
:   Do internal benchmarking? (Default `TRUE`) Set to `FALSE` to disable internal profiling.
    Disable in production for best performance.

`boolean` caching
:   Cache the location of files between requests? (Default `FALSE`) Set to `TRUE` to cache the
    absolute path of files. This dramatically speeds up [Kohana::find_file] and can sometimes
    have a dramatic impact on performance. Only enable in a production environment, or for testing.

`string` charset
:   Character set used for all input and output. (Default `"utf-8"`) Should be a character set that is supported by both [htmlspecialchars](http://php.net/htmlspecialchars) and [iconv](http://php.net/iconv).

`string` base_url
:   Base URL for the application. (Default `"/"`) Can be a complete or partial URL. For example "http://example.com/kohana/" or just "/kohana/" would both work.

`string` index_file
:   The PHP file that starts the application. (Default `"index.php"`) Set to `FALSE` when you remove the index file from with URL rewriting.

`string` cache_dir
:   Cache file directory. (Default `"application/cache"`) Must point to a **writable** directory.

## Cookie Settings

There are several static properties in the [Cookie] class that should be set, particularly on production websites.

`string` salt
:   Unique salt string that is used to used to enable [signed cookies](security.cookies)

`integer` expiration
:   Default expiration lifetime in seconds

`string` path
:   URL path to restrict cookies to be accessed

`string` domain
:   URL domain to restrict cookies to be accessed

`boolean` secure
:   Only allow cookies to be accessed over HTTPS

`boolean` httponly
:   Only allow cookies to be accessed over HTTP (also disables Javascript access)

# Configuration Files

Configuration is done in plain PHP files, which look similar to:

~~~
<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'setting' => 'value',
    'options' => array(
        'foo' => 'bar',
    ),
);
~~~

If the above configuration file was called `myconf.php`, you could acess it using:

~~~
$config = Kohana::config('myconf');
$options = $config['options'];
~~~

[Kohana::config] also provides a shortcut for accessing individual keys from configuration arrays using "dot paths".

Get the "options" array:

~~~
$options = Kohana::config('myconf.options');
~~~

Get the "foo" key from the "options" array:

~~~
$foo = Kohana::config('myconf.options.foo');
~~~

Configuration arrays can also be accessed as objects, if you prefer that method:

~~~
$options = Kohana::config('myconf')->options;
~~~