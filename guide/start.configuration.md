# Configuration

Configuration is done in plain PHP files. Configuration files look like this:

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