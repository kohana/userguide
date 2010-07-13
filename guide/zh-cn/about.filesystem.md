# 级联文件系统

Kohana 文件系统单一的目录结构。
当使用 [Kohana::find_file] 加载一个文件时，系统会以下顺序搜索:

Application 路径
: 在 `index.php` 文件中常量被定义为 `APPPATH`，默认值是 `application`。

Module 路径
: 这是在 `APPPATH/bootstrap.php` 文件中使用 [Kohana::modules] 设置的一组数组。
  数组的每个值都会按照顺序搜索并添加进来。

System 路径
: 在 `index.php` 文件中常量被定义为 `SYSPATH`。默认值是 `system`。
所有 “core” 核心文件和类文件都在这里定义。

目录中的文件包含了优先顺序建立的从高到低的优先级，这就有可能使得具有"高等级"目录的同名文件的会重载任何可以低于它的文件内容。

![级联文件系统示意图](img/cascading_filesystem.png)

如果在 `APPPATH/views` 目录和 `APPPATH/views` 目录均有一个名为 `welcome.php` 视图文件，
当 `welcome.php` 被加载的时候由于 application 目录在文件系统的最上面所以只有它会被返回。

## 文件类型

目录的级别从高到低依次是 application，module 和 system 路径，分别都有下面的目录结构:

classes/
:  所有你想要 [autoload](using.autoloading) 的类库均保存在这里。
   本目录包含了控制器，模型和其他类库。所有的库文件都必须遵循[类的命名规则](about.conventions#classes)。

config/
:  配置文件是使用 [Kohana::config] 返回的数组项。
   详情请查阅[配置的用法](using.configuration)。

i18n/
:  多语言文件返回的包各国语言的字符串数组。多语言是使用 `__()` 方法实现。
   如果想把 "Hello, world" 多语言化，只需要调用 `__('Hello, world!')` 并设置 
   [I18n::$lang] 为  "zh-cn"。
   详情请查阅[多语言的用法](using.translation)。

messages/
:  消息文件是使用 [Kohana::message] 返回的字符串数组。消息和 i18n 文件唯一不同的就是无法多语言化，
   但是总是携程默认语言并通过单键引用。
   详情请查阅[消息的用法](using.messages)。

views/
:  视图是标准的 PHP 文件被用于生成 HTML。视图文件被加载到 [View] 对象中并得到变量的设置，
   最后在转换为 HTML 片段或其他输出。多个视图可以相互引用。
   详情请查阅[视图的用法](using.views)。

## 查找文件

使用 [Kohana::find_file] 方法可以找到在文件系统中任意路径下的文件:

    // 查询的路径 "classes/cookie.php"
    $path = Kohana::find_file('classes', 'cookie');

    // 查询的路径 "views/user/login.php"
    $path = Kohana::find_file('views', 'user/login');


## 第三方扩展

调用扩展并非限定在 Kohana 。
比如，如果你想使用 [DOMPDF](http://code.google.com/p/dompdf)，
只需把他复制到 `application/vendor/dompdf` 并加载 DOMPDF 的自动加载类:

    require Kohana::find_file('vendor', 'dompdf/dompdf/dompdf_config.inc');

现在无需再加载任何文件就可以使用 DOMPDF:

    $pdf = new DOMPDF;

[!!] 如果你想使用 DOMPDF 转换试图到 PDFs，可以试试 [PDFView](http://github.com/shadowhand/pdfview) 扩展。
