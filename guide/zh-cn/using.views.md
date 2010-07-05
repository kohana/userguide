# 视图的使用

视图是包含输出显示信息内容的文件。通常大多数情况下是 HTML，CSS 和 Javascript 或者其他任何内容（包括调用 AJAX 的 XML 或 JSON 的输出）。其主要目的是为了从程序中分离逻辑以获得可复用性和整洁代码。

然而事实上，视图本身也能传递变量等代码并输出数据。比如，循环产品信息的数组并输出每个产品的信息。视图仍然是 PHP 文件因此你可以正常的写任何代码。

# 创建视图文件

视图文件存在[文件系统](about.filesystem)中的 `views` 目录。你也可以在 `views` 目录下面创建子目录组织你的文件。下面所有的例子都是合理的视图文件:

    APPPATH/views/home.php
    APPPATH/views/pages/about.php
    APPPATH/views/products/details.php
    MODPATH/error/views/errors/404.php
    MODPATH/common/views/template.php

## 加载视图

[View] 对象通常在 [Controller] 内部通过使用 [View::factory] 方法创建。一般视图被赋值给 [Request::$response] 属性或其他视图。

    public function action_about()
    {
        $this->request->response = View::factory('pages/about');
    }

当视图对象如同上面的例子赋值给 [Request::$response]，必要时它会自动输出呈现。如果想获得视图输出的内容，你可以调用 [View::render] 方法或者强制转为字符串类型。当时视图输出呈现时，视图会被加载并生成 HTML 代码。

    public function action_index()
    {
        $view = View::factory('pages/about');

        // Render the view
        $about_page = $view->render();

        // Or just type cast it to a string
        $about_page = (string) $view;

        $this->request->response = $about_page;
    }

## 视图变量

一旦视图已经被加载，我们可以通过 [View::set] 和 [View::bind] 方法赋值变量。

    public function action_roadtrip()
    {
        $view = View::factory('user/roadtrip')
            ->set('places', array('Rome', 'Paris', 'London', 'New York', 'Tokyo'));
            ->bind('user', $this->user);

        // 视图拥有 $places 和 $user 变量
        $this->request->response = $view;
    }

[!!] `set()` 和 `bind()` 方法的区别在于 `bind()` 是引用赋值。如果你在变量定义之前使用 `bind()` 绑定了它。变量默认会被当作 `NULL` 创建。

### 全局变量

在程序中可能有多个视图文件而同时调用同样的变量。比如，在两个模板的 header 块中显示一个页面的相同标题而不同的内容。通过 [View::set_global] 和 [View::bind_global] 方法创建全局变量。

    // 赋值 $page_title 到所有的视图
    View::bind_global('page_title', $page_title);

假如程序中首页有三个视图需要输出呈现：`template`，`template/sidebar` 和 `pages/home`。首先，创建一个抽象类控制器去初始化视图模板:

    abstract class Controller_Website extends Controller_Template {

        public $page_title;

        public function before()
        {
            parent::before();

            // 定义 $page_title 变量到所有视图中使用
            View::bind_global('page_title', $this->page_title);

            // 加载视图为 $sidebar 变量到模板
            $this->template->sidebar = View::factory('template/sidebar');
        }

    }

下一步，在 home 控制器继承 `Controller_Website`:

    class Controller_Home extends Controller_Website {

        public function action_index()
        {
            $this->page_title = 'Home';

            $this->template->content = View::factory('pages/home');
        }

    }

## 视图嵌套

如果你想在视图中加载另外一个视图，这里提供两个方案。通过调用 [View::factory] 你可以实现沙盒加载视图。这意味着你可以使用 [View::set] 或 [View::bind] 赋值:

    // 只有 $user 变量可用在 "views/user/login.php" 视图文件
    <?php echo View::factory('user/login')->bind('user', $user) ?>

另外一种选择是直接加载视图，这会使得当前所有变量加载并在视图中使用:

    // 所有定义在此视图中的变量都会加载到 "views/message.php" 文件
    <?php include Kohana::find_file('views', 'user/login') ?>

另外，你也可以在整个 [Request] 中加载一个视图中:

    <?php echo Request::factory('user/login')->execute() ?>

这是一个 [HMVC](about.mvc) 的例子已确保它可以创建并从程序其他的 URL 调用。

# 升级 v2.x 版本

不同于 Kohana v2.x 版本，视图不在 [Controller] 环境中加载，因此你不能够把 `$this` 当作加载视图的控制器访问。传递控制器到视图必须这样实现:

    $view->bind('controller', $this);
