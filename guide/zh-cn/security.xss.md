# 跨站脚本(XSS)安全  

首先大家先要了解什么是 [XSS](http://wikipedia.org/wiki/Cross-Site_Scripting) 之后才能更好的包含自己。XSS 只能在 HTML 代码中才能触发，可能通过表单的输入或者从数据库结果显示。任何全局变量包括客户信息都可能被感染。这包括 `$_GET`，`$_POST` 和 `$_COOKIE` 中的数据。

## 预防措施

这里有一些简单的方法可以预防你的程序不受 XSS 的侵害。第一个方法是使用 [Security::xss] 方法处理所有全局变量的输入数据。如果你不想让变量里有 HTML 代码，你可以使用 [strip_tags](http://php.net/strip_tags) 从值中移除所有的 HTML 标签。

[!!] 如果你运行用户提交 HTML 到你的程序之中，最好的推荐方法是使用像 [HTML Purifier](http://htmlpurifier.org/) 或 [HTML Tidy](http://php.net/tidy) 这样的 HTML 代码清理工具。

第二个方法是始终去转义输入的 HTML。[HTML] 类提供大多数的标签生成，其中包括脚本（script）和样式表（stylesheet）链接，超级链接，图片，Email（emailto）链接。任何不可信的内容都会使用 [HTML::chars] 去转义。

## 参考资料

* [OWASP XSS Cheat Sheet](http://www.owasp.org/index.php/XSS_(Cross_Site_Scripting)_Prevention_Cheat_Sheet)
