# 从 2.3.x 升级

Kohana v3 大部分功能都不同于 Kohana 2.3 版本，下面列出了一系列的升级建议：

## 命名约定

在 2.x 体系中不同的类的'类型'（比如 controller，model 等）使用后缀来加以区分。文件夹在模型/控制器目录下没有任何类名的关系。

在 3.0 版本中废弃了上面的形式转而使用 Zend framework 的文件体系的约定，也就是类名包含类名和其路径，之间是有下划线分割而不是斜杠符（比如 `/some/class/file.php` 变为了 `Some_Class_File`） 
详情请参见 [约定文档](start.conventions)

## Input 库

Input 库已经从 3.0 版本中移除，请使用 `$_GET` 和 `$_POST` 获取。

### XSS 保护

假如你需要使用 XSS 清除用户输入数据，你可以使用 [Security::xss_clean] 处理输入数据，比如：

	$_POST['description'] = security::xss_clean($_POST['description']);

你也可以把 [Security::xss_clean] 当作 [Validate] 类的过滤器使用：

	$validation = new Validate($_POST);
	
	$validate->filter('description', 'Security::xss_clean');

### POST & GET

Input 库有一个最大的方便之处在于如果你试图从一个超全域阵列（superglobal arrays）访问它的值，假若其值不存在 Input 库则会返回一个指定的默认值，比如：

	$_GET = array();
	
	// $id 获得的值是 1
	$id = Input::instance()->get('id', 1);
	
	$_GET['id'] = 25;
	
	// $id 现在获得的值是 25
	$id = Input::instance()->get('id', 1);

在 3.0 版本你可以使用 [Arr::get] 方法实现同样的效果：

	$_GET = array();
	
	// $id 获得的值是 1
	$id = Arr::get($_GET, 'id', 1);
	
	$_GET['id'] = 42;
	
	// $id 现在获得的值是 42
	$id = Arr::get($_GET, 'id', 1);

## ORM 库

自 2.3 版本到现在已经有一些主要的改动，下面是常见的通用升级问题：

### 成员变量

现在所有的成员变量都添加了 下划线(_) 作为前缀而且无法再通过 `__get()` 方法获得访问权利。相反的你可以把属性名并去掉下划线当作函数去调用。

例如，在 2.3 版本中有一个 `loaded` 属性，现在改名为 `_loaded` 并且如果需要在外边类库中访问此属性只需要 `$model->loaded()`。

### 关系

在 2.3 版本中如果你想要迭代一个模型相关对象的话，你需要：

	foreach($model->{relation_name} as $relation)

然而，在新的系统中这已经失效。在 2.3 版本中任何使用 Databate 库生成的查询都是在全局作用域生成，这就意味着你不能同时尝试和构建两个查询语句。这里有个例子：

# TODO: 需要一个具体的实例!!!!
	
第二此查询则会失效而不能查询，内部查询将 '继承' 作为第一条件，从而造成混乱。在 3.0 版本中此问题得到了有效的解决，创建每条查询都是其自身的作用域之中，尽管如此，这也意味着有些东西没法按实际的预期正常工作。这里有个例子：

	foreach(ORM::factory('user', 3)->where('post_date', '>', time() - (3600 * 24))->posts as $post)
	{
		echo $post->title;
	}

[!!] (相关新的查询语法请查看 [Database 教程](tutorials.databases))

在 2.3 版本中你希望它可以返回用户为 3 且 `post_date` 在最近 24 小时内发布的所有 posts 的迭代器，然而相反的，它将适用 where 语句到 user 模型中并返回带有指定加入语句的 'Model_Post' 对象。

为了达到 2.3 版本的同样效果，你只需要略微修改结构即可：

	foreach(ORM::factory('user', 3)->posts->where('post_date', '>', time() - (36000 * 24))->find_all() as $post)
	{
		echo $post->title;
	}

