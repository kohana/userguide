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

[!!] Stub
