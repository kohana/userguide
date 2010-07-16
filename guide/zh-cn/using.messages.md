# 消息的基本使用

Kohana 消息(messages) 是一种友好化短小的词或短语的字符串，通常被叫做 "key"。消息通过 [Kohana::message] 方法调用访问并返回整个消息组或者单个消息。

举个简单的例子，当用户没有登录并试图访问一个需要验证的页面，通常会一个类似"你必须登录后才能访问此页面"的提示，而此消息可以保存在 auth 文件的 'must_login' 的键值中:

    $message = Kohana::message('auth', 'must_login');

消息并不能直接翻译，如果想翻译一个消息，你需要配合使用[翻译函数](using.translation):

    $translated = __(Kohana::message('auth', 'must_login'));

[!!] 在 Kohana v2 版本中，消息系统是可以翻译的，尽管如此，我们还是强烈推荐大家使用新的翻译系统代替消息，因为当翻译不存时它可以提供可读性文本。

## 消息文件

所有的消息文件都是保存在 `messages/` 目录下的纯 PHP 文件的配对数组:

    <?php defined('SYSPATH') or die('No direct script access.');

    return array(
        'must_login' => '你必须登录后才能访问此页面',
        'no_access'  => '你没有访问此页面的权限',
    );

消息文件有些类似于[配置文件](using.configuration#config-files)，它们都可以合并在一起。这意味着所有的消息都可以设置为一个数组并保存在 'auth' 文件之中。因此当你需要一个新的 'auth' 文件而没有必要创建多个重复文件。
