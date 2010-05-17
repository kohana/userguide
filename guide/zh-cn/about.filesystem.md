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
be returned when
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


## 视图

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

# 第三方扩展

We call extensions that are not specific to Kohana "vendor" extensions.
For example, if you wanted to use [DOMPDF](http://code.google.com/p/dompdf),
you would copy it to `application/vendor/dompdf` and include the DOMPDF
autoloading class:

    require Kohana::find_file('vendor', 'dompdf/dompdf/dompdf_config.inc');

Now you can use DOMPDF without loading any more files:

    $pdf = new DOMPDF;

[!!] If you want to convert views into PDFs using DOMPDF, try the
[PDFView](http://github.com/shadowhand/pdfview) module.
