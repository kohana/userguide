# 错误/异常句柄

Kohana 同时提供了异常句柄和错误句柄使用 PHP 的 [ErrorException](http://php.net/errorexception) 类转换错误为异常。关于错误的详情和通过句柄显示应用程序的内部状态：

1. Exception 类
2. 错误等级
3. 错误信息
4. 带有行高亮的错误源
5. 执行流程的[调试跟踪](http://php.net/debug_backtrace)
6. 包含（Include）文件，加载扩展，全局变量

## 实例

点击任何一个链接可以切换显示额外的信息：

<div>{{userguide/examples/error}}</div>

## 显示错误/异常句柄

如果您不希望使用内部错误句柄，您可以调用 [Kohana::init] 时禁用它：

~~~
Kohana::init(array('errors' => FALSE));
~~~