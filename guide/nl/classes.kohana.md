#Kohana

**Todo: message()**

##Static Properties
* $environment
* $is_ci
* $is_windows
* $magic_quotes
* $log_errors
* $charset
* $base_url
* $index_file
* $cache_dir
* $caching (<code>bool</code>) - enable internal caching
* $profiling (<code>bool</code>)
* $errors (<code>bool</code>) - should Kohana handle errors
* [$log](classes.log) (log object)
* [$config](classes.config) (config object)

##Methods
###::init() {#init}
This starts everything off. It takes one optional parameter, an array of settings. The defaults are listed below. This should be run early on in your [bootstrap.php](basics.startup#bootstrap_php) file.

###::sanitize() {#sanitize}
This will return a sanitized variable. It will also sanitize an array or object and their values/properties recursively.

###::modules() {#module}
Returns the list of currently enabled modules.

###::modules($modules) {#modues}
Replaces the list of modules.

    Kohana::modules(array(
    	'database' => MODPATH.'database'
    ));

###::include_paths() {#include_paths}
Returns the currently active include paths, including the application and system paths.

###::find_file() {#find_file}
Finds the path of a file by directory, filename, and extension. It will return an absolute path to the file. This works with the [cascading filesystem](basics.filesystem).

    Kohana::find_file('views', 'template'); // assumes default EXT extension (usually .php)
    
    Kohana::find_file('media', 'css/style', 'css');

If the directory is config, i18n or messages then it will return an array of paths which should be merged.

    Kohana::find_file('config', 'mimes');

###::list_files() {#list_files}
Returns a list of all files in a directory (including subdirectories). These are sorted alphabetically.

    $views = Kohana::list_files('views');
Accepts an optional second parameter <code>$paths</code> that tells it which paths to look in. The default is the same as <code>Kohana::include_paths()</code>.

###::load()
Loads a file and returns its output.

    $foo = Kohana::load('foo.php');

###::cache($name) {#cache}
Returns the contents of a cached file or <code>NULL</code> if it does not exist. This is a simple core cache method and should only be used for strings and arrays as they are stored as PHP code. It will not be able to store references or handle recursion.

###::cache($name, $data, $lifetime) {#cache2}
Creates or updates a cached file. Default <code>$lifetime</code> is 60 seconds. This caches an array as <code>foo</code> that will expire in one hour.

    Kohana::cache('foo', array('foo', 'bar'), 3600); //3600 = 1 hour

###::message() {#message}