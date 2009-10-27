# Hello, World

差不多每个框架提供的教程实例都会包括 Hello，World 这样的例子，于是我们也遵循这样的传统!

下面我们将要创建一个非常非常基础的 Hello，World，然后我们将它慢慢扩展符合 MVC 架构的样例。

## 基础框架

首先，我们先要创建一个控制器，让 Kohana 可用于处理请求。

在 application 目录下创建 `application/classes/controller/hello.php` 文件并敲入下面代码：

    <?php defined('SYSPATH') OR die('No Direct Script Access');

	Class Controller_Hello extends Controller
	{
		function action_index()
		{
			echo 'hello, world!';
		}
	}

接着，让我们讲解一下上面的代码：

`<?php defined('SYSPATH') OR die('No Direct Script Access');`
:	首先你应该知道代码开头是 PHP 的开始标签（如果你不知道请先好好[学习 PHP](http://php.net)）。紧跟着它后面的是一段检测代码，以它来确保此文件是由 Kohana 来加载的。它会阻止访问者由 URL 上面直接访问本文件。

`Class Controller_Hello extends Controller`
:	这行代码是声明我们的控制器，每个控制器类都必须使用 `Controller_` 作为前缀，下划线的作用是划定文件夹路径下的控制器（详情请参见 [公约和样式](start.conventions)）。每个控制器都必须继承基控制器 `Controller` 类，它提供了一个控制器的标准结构。


`function action_index()`
:	这里在我们的控制器定义了一个 "index" 动作。如果用户没有指定动作，那么 Kohana 默认调用这个动作。（详情请参见[路由，URLs 和链接](tutorials.urls)）

`echo 'hello, world!';`
:	这行就是输出 Hello world 的语句！

现在，打开浏览器敲入 http://your/kohana/website/index.php/hello 你将会看到：

![Hello, World!](img/hello_world_1.png "Hello, World!")

## 继续增强

我们在上一节所做的是创建一个*非常*基础的 Kohana 应用是多么的容易。（实际上它太基本了以至于你可能不会再敲一次！）

如果你曾经听说过的 MVC 或者你可能已经意识到在控制器中输出内容是严格遵循 MVC 原则的。

使用 MVC 框架编程的正确方法是使用_视图_来处理程序的显示，而且最好是使用控制器来做 – 控制请求的流量！

让我们略微的改造原始的代码：

    <?php defined('SYSPATH') OR die('No Direct Script Access');

	Class Controller_Hello extends Controller_Template
	{
		public $template = 'site';

		function action_index()
		{
			$this->template->message = 'hello, world!';
		}
	}

`extends Controller_Template`
:	现在我们继承了模板控制器（Template Controller），使用它可以更加方便在控制器中使用视图。

`public $template = 'site';`
:	模板控制器需要知道你想要使用什么模板文件。它会自动加载这个变量中定义的视图并返回一个视图对象。

`$this->template->message = 'hello, world!';`
:	`$this->template` 是我们站点模板的视图对象引用。这里我们分配一个名为 "message" 的变量其值为 "hello, world!" 到视图中。

现在让我们尝试运行代码...

<div>{{userguide/examples/hello_world_error}}</div>

出于某种原因 Kohana 会抛出一个不稳定的而没有正常显示我们期望的信息。

如果我们仔细查看错误信息，我们可以发现 View 库无法找到我们设定的模板文件，这可能是我们还没有创建它 – *doh*！（译注：doh 表达当发现事情朝坏的、不随人意的方向发展或某人说了傻话、做了蠢事时的情绪低落）

马上开始创建视图文件 `application/views/site.php`：

	<html>
		<head>
			<title>We've got a message for you!</title>
			<style type="text/css">
				body {font-family: Georgia;}
				h1 {font-style: italic;}

			</style>
		</head>
		<body>
			<h1><?php echo $message; ?></h1>
			<p>We just wanted to say it! :)</p>
		</body>
	</html>

再次刷新刚才的错误页面，怎么样看到正确的结果了吧：

![hello, world! We just wanted to say it!](img/hello_world_2.png "hello, world! We just wanted to say it!")

## 第三阶段 – 成果！

在本教程中你已经学会如何创建和使用控制器，以及使用视图分离逻辑来显示视图。

这绝对是一个非常基本教程来介绍如何使用 Kohana 工作，且它根本就不会影响你的潜力使用它来开发应用。