这同样也应用到 `has_one` 关系中：

	// 错误
	$user = ORM::factory('post', 42)->author;
	// 正确
	$user = ORM::factory('post', 42)->author->find();

### Has and belongs to many relationships

在 2.3 版本中你可以设置 `has_and_belongs_to_many` 关系。但是在 3.0 版本此功能已经融合到了 `has_many` *through*。

在你的模型中定义一个 `has_many` 关系到其他模型中，并且添加一个 `'through' => 'table'` 属性，其中 `'table'` 是连接表的名称。比如（posts<>categories）：

	$_has_many = array
	(
		'categories' => 	array
							(
								'model' 	=> 'category', // 外部模型
								'through'	=> 'post_categories' // 连接表
							),
	);

如果你的数据库配置设置了表前缀，这也不用担心去添加表前缀。

### 外键

如果你想在 2.x 版本的 ORM 中覆写一个外键，你必须指定关系属于谁，并且你的新外键在成员变量 `$foreign_keys` 之中。

在 3.0 版本中你只需要在关系数组中定义一个 `foreign_key` 键即可，比如：

	Class Model_Post extends ORM
	{
		$_belongs_to = 	array
						(
							'author' => array
										(
											'model' 		=> 'user',
											'foreign_key' 	=> 'user_id',
										),
						);
	}

在上面的实例中我们应该在 posts 表中存在一个 `user_id` 字段。



In has_many relationships the `far_key` is the field in the through table which links it to the foreign table & the foreign key is the field in the through table which links "this" model's table to the through table.

考虑以下设定，"Posts" have and belong to many "Categories" through `posts_sections`.

| categories | posts_sections 	| posts   |
|------------|------------------|---------|
| id		 | section_id		| id	  |
| name		 | post_id			| title   |
|			 | 					| content |

		Class Model_Post extends ORM
		{
			protected $_has_many = 	array(
										'sections' =>	array(
															'model' 	=> 'category',
															'through'	=> 'posts_sections',
															'far_key'	=> 'section_id',
														),
									);
		}
		
		Class Model_Category extends ORM
		{
			protected $_has_many = 	array (
										'posts'		=>	array(
															'model'			=> 'post',
															'through'		=> 'posts_sections',
															'foreign_key'	=> 'section_id',
														),
									);
		}


显然，这里的别名设定是有点疯狂，但它是如何让 foreign/far 键很好工作的绝佳范例。

### ORM 迭代器

`ORM_Iterator` 也是值得注意的改动，它已经融合到了 Database_Result 之中。

如果你想要获得带有对象主键的 ORM 对象数组，你只需要调用 [Database_Result::as_array]，比如：

		$objects = ORM::factory('user')->find_all()->as_array('id');

其中的 `id` 就是 user 表的主键。

## Router 库

在 2.x 版本中有一个 Router 库用于处理主要的请求工作。它允许你在 `config/routes.php` 配置文件中定义基本的路由，而且它还允许支持自定义的正则表达式路由，尽管如此，如果你想做极端的话它就显得相当呆板。

## 路由

在 3.0 版本中路由系统（现在成为请求系统）有了更多的灵活度。路由现在全部定义在 bootstrap 文件中（`application/bootstrap.php`）以及模块（Module）的 init.php 文件之中（`modules/module/init.php`）。（另外值得一提的是，现在的路由是按照他们定义的顺序评估）

替换定义的路由数组，你现在为每个路由创建一个新的 [Route] 对象。不像在 2.x 体系一样没有必要映射一个 uri 到另一个。相反的你使用标记段（比如，controller，method，id）的变量来指定 uri 模式。

例如，在老系统的正则：

	$config['([a-z]+)/?(\d+)/?([a-z]*)'] = '$1/$3/$1';

需要映射 uri 的 `controller/id/method` 为 `controller/method/id`，在 3.0 版本中这样修改：

	Route::set('reversed','(<controller>(/<id>(/<action>)))')
			->defaults(array('controller' => 'posts', 'action' => 'index'));

