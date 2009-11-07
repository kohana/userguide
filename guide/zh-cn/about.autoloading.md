# 自动加载

Kohana 需要使用 PHP 自身的[自动加载](http://php.net/manual/language.oop5.autoload.php)。这个消除了不用调用 [include](http://php.net/include) 和 [require](http://php.net/require) 之前就可以使用类文件。

类也可以通过 [Kohana::auto_load] 方法加载，这使得从简单的类名称转换为文件名：

1. 类必须放置在[文件系统](start.filesystem)的 `classes/` 目录
2. 任何下划线字符转换为斜线
2. 文件名必须是小写的

当调用一个尚未加载类（比如，`Session_Cookie`），通过使用 [Kohana::find_file] 方法可以让 Kohana 搜索文件系统查找名为 `classes/session/cookie.php` 的文件。

## 自动加载器

[!!] 在 `application/bootstrap.php` 配置文件默认已经开启了自动加载器。

使用 [spl_autoload_register](http://php.net/spl_autoload_register) 添加额外的类加载器。