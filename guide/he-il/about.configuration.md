# General Configuration - הגדרות כלליות

[!!] Finish translating... (todo: description of benefits of static properties for configuration)

## Core Configuration - הגדרות בסיסיות

ההגדרה הראשונה אותה יש לבצע בכל התקנה של קוהנה היא שינוי ההגדרות של [Kohana::init] ב `application/bootstrap.php`.
ההגדרות הן:

שגיאות:
האם להשתמש בטיפול שגיאות ויוצאי דופן פנימי של הקוהנה
ערך ברירת מחדל - True, יש לשנות ל FLASE במידה ולא מעוניינים

פרופיל:
האם להשתמש בדף הפרופיל הסטטיסטי
ערך ברירת מחדל - True
יש לשנות ל FALSE במידה ולא מעוניינים - מומלץ שלא להשתמש באפשרות זו בגרסה הסופית על מנת להסתיר מידע רגיש וטעינה מהירה יותר של הדפים

caching - זכרון מטמון
האם לשמור בזכרון מטמון את המיקום של הקבצים בין בקשות?
ערך ברירת מחדל - True, יש לשנות ל FALSE במידה ולא מעוניינים
פעולה זו מגבירה באופן דרמטי את מהירות הטעינת דפים [Kohana::find_file] ולכן יכולה להיות בעלת השפעה גדולה על רמת הביצועים הכללית של האפליקציה.
חשוב להשתמש באופצייה זו רק בגרסה הסופית או בשביל נסיונות.

`string` charset
:   Character set used for all input and output. (Default `"utf-8"`) Should be a character set that is supported by both [htmlspecialchars](http://php.net/htmlspecialchars) and [iconv](http://php.net/iconv).

`string` base_url
:   Base URL for the application. (Default `"/"`) Can be a complete or partial URL. For example "http://example.com/kohana/" or just "/kohana/" would both work.

`string` index_file
:   The PHP file that starts the application. (Default `"index.php"`) Set to `FALSE` when you remove the index file from the URL with URL rewriting.

`string` cache_dir
:   Cache file directory. (Default `"application/cache"`) Must point to a **writable** directory.

## Cookie Settings

There are several static properties in the [Cookie] class that should be set, particularly on production websites.

`string` salt
:   Unique salt string that is used to enable [signed cookies](security.cookies)

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
