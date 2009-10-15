# 通用配置

[!!] TODO：静态配置属性的说明

## 核心配置

任何一个新的 Kohana 安装之后第一件事情就是需要修改位于 `application/bootstrap.php` 配置文件的 [Kohana::init] 设置：

`boolean` errors
:   使用内部错误和异常句柄？（默认为 `TRUE`）设置为 `FALSE` 则在 Kohana 中关闭错误和异常句柄显示。

`boolean` profile
:   使用内部基准测试？（默认为 `TRUE`）设置为 `FALSE` 则为关闭内部分析器。
    禁用它可以获得最佳性能。

`boolean` caching
:   高速缓存之间的请求文件的位置？（默认是 `FALSE`）设置为 `TRUE` 开缓存在绝对路径的文件。
	这大大加速了 [Kohana::find_file] 方法，它有时会对性能产生重大影响。仅在产品环境开启做测试使用。

`string` charset
:   设置所有输入和输出的字符编码。（默认是 "utf-8"`）允许设置 [htmlspecialchars](http://php.net/htmlspecialchars) 和 [iconv](http://php.net/iconv) 允许的编码格式。

`string` base_url
:   程序的 URL（默认为 `"/"`）可以是一个完整或部分网址。比如 "http://example.com/kohana/" 或仅有 "/kohana/" 两者都工作。

`string` index_file
:   程序开始的 PHP 文件入口。（默认是 `"index.php"`）当你从 URL 地址上移除了 index 文件请设置为 `FALSE`。

`string` cache_dir
:   缓存文件目录。（默认是 `"application/cache"` 目录）且必须是**可写**属性。

## Cookie 设置

[Cookie] 类中有几个静态的属性可以设置，特别是上线中的产品。

`string` salt
:   Unique salt string that is used to used to enable [signed cookies](security.cookies)

`integer` expiration
:   默认的生命期限（单位以 秒 为单位）

`string` path
:   Cookie 可以写入的一个有效 URL 路径。

`string` domain
:   限制只有配置的域名地址才能访问 cookies

`boolean` secure
:   仅能通过 HTTPS 访问允许的 cookies

`boolean` httponly
:   仅能通过 HTTP 访问允许的 cookies（同时也禁用 Javascript 访问）

# 配置文件

配置文件是纯 PHP 文件，这类似于：

~~~
<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'setting' => 'value',
    'options' => array(
        'foo' => 'bar',
    ),
);
~~~

如果上面的配置文件名为 `myconf.php`，你可以通过下面代码调用：

~~~
$config = Kohana::config('myconf');
$options = $config['options'];
~~~

[Kohana::config] 也提供了一钟使用“逗号格式”访问配置数组中的键：

获得 "options" 数组：

~~~
$options = Kohana::config('myconf.options');
~~~

从 "options" 数组获得 "foo" 键：

~~~
$foo = Kohana::config('myconf.options.foo');
~~~

配置数组也可以当作对象访问，如果你喜欢下面的方法：

~~~
$options = Kohana::config('myconf')->options;
~~~