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

## Types of Files

The top level directories of the application, module, and system paths has the following
default directories:

classes/
:  All classes that you want to [autoload](using.autoloading) should be stored here.
   This includes controllers, models, and all other classes. All classes must
   follow the [class naming conventions](about.conventions#classes).

config/
:  Configuration files return an associative array of options that can be
   loaded using [Kohana::config]. See [config usage](using.configuration) for
   more information.

i18n/
:  Translation files return an associative array of strings. Translation is
   done using the `__()` method. To translate "Hello, world!" into Spanish,
   you would call `__('Hello, world!')` with [I18n::$lang] set to "es-es".
   See [translation usage](using.translation) for more information.

messages/
:  Message files return an associative array of strings that can be loaded
   using [Kohana::message]. Messages and i18n files differ in that messages
   are not translated, but always written in the default language and referred
   to by a single key. See [message usage](using.messages) for more information.

views/
:  Views are plain PHP files which are used to generate HTML or other output. The view file is
   loaded into a [View] object and assigned variables, which it then converts
   into an HTML fragment. Multiple views can be used within each other.
   See [view usage](usings.views) for more information.

## Finding Files

The path to any file within the filesystem can be found by calling [Kohana::find_file]:

    // Find the full path to "classes/cookie.php"
    $path = Kohana::find_file('classes', 'cookie');

    // Find the full path to "views/user/login.php"
    $path = Kohana::find_file('views', 'user/login');


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
