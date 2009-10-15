# 控制器

控制器模型和视图是程序的组成部分。控制器把改变的数据的信息传递模型或者从模型获取信。例如，数据库对数据的插入，更新和删除数据。控制器通过模型传递信息并传给视图，视图是用户的最终呈现。

控制器也可以当作 URL，详情请参见 [URLs and Links](start.urls)。 



## 控制器命名和解析

控制器的类名必须和文件名保持一致。 

**控制器公约**

* 控制器的文件名必须全部小写。比如 `articles.php`
* 全部放置在 **classes/controller** 或其子目录下面。比如 `classes/controller/articles.php`
* 控制器类必须同文件名一致的前提下首字母大写且使用 **Controller_** 做前缀。比如 `Controller_Articles`
* 控制器类必须继承 Controller 类做父类
* 所有 URI 映射的控制器方法必须添加 **action_** 前缀（比如 `action_do_something()`）



### 一个简单的控制器

首先，我们先创建一个简单的控制器，它可以输出 Hello World 到屏幕上面。

**application/classes/controller/article.php**
~~~
<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class Controller_Article extends Controller
{
    public function action_index()
    {
        echo 'Hello World!';
    }
}
~~~
完成上面代码之后，在浏览器敲入 yoursite.com/article（或没有使用 URL 重写的地址 yoursite.com/index.php/article），你就能看到：
~~~
Hello World
~~~
这个控制器就是这么简单。里面包含了上面所提到的所有公约。



### 高级控制器

在上面的例子中，访问 yoursite.com/article 且 URL 第二个分段为空是执行的 `index()` 方法。即：yoursite.com/article/index。

_如果 URL 的第二个分段不为空，它会调用其控制器所对应的方法函数。_

**application/classes/controller/article.php**
~~~
class Controller_Article extends Controller
{
    public function action_index()
    {
        echo 'Hello World!';
    }
 
    public function action_overview()
    {
        echo 'Article list goes here!';
    }
}
~~~
如果在浏览器敲入 yoursite.com/article/overview 你可以看到：
~~~
Article list goes here!
~~~


### 带参数的控制器

那么如果想显示指定的文章怎么办？比方说，我们要显示一篇名为 `your-article-title` 的文章，而这篇文章的 id 为 `1`。 

实现这个就好像是这样的 yoursite.com/article/view/**your-article-title/1**，最后的两个分段即使通过调用 view() 方法里面的两个参数实现的。 

**application/classes/controller/article.php**
~~~
class Controller_Article extends Controller
{
    public function action_index()
    {
        echo 'Hello World!';
    }
 
    public function action_overview()
    {
        echo 'Article list goes here!';
    }
 
    public function action_view($title, $id)
    {
        echo $id . ' - ' . $title;
        // you'd retrieve the article from the database here normally
    }
}
~~~
如果在浏览器敲入 yoursite.com/article/view/**your-article-title/1** 你可以看到：
~~~
1 - your-article-title
~~~
