# 请求流程

每个应用都遵循同样的流程：

1. 程序从 `index.php` 入口
2. 加载 `APPPATH/bootstrap.php`
3. [Request::instance] 调用处理的请求
    1. 检测每个路由直到发现匹配的
    2. 加载控制器并传递请求过来
    3. 调用 [Controller::before] 方法
    4. 调用控制 action
    5. 调用 [Controller::after] 方法
4. 显示 [Request] 响应

控制器 action 可以通过基于请求参数的 [Controller::before] 方法改变。

[!!] Stub