[!!] 每个 uri 都必须指定一个独一无二的名称（这里定义的是 `reversed`），其背后的原因是解释在 [URL 教程](tutorials.urls) 之中。

尖括号的内容会当作动态解析部分。圆括号的内容则会当作是可选或不必要的字段。如果你只是想匹配 uris 的开头是 admin，你只需要：

	Rouse::set('admin', 'admin(/<controller>(/<id>(/<action>)))');

但，如果你想用户必须指定一个控制器：

	Route::set('admin', 'admin/<controller>(/<id>(/<action>))');
	
同样，Kohana 不使用任何的 '默认的默认项'。如果你想让 Kohana 去设置默认 action 为 'index'，你只需要使用 [Route::defaults] 设置即可！如果你需要为 uri 字段自定义正则表达式，你只需要以 `segment => regex` 传递数组，比如：

	Route::set('reversed', '(<controller>(/<id>(/<action>)))', array('id' => '[a-z_]+'))
			->defaults(array('controller' => 'posts', 'action' => 'index'))

这会迫使 id 的值必须全部是小写字母或者是数字，下划线。

### Actions

还有一点我们必须要提到的，如果控制器中的方法可以通过网址访问，现在被称为 "actions"，且其前缀为 'action_'。比如，在上面的例中，如果用户访问 `admin/posts/1/edit`，那么 "actions" 就是 'edit' 而且方法在控制器将会是 `action_edit`。详情请参见 [URL 教程](tutorials.urls)

## Sessions

以下方法不再存在：Session::set_flash()，Session::keep_flash() 和 Session::expire_flash() 方法，替代这些废弃方法的函数你可以使用 [Session::get_once]。

## URL 辅助函数

URL 辅助函数仅做了略微的改动 - `url::redirect()` 方法转移到了 `$this->request->redirect()` 之中（包含控制器）/ `Request::instance()->redirect()`

`url::current` 现在替换为了 `$this->request->uri()` 

## Valid / Validation

这恋歌类现在已经合并为一个类并命名为 `Validate`.

对于校验数组的语法也有些改动：

	$validate = new Validate($_POST);
	
	// 应用一个过滤器到所有数组项中
	$validate->filter(TRUE, 'trim');
	
	// 定义规则使用 rule() 方法
	$validate
		->rule('field', 'not_empty')
		->rule('field', 'matches', array('another_field'));
	
	// 为单字段设置多个规则也使用 rules() 方法，以 rules => params 的数组方式作为第二参数
	$validate->rules('field', 	array(
									'not_empty' => NULL,
									'matches'	=> array('another_field')
								));

为保证定义明确，其中 'required' 规则现已经改名为 'not_empty'。

## View 库

对于 View 库也有一些值得注意的主要改动。

在 2.3 版本中视图在其处理的控制器中调用呈现，并允许你使用 `$this` 作为视图应用引用到控制器中。这一点在 3.0 版本改变了。视图现在呈现在一个空白的作用域，如果你需要在视图中使用 `$this`，你可以使用 [View::bind] 绑定一个引用 - `$view->bind('this', $this)`

It's worth noting, though, that this is *very* bad practice as it couples your view to the controller, preventing reuse. 推荐的方法是像下面这样去传递必备的变量到视图中：

	$view = View::factory('my/view');
	
	$view->variable = $this->property;
	
	// 或者如果你想使用连接方式
	
	$view
		->set('variable', $this->property)
		->set('another_variable', 42);
		
	// 不推荐
	$view->bind('this', $this);

因为视图在一个空的作用域呈现，而 `Controller::_kohana_load_view` 现在是多余的了。如果你想在它呈现之前修改视图（比如，添加一个站点的菜单），你可以使用 [Controller::after]
	
	Class Controller_Hello extends Controller_Template
	{
		function after()
		{
			$this->template->menu = '...';
			
			return parent::after();
		}
	}
