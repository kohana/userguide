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

Kohana 遵循[前端控制器]模式，因此所有的请求都要发送到 `index.php` 文件。这样就可以允许保持一个非常整洁的[文件系统](about.filesystem)设计。在 `index.php` 文件中有一些非常重要而又基础的配置变量。你可以改变 `$application`，`$modules` 和 `$system` 的路径以及设置错误报告级别。

`$application` 变量让目录包含着你的程序文件。默认情况下，就是 `application` 目录。`$modules` 变量让目录包含着你的扩展文件。默认情况下。`$system` 变量让目录包含着默认的 Kohana 文件。默认情况下。

你可以移动下面三个目录到任意路径。假如你的目录结构是:

    www/
        index.php
        application/
        modules/
        system/

你想转移这些目录到 web 目录以外:

    application/
    modules/
    system/
    www/
        index.php

那么你应该在 `index.php` 文件改变下面变量的配置:

    $application = '../application';
    $modules     = '../modules';
    $system      = '../system';

Now none of the directories can be accessed by the web server. It is not necessary to make this change, but does make it possible to share the directories with multiple applications, among other things.

[!!] There is a security check at the top of every Kohana file to prevent it from being accessed without using the front controller. However, it is more secure to move the application, modules, and system directories to a location that cannot be accessed via the web.

### 错误报告

默认情况下，Kohana显示所有错误，包括严格的警告。

    error_reporting(E_ALL | E_STRICT);

对于已经上线并在运行的程序，一个保守的推荐，可以忽略掉提醒:

    error_reporting(E_ALL & ~E_NOTICE);

如果在错误被触发后得到的是一个空白的结果，你的服务器可能关闭了错误提示。你可以在 `error_reporting` 调用前使用下面的代码开启错误提醒:

    ini_set('display_errors', TRUE);

在发送错误提示时，错误应该**实时**显示，甚至是在上线发布之后，因为它允许你使用[异常和错误句柄](debugging.errors) 指引到一个友好的错误页面从而代替空白的页面。
