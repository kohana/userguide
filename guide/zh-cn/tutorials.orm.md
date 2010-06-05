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

上面的例子中，模型会寻找默认数据库的 `users` 表。

### 模型配置属性

下面的属性是用于配置每个模型的：

类型      | 属性                |  描述                            | 默认值
----------|---------------------|----------------------------------| -------------------------
`string`  |  _table_name        | 表名                             | `singular model name`
`string`  | _db                 | 数据库配置名                     | `default`
`string`  | _primary_key        | 主键                             | `id`
`string`  | _primary_val        | 主键值                           | `name`
`bool`    | _table_names_plural | 表名是否是复数形式               | `TRUE`
`array`   | _sorting            | 列名 => 排序方向的数组           | `primary key => ASC`
`string`  | _foreign_key_suffix | 外键的后缀                       | `_id`

## 使用 ORM

### 加载一条记录

通过调用 [ORM::factory] 或 [ORM::__construct] 方法创建模型的实例化：

	$user = ORM::factory('user');
	// 或者
	$user = new Model_User();

构造函数和 factory 方法也接受一个主键值来加载模型数据：

	// 加载 ID 为 5 的用户
	$user = ORM::factory('user', 5);

	// 检查用户是否加载成功
	if ($user->loaded()) { ... }

你同样可以使用传递键-值型数组的数据对象去加载记录:

	// 加载 email 为 oe@example.com 的用
	$user = ORM::factory('user', array('email' => 'joe@example.com'));

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

