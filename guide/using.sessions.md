# Using Sessions and Cookies

Kohana provides a couple of classes that make it easy to work with both cookies and session. At a high level both sessions and cookies provide the same function. They allow the developer to store temporary or persistent information about a specific client for later retrieval.

Cookies should be used for storing non-private data that is persistent for a long period of time. For example storing a user id or a language preference. Use the [Cookie] class for getting and setting cookies.

[!!] Kohana uses "signed" cookies. Every cookie that is stored is combined with a secure hash to prevent modification of the cookie. This hash is generated using [Cookie::salt], which uses the [Cookie::$salt] property. You should [change this setting](using.configuration) when your application is live.

Sessions should be used for storing temporary or private data. Very sensitive data should be stored using the [Session] class with the "database" or "native" adapters. When using the "cookie" adapter, the session should always be encrypted.

[!!] For more information on best practices with session variables see [the seven deadly sins of sessions](http://lists.nyphp.org/pipermail/talk/2006-December/020358.html).

# Storing, Retieving, and Deleting Data

[Cookie] and [Session] provide a very similar API for storing data. The main difference between them is that sessions are accessed using an object, and cookies are accessed using a static class.

Accessing the session instance is done using the [Session::instance] method:

    // Get the session instance
    $session = Session::instance();

## Storing Data

Storing session or cookie data is done using the `set` method:

    // Set session data
    $session->set($key, $value);

    // Set cookie data
    Cookie::set($key, $value);

    // Store a user id
    $session->set('user_id', 10);
    Cookie::set('user_id', 10);

## Retrieving Data

Getting session or cookie data is done using the `get` method:

    // Get session data
    $data = $session->get($key, $default_value);

    // Get cookie data
    $data = Cookie::get($key, $default_value);

    // Get the user id
    $user = $session->get('user_id');
    $user = Cookie::get('user_id');

## Deleting Data

Deleting session or cookie data is done using the `delete` method:

    // Delete session data
    $session->delete($key);

    // Delete cookie data
    Cookie::delete($key);

    // Delete the user id
    $session->delete('user_id');
    Cookie::delete('user_id');

# Configuration

Both cookies and sessions have several configuration settings which affect how data is stored. Always check these settings before making your application live, as many of them will have a direct affect on the security of your application.

## Cookie Settings

All of the cookie settings are changed using static properties. You can either change these settings in `bootstrap.php` or by using a [class extension](using.autoloading#class-extension).

The most important setting is [Cookie::$salt], which is used for secure signing. This value should be changed and kept secret:

    Cookie::$salt = 'your secret is safe with me';

[!!] Changing this value will render all cookies that have been set before invalid.

By default, cookies are stored until the browser is closed. To use a specific lifetime, change the [Cookie::$expiration] setting:

    // Set cookies to expire after 1 week
    Cookie::$expiration = 604800;

    // Alternative to using raw integers, for better clarity
    Cookie::$expiration = Date::WEEK;

The path that the cookie can be accessed from can be restricted using the [Cookie::$path] setting.

    // Allow cookies only when going to /public/*
    Cookie::$path = '/public/';

The domain that the cookie can be accessed from can also be restricted, using the [Cookie::$domain] setting.

    // Allow cookies only on the domain www.example.com
    Cookie::$domain = 'www.example.com';

If you want to make the cookie accessible on all subdomains, use a dot at the beginning of the domain.

    // Allow cookies to be accessed on example.com and *.example.com
    Cookie::$domain = '.example.com';

To only allow the cookie to be accessed over a secure (HTTPS) connection, use the [Cookie::$secure] setting.

    // Allow cookies to be accessed only on a secure connection
    Cookie::$secure = TRUE;
    
    // Allow cookies to be accessed on any connection
    Cookie::$secure = FALSE;

To prevent cookies from being accessed using Javascript, you can change the [Cookie::$httponly] setting.

    // Make cookies inaccessible to Javascript
    Cookie::$httponly = TRUE;

## Session Adapters

When creating or accessing an instance of the [Session] class you can decide which session adapter you wish to use. The session adapters that are available to you are:

Native
: Stores session data in the default location for your web server. The storage location is defined by [session.save_path](http://php.net/manual/session.configuration.php#ini.session.save-path) in `php.ini` or defined by [ini_set](http://php.net/ini_set).

Database
: Stores session data in a database table using the [Session_Database] class. Requires the [Database] module to be enabled.

Cookie
: Stores session data in a cookie using the [Cookie] class. **Sessions will have a 4KB limit when using this adapter.**

[!!] The default adapter is "native". You can change the default datapter by setting [Session::$default] to the adapter you want to use.

### Session Adapter Settings

[!!] Stub, explain configuration options
