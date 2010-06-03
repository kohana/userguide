# 路由，URLs 和链接

本节讲述了关于 Kohana 的请求路由， URL 生成以及链接的基本使用。

## 路由（Routing）

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

    Route::set('sections', '<directory>(/<controller>(/<action>(/<id>)))',
      array(
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

目录，控制器和 action 都可以通过 [Request] 实例化后的两种方式访问:

    $this->request->action;
    Request::instance()->action;
    
所有其他定义在路由中的键值可以从内控制器中访问:

    $this->request->param('key_name');
    
[Request::param] 方法提供一个可选的第二参数，用于返回默认的没有找到路由设置键值的值。如果没有指定第二参数，返回包含所有键值的数组。

### 约定

约定适用于自定义的扩展的 `MODPATH/<module>/init.php` 文件或者 `APPPATH/bootstrap.php` 文件默认设置的路由。当然，你也可以采用外部加载，甚至是动态加载的方式。
    
## URLs

随着 Kohana 路由功能的不断强大，加入了一些生成路由 URI 的方法。通常你可能在调用 [URL::site] 方法时指定的字符串来创建完整的 URL:

    URL::site('admin/edit/user/'.$user_id);

同时，Kohana 也提供另外一种从路由定义生成 URI 的方法。假如能够所以改变的路由的参数从而减轻代码的变更带来的烦恼，这是非常好的替代方法。下面提供一个使用 `feeds` 路由动态生成 URL 的例子:

    Route::get('feeds')->uri(array(
      'user_id' => $user_id,
      'action' => 'comments',
      'format' => 'rss'
    ));

比方说，你今后决定改变 `feeds/<user_id>(/<action>).<format>` 的路由定义作进一步的设计。
Let's say you decided later to make that route definition more verbose by changing it to `feeds/<user_id>(/<action>).<format>`. If you wrote your code with the above uri generation method you wouldn't have to change a single line! When a part of the uri is enclosed in parentheses and specifies a key for which there in no value provided for uri generation and no default value specified in the route, then that part will be removed from the uri. An example of this is the `(/<id>)` part of the default route; this will not be included in the generated uri if an id is not provided.

[Request::uri] 可能会是你经常使用的方法，它除了上面说明的功能外还可以设定当前的路由，目录，控制器和 actions 的值。如果我们当前的默认路由是 `users/list`，我们可以生成这样的格式 `users/view/$id`:

    $this->request->uri(array('action' => 'view', 'id' => $user_id));
    
或者在视图中，可取的方法:

    Request::instance()->uri(array('action' => 'view', 'id' => $user_id));

## 链接

[!!] links stub
