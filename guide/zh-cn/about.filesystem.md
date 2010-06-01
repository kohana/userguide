# 级联文件系统

Kohana 文件系统单一的目录结构。
当使用 [Kohana::find_file] 加载一个文件时，系统会以下顺序搜索:

Application 路径
: 在 `index.php` 文件中常量被定义为 `APPPATH`，默认值是 `application`。

Module 路径
: This is set as an associative array using [Kohana::modules] in `APPPATH/bootstrap.php`.
  Each of the values of the array will be searched in the order that the modules
  are added.

System 路径
: 在 `index.php` 文件中常量被定义为 `SYSPATH`。默认值是 `system`。
所有 “core” 核心文件和类文件都在这里定义。

目录中的文件是按照上面的 1，2，3 的顺序建立的从高到低的优先级，这就有可能使得具有"高等级"目录的同名文件的会重载任何可以低于它的文件内容。

Files that are in directories higher up the include path order take precedence
over files of the same name lower down the order, which makes it is possible to
overload any file by placing a file with the same name in a "higher" directory:

![级联文件系统示意图](img/cascading_filesystem.png)

If you have a view file called `welcome.php` in the `APPPATH/views` and
`SYSPATH/views` directories, the one in application will be returned when
`welcome.php` is loaded because it is at the top of the filesystem.

## 文件类型

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
:  Views are a plain PHP file which is used to generate HTML. The view file is
   loaded into a [View] object and assigned variables, which it then converts
   into an HTML fragment. Multiple views can be used within each other.
   See [view usage](usings.views) for more infromation.

## 查找文件

The path to any file within the filesystem can be found by calling [Kohana::find_file]:

    // Find the full path to "classes/cookie.php"
    $path = Kohana::find_file('classes', 'cookie');

    // Find the full path to "views/user/login.php"
    $path = Kohana::find_file('views', 'user/login');


## 视图扩展

We call extensions that are not specific to Kohana "vendor" extensions.
For example, if you wanted to use [DOMPDF](http://code.google.com/p/dompdf),
you would copy it to `application/vendor/dompdf` and include the DOMPDF
autoloading class:

    require Kohana::find_file('vendor', 'dompdf/dompdf/dompdf_config.inc');

Now you can use DOMPDF without loading any more files:

    $pdf = new DOMPDF;

[!!] If you want to convert views into PDFs using DOMPDF, try the
[PDFView](http://github.com/shadowhand/pdfview) module.
