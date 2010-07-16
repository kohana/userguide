# 调试

Kohana 默认加载一些功能强大的功能来辅助调试程序。

最常使用也是最基本的 [Kohana::debug] 方法。这个简单的方法会显示任何的变量数字，类似于 [var_export](http://php.net/var_export) 或 [print_r](http://php.net/print_r)，但是使用了 HTML 格式输出。

    // 显示 $foo 和 $bar 变量的相关信息
    echo Kohana::debug($foo, $bar);

Kohana 也提供一个方法 [Kohana::debug_source] 来显示特定文件的源代码。

    // 显示当前行的源代码
    echo Kohana::debug_source(__FILE__, __LINE__);

如果你希望显示你应用文件的信息而不泄漏安装路径，你可以使用[Kohana::debug_path]:

    // 显示 "APPPATH/cache" 而不是真实路径
    echo Kohana::debug_path(APPPATH.'cache');
