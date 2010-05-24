# 请求流程

每个应用都遵循同样的流程：

1. 程序从 `index.php` 文件加载
2. 设置 application，module 和 system 目录到路径
3. 设置错误报告的级别
4. 加载 install.php 文件（如果存在的话）
5. 加载 [Kohana] 类
6. 加载 `APPPATH/bootstrap.php` 引导文件
7. 调用 [Kohana::init] 方法初始化错误句柄，缓存和日志设置
8. 设置 [Kohana_Config] 读取器和 [Kohana_Log] 记录器
9. 调用 [Kohana::modules] 方法加载激活的模块
    * 模块路径附加到[文件级联系统](about.filesystem).
    * 加载模块的 `init.php` 文件（如果存在的话）
    * `init.php` 文件可以增强系统环境设置，同时也包括路由
10. [Route::set] 会被多次调用来定义[程序路由](using.routing)
11. 调用 [Request::instance] 方法来处理请求
    1. 检测每个路由直到发现匹配的
    2. 加载控制器实例化并传递请求
    3. 调用 [Controller::before] 方法
    4. 调用控制器的方法生成请求的响应
    5. 调用 [Controller::after] 方法
        * 当使用 [HMVC sub-requests](about.mvc) 时以上五步会多次循环调用
12. 显示 [Request] 响应

## index.php

Kohana follows a [front controller] pattern, which means that all requests are sent to `index.php`. This keeps allows a very clean [filesystem](about.filesystem) design. In `index.php`, there are some very basic configuration options available. You can change the `$application`, `$modules`, and `$system` paths and set the error reporting level.

The `$application` variable lets you set the directory that contains your application files. By default, this is `application`. The `$modules` variable lets you set the directory that contains module files. The `$system` variable lets you set the directory that contains the default Kohana files.

You can move these three directories anywhere. For instance, if your directories are set up like this:

    www/
        index.php
        application/
        modules/
        system/

You could move the directories out of the web root:

    application/
    modules/
    system/
    www/
        index.php

Then you would change the settings in `index.php` to be:

    $application = '../application';
    $modules     = '../modules';
    $system      = '../system';

Now none of the directories can be accessed by the web server. It is not necessary to make this change, but does make it possible to share the directories with multiple applications, among other things.

[!!] There is a security check at the top of every Kohana file to prevent it from being accessed without using the front controller. However, it is more secure to move the application, modules, and system directories to a location that cannot be accessed via the web.

### Error Reporting

By default, Kohana displays all errors, including strict mode warnings. This is set using [error_reporting](http://php.net/error_reporting):

    error_reporting(E_ALL | E_STRICT);

When you application is live and in production, a more conservative setting is recommended, such as ignoring notices:

    error_reporting(E_ALL & ~E_NOTICE);

If you get a white screen when an error is triggered, your host probably has disabled displaying errors. You can turn it on again by adding this line just after your `error_reporting` call:

    ini_set('display_errors', TRUE);

Errors should **always** be displayed, even in production, because it allows you to use [exception and error handling](debugging.errors) to serve a nice error page rather than a blank white screen when an error happens.

