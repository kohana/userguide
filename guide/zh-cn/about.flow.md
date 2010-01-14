# 请求流程

每个应用都遵循同样的流程：

1. 程序从 `index.php` 入口
2. 加载 `APPPATH/bootstrap.php`
3. [Request::instance] 调用处理的请求
    1. 检测每个路由直到发现匹配的
    2. 加载控制器并传递请求过来
	    * 每个 init.php 文件都可以自定义路由使用，当发现 init.php 文件并加载后，路由等配置也同样被加载
4. 调用 [Request::instance] 方法来处理请求
    1. 检测每个路由直到发现匹配的
    2. 加载控制器并传递请求进来
    3. 调用 [Controller::before] 方法
    4. 调用控制 action
    5. 调用 [Controller::after] 方法
5. 显示 [Request] 响应

控制器 action 可以通过基于请求参数的 [Controller::before] 方法改变。

[!!] Stub
