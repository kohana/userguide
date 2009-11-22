# 路由，URLs 和链接

本节讲述了关于 Kohana 的请求路由， URL 生成以及链接的基本使用。

## 路由（Route）

在上面提到的[请求流程](about.flow)一节中，一个请求通过 [Request] 类来寻找匹配 [Route] 并且加载对应的控制器以执行请求。本系统提供了更大的灵活性以及常规默认行为。

如果你查看了 `APPPATH/bootstrap.php` 的代码，你会发现会有包含下面的一段代码，它会在请求处理对在 [Request::instance] 关闭前立即执行。

    Route::set('default', '(<controller>(/<action>(/<id>)))')
      ->defaults(array(
        'controller' => 'welcome',
        'action'     => 'index',
      ));

这是按照 `(<controller>(/<action>(/<id>)))` 的 uri 格式化的 ‘默认’ 路由设置。其中 *key* 使用 '<>' 括起来，而*可选*部分使用 '()' 括起来。既然是这样，上面的路由设置说明，所有的 uri 都是可选的，所以如果对于一个空的 uri 要匹配，它会去匹配默认的控制器和 action，也就是上面代码将会匹配并加载 `Controller_Welcome` 类，调用 `action_index` 方法以执行请求。

需要注意的是，任何的字符都是允许使用 `()<>` 括起来，对于 `/` 并没有特殊含义。在默认路由中 `/` 是被用来当作静态分隔符，但是如果正确的正则表达式是不会限制你如果格式化你的路由。

### 目录

对于某些原因你可能需要把一些控制器放置在子目录当作。比如这里有一个 amdin 子目录：

    Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
      ->defaults(array(
        'directory'  => 'admin',
        'controller' => 'home',
        'action'     => 'index',
      ));

该路由规定了 uri 中必须以 `admin` 开头去匹配，并且默认的，这个目录是静态被分配到 `admin`。如果现在有一个请求到 `admin/users/create` 那么它会加载 `Controller_Admin_Users` 类并调用 `action_create` 方法。

### 模式

Kohana 路由系统使用 perl 正则表达式来处理匹配。默认情况下 key（使用 `<>` 括起来的）只能根据 `[a-zA-Z0-9_]++` 来匹配，但是你可以为每个 key 以数组的形式自定义不同的模式分配到 [Route::set]。继续扩充上面的例子，如果你之前定义了一个 amdin 和 addiliate 段。其实可以使用路由分割或者下面的方式指定它们：

    Route::set('sections', '<directory>(/<controller>(/<action>(/<id>)))'
      array((
        'directory' => '(admin|affiliate)'
      ))
      ->defaults(array(
        'controller' => 'home',
        'action'     => 'index',
      ));
      
上面的设置同时实现了两个段的路由映射，'admin' 和 'affiliate' 会映射到相对于的目录控制器里但是它会覆盖默认的路由设置。

### 更多路由样例

这里还有一些其他使用技巧，下面是一些样例：

    /*
     * 验证的缩写
     */
    Route::set('auth', '<action>',
      array(
        'action' => '(login|logout)'
      ))
      ->defaults(array(
        'controller' => 'auth'
      ));
      
    /*
     * 多样式 feeds
     *   452346/comments.rss
     *   5373.json
     */
    Route::set('feeds', '<user_id>(/<action>).<format>',
      array(
        'user_id' => '\d+',
        'format' => '(rss|atom|json)',
      ))
      ->defaults(array(
        'controller' => 'feeds',
        'action' => 'status',
      ));
    
    /*
     * 静态页面
     */
    Route::set('static', '<path>.html',
      array(
        'path' => '[a-zA-Z0-9_/]+',
      ))
      ->defaults(array(
        'controller' => 'static',
        'action' => 'index',
      ));
      
    /*
     * 你不喜欢斜线号？那我们使用冒号分隔。
     *   EditGallery:bahamas
     *   Watch:wakeboarding
     */
    Route::set('gallery', '<action>(<controller>):<id>',
      array(
        'controller' => '[A-Z][a-z]++',
        'action'     => '[A-Z][a-z]++',
      ))
      ->defaults(array(
        'controller' => 'Slideshow',
      ));
      
    /*
     * 快速搜索
     */
    Route::set('search', ':<query>', array('query' => '.*'))
      ->defaults(array(
        'controller' => 'search',
        'action' => 'index',
      ));


路由的匹配是按照顺序指定的所以大家需要知道的是，如果你在加载模块之后设置路由，模块也可以指定路由程序相冲突的路由。如果是因为这个为什么默认路由会在最后设置，所以字段能够以路由的时候最好先做测试。
      
### 请求参数

The directory, controller and action can be accessed from the [Request] instance in either of these two ways:

    $this->request->action;
    Request::instance()->action;
    
All other keys specified in a route can be accessed from within the controller via:

    $this->request->param('key_name');
    
The [Request::param] method takes an optional second argument to specify a default return value in case the key is not set by the route. If no arguments are given, all parameters are returned as an associative array.

### 公约

The established convention is to either place your custom routes in the `MODPATH/<module>/init.php` file of your module if the routes belong to a module, or simply insert them into the `APPPATH/bootstrap.php` file above the default route if they are specific to the application. Of course, they could also be included from an external file or even generated dynamically.
    
## URLs

Along with Kohana's powerful routing capabilities are included some methods for generating URLs for your routes uris. You can always specify your uris as a string using [URL::site] to create a full URL like so:

    URL::site('admin/edit/user/'.$user_id);

However, Kohana also provides a method to generate the uri from the route's definition. This is extremely useful if your routing could ever change since it would relieve you from having to go back through your code and change everywhere that you specified a uri as a string. Here is an example of dynamic generation that corresponds to the `feeds` route example from above:

    Route::get('feeds')->uri(array(
      'user_id' => $user_id,
      'action' => 'comments',
      'format' => 'rss'
    ));

Let's say you decided later to make that route definition more verbose by changing it to `feeds/<user_id>(/<action>).<format>`. If you wrote your code with the above uri generation method you wouldn't have to change a single line! When a part of the uri is enclosed in parentheses and specifies a key for which there in no value provided for uri generation and no default value specified in the route, then that part will be removed from the uri. An example of this is the `(/<id>)` part of the default route; this will not be included in the generated uri if an id is not provided.

One method you might use frequently is the shortcut [Request::uri] which is the same as the above except it assumes the current route, directory, controller and action. If our current route is the default and the uri was `users/list`, we can do the following to generate uris in the format `users/view/$id`:

    $this->request->uri(array('action' => 'view', 'id' => $user_id));
    
Or if within a view, the preferable method is:

    Request::instance()->uri(array('action' => 'view', 'id' => $user_id));

## 链接

[!!] links stub