ORM 一个强大的特性是 [ORM::as_array] 方法，它把返回的记录集转为为一个数组。如果使用了 [ORM::find_all] 所有的记录都会以数组的形式返回。
对于选择列的时候是非常好用的:

	// 显示选择列的用户名 (使用 id 作为其值)
	form::select('user', ORM::factory('user')->find_all()->as_array('id', 'username') ...

### 记录数

使用 [ORM::count_all] 方法返回查询返回记录集的记录数。

	// 用户的记录数
	$count = ORM::factory('user')->where('active', '=', TRUE)->count_all();

如果你想在特定子集的查询语句中统计所有用户的记录数，在调用 `count_all` 方法之前先调用 [ORM::reset] 方法并赋值 `FALSE`:

	$user = ORM::factory('user');

	// 用户的总数 (reset FALSE prevents the query object from being cleared)
	$count = $user->where('active', '=', TRUE)->reset(FALSE)->count_all();

	// 仅返回前 10 条记录的记录数
	$users = $user->limit(10)->find_all();

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

使用 [ORM::values] 方法设置键-值型数组:

	$user->values(array('username' => 'Joe', 'password' => 'bob'));

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

	// 检查记录是否保存成功
	if ($user->saved()) { ... }

你也可以使用 [ORM::save_all] 方法来更新多条记录：

	$user = ORM::factory('user');
	$user->name = 'Bob';

	// 更新所有结果记录的名字为 'Bob'
	$user->where('active', '=', TRUE)->save_all();

#### 使用 `Updated` 和 `Created` 列

`_updated_column` 和 `_created_column` 变量是用于当模型更新或插入新纪录的时候自动更新设置的字段值。默认没有使用。如果你想使用:

	// date_created 列用于储存创建的时间，使用 TRUE 保存的是时间戳(timestamp)
	protected $_created_column = array('date_created' => TRUE);

	// date_modified 列用于储存最后修改时间。这里的时间设置为使用 date() 格式后的字符串
	protected $_updated_column = array('date_modified' => 'm/d/Y');

### 删除记录

删除记录可以使用 [ORM::delete] 和 [ORM::delet_all] 方法。这两个方法的使用和上面 存储记录 方法类似，但有一点不同的是 [ORM::delete] 方法带有一个删除记录 'id' 的可选参数。

### 关系

ORM 提供强大的关系模型。Ruby 有一篇介绍关系模型的文章: [http://api.rubyonrails.org/classes/ActiveRecord/Associations/ClassMethods.html](http://api.rubyonrails.org/classes/ActiveRecord/Associations/ClassMethods.html)

#### Belongs-To 和 Has-Many

假设我们在一所学校工作，当然学校有很多学生，而每个学生都只属于一个学校。这样的关系模型可以这样定义:

	// school 模型文件
	protected $_has_many = array('students' => array());

	// student 模型文件
	protected $_belongs_to = array('school' => array());

获取学生的学校:

	$school = $student->school;

获取学校的学生:

	// 注意在 studends 后必须调用 find_all 方法
	$students = $school->students->find_all();

	// 缩小范围查询:
	$students = $school->students->where('active', '=', TRUE)->find_all();

默认情况下，在 student 表定义的模型文件中 ORM 会寻找 `school_id` 当作外键。这个可以通过 `foreign_key` 属性更改:

	protected $_belongs_to = array('school' => array('foreign_key' => 'schoolID'));
	
外键应该同时覆写 student 和 school 模型文件。

#### Has-One

Has-One 是 Has-Many 的一个特别情况，唯一不同的这是一对一关系。还以上面的例子说明就是，每个学校有且只有一个学生（当然这是一个很牵强呃例子）。

	// school 模型文件
	protected $_has_one = array('student' => array());

类似于 Belongs-To，当你引用 Has-One 关系对象的时候无需调用 `find` 方法 - 它是自动完成的。

#### Has-Many "Through"

Has-Many "through" 关系(也可以称之为 Has-And-Belongs-To-Many) is used in the case of one object being related to multiple objects of another type, and visa-versa.  For instance, a student may have multiple classes and a class may have multiple students.  In this case, a third table and model known as a `pivot` is used.  In this case, we will call the pivot object/model `enrollment`.

	// student (学生)模型文件
	protected $_has_many = array('classes' => array('through' => 'enrollment'));

	// class (班级)模型文件
	protected $_has_many = array('students' => array('through' => 'enrollment'));

其中 enrollment 表包含两个外键: `class_id` 和 `student_id`。在定义关系时，使用 `foreign_key` 和 `far_key` 覆写了默认值。例如:

	// student (学生)模型文件() (the foreign key refers to this model [student], while the far key refers to the other model [class])
	protected $_has_many = array('classes' => array('through' => 'enrollment', 'foreign_key' => 'studentID', 'far_key' => 'classID'));

	// class (班级)模型文件
	protected $_has_many = array('students' => array('through' => 'enrollment', 'foreign_key' => 'classID', 'far_key' => 'studentID'));

enrollment 模型文件应该这样定义:

	// Enrollment 模型同时属于一个 student 和 class
	protected $_belongs_to = array('student' => array(), 'class' => array());

获取相关对象:

	// 从 student 中获取 classes
	$student->classes->find_all();

	// 从 class 中获取 students
	$class->students->find_all();

### 校验
	
ORM 和 [Validate] 类是紧密结合使用的。ORM 提供以下几种校验方式:

* _rules
* _callbacks
* _filters
* _labels

#### `_rules`
	
	protected $_rules = array
	(
		'username' => array('not_empty' => array()),
		'email'    => array('not_empty' => array(), 'email' => array()),
	);

检测并确保 `username` 字段不为空。检测 `email` 字段不为空且是有效的 Email 地址格式。那些传递空值数组用于提供可选的额外参数到校验方法中使用。

#### `_callbacks`
	
	protected $_callbacks = array
	(
		'username' => array('username_unique'),
	);

`username` 字段被传递到了 `username_unique` 回调函数。如果方法存在于当前模型它就会被调用，否则调用全局函数。下面有个小例子:

	public function username_unique(Validate $data, $field)
	{
		// 确保 username 是唯一的
		...
	}

#### `_filters`

	protected $_filters = array
	(
		TRUE       => array('trim' => array()),
		'username' => array('stripslashes' => array()),
	);

`TRUE` 值代表 `trim` 过滤器应用到所有字段。而 `username` 字段则在校验前使用 `stripslashes` 过滤。那些传递空值数组用于提供可选的额外参数到校验方法中使用。
	
#### 检测对象是否通过校验

使用 [ORM::check] 检测当前对象是否通过校验:

	// 设置完对象的值，接下来检测是否通过校验
	if ($user->values($_POST)->check())
	{
		$user->save();
	}

你也可是使用 `validate()` 方法直接访问模型的校验对象:

	// 手动添加额外的过滤器
	$user->validate()->filter('username', 'trim');