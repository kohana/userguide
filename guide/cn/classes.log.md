#Log

###函数

##::instance()
静态函数，返回 log 的实例化对象，如果不存在则会创建它。

##attach()
附加一个写入器到 log 类中。它会创建一个新的带有一个参数的 Kohana_Log_File 实例化并附加到 log 类中。它允许日志写入器去执行所有日志的写入。

    Kohana::$log->attach(new Kohana_Log_File(APPPATH.'logs'));

第二个参数只允特性的信息类型赋值于日志写入器。默认为可选的所有消息类型。

##detach()
从写日志中终止日记写入器。

##add($type, $message)
创建一个带有时间戳的日志信息。

    Kohana::$log->add(E_WARNING, 'Something went a little wrong');

##write()
将所有的队列日志执行写入操作。
