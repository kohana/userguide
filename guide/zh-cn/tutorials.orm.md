# ORM {#top}

Kohana 3.0 包含一个强劲的 ORM 扩展，作用于记录模式，数据库内省来确定模型的列表。

ORM 扩展默认包含在 Kohana 3.0 之中，但是如要使用它则需要先开启它。修改 `application/bootstrap.php` 文件中的 [Kohana::modules] 方法并加载 ORM 扩展：

	Kohana::modules(array(
		...
		'orm' => MODPATH.'orm',
		...
	));

## 配置 {#configuration}

通过一些配置 ORM 才能工作。通过在模型类继承 ORM：  

	class Model_User extends ORM
	{
		...
	}

上面的例子中，模型会寻找默认数据库的 'users' 表。

### 模型配置属性

下面的属性是用于配置每个模型的：

Type      | Option          |  Description                   | Default value
----------|-----------------|--------------------------------| -------------------------
`string`  |  _table_name    | Table name to use              | 
`string`  | _db             | Name of the database to use    |`default`
`string`  | _primary_key    | Column to use as primary key   |`id`
`string`  | _primary_val    | Column to use as primary value |`name`

## 使用 ORM

### 加载一条记录

通过调用 [ORM::factory] 或 [ORM::__construct] 方法创建模型的实例化：

	$user = ORM::factory('user');
	// 或者
	$user = new Model_User();

构造函数和 factory 方法也接受一个主键值来加载模型数据：

	// 加载用户（user） ID 5
	$user = ORM::factory('user', 5);

使用 [ORM::loaded] 方法检查模型加载的记录是否正确加载。

### 搜索记录

ORM 支持大多数的 [Database] 方法来强劲驱动搜索模型中的数据。ORM 类的 `_db_methods` 属性列出了所有支持调用的方法列表。记录的搜索可以通过 [ORM::find] 和 [ORM::find_all] 方法调用获得。

	// 搜索活跃用户中名为 Bob 的第一条记录
	$user = ORM::factory('user')
		->where('active', '=', TRUE)
		->where('name', '=', 'Bob')
		->find();

	// 搜索名为 Bob 的所有用户
	$users = ORM::factory('user')
		...
		->find_all();
	
当你使用 [ORM::find_all] 搜索一批记录模型，你可以使用迭代从数据库结果中获取每条记录模型：

	foreach ($users as $user)
	{
		...
	}

### 取出模型属性

所有的模型属性都可以通过 PHP 的魔法方法 `__get` 和 `__set` 得到读写权。

	$user = ORM::factory('user', 5);
	
	// 输出用户名
	echo $user->name;

	// 更改用户名
	$user->name = 'Bob';

假如保存的信息/属性并不存在于模型表中，使用 `_ignored_columns` 来忽略数据成员。

	class Model_User extends ORM
	{
		...
		protected $_ignored_columns = array('field1', 'field2', ...)
		...
	}

### 创建并存储记录

[ORM::save] 方法既可以用于创建新记录也可作用于更新现有记录。

	// 创建新记录
	$user = ORM::factory('user');
	$user->name = 'New user';
	$user->save();

	// 更新现有记录
	$user = ORM::factory('user', 5);
	$user->name = 'User 2';
	$user->save();

你也可以使用 [ORM::save_all] 方法来更新多条记录：

	$user = ORM::factory('user');
	$user->name = 'Bob';

	// 更新所有结果记录的名字为 'Bob'
	$user->where('active', '=', TRUE)->save_all();

通过 [ORM::saved] 方法检查记录是否存储成功。

### 删除记录

删除记录可以使用 [ORM::delete] 和 [ORM::delet_all] 方法。这两个方法的使用和上面 存储记录 方法类似，但有一点不同的是 [ORM::delete] 方法带有一个删除记录 'id' 的可选参数。