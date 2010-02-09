# 公约

## 类名和文件位置

在 Kohana 系统中类名严格遵循命名公约才能够[自动加载](start.autoloading)。

类名的首字母必须大写，且使用下划线连接单词，千万要注意下划线的重要性，因为它直接关系到文件在文件系统中所存放的位置。

	类							文件
	
	Controller_Template			classes/controller/template.php
	Model_User					classes/model/user.php
	Model_Auth_User				classes/model/auth/user.php
	Auth						classes/auth.php

CamelCased 这样的类名是不允许使用的。

所有的类文件的文件名和目录名都必须是小写。

所有的类文件都应该存放在 `classes` 目录下面，它可以是在[级联文件系统](start.filesystem)的任何一级。

Kohana 3 类的*类型*已经不同于包括 Kohana2.x 在内的其他框架。它不再有 '辅助函数' 和 '类库' 区别 - 在 Kohana 3 中类可以实现任何接口，既可以是完全静态的方法（类似于辅助函数） 也可以是混合的方法（比如，单例模式）

## 编码样式

鼓励大家遵循 Kohana 的编码样式，Kohana 基于 [BSD/Allman style](http://en.wikipedia.org/wiki/Indent_style#BSD.2FAllman_style) 的编码样式（这里还有一些更多[关于 Kohana 编码样式的描述](http://dev.kohanaphp.com/wiki/kohana2/CodingStyle)）