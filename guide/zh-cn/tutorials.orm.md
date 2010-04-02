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

Type      | Option              |  Description                     | Default value
----------|---------------------|----------------------------------| -------------------------
`string`  |  _table_name        | Table name to use                | `singular model name`
`string`  | _db                 | Name of the database to use      | `default`
`string`  | _primary_key        | Column to use as primary key     | `id`
`string`  | _primary_val        | Column to use as primary value   | `name`
`bool`    | _table_names_plural | Whether tables names are plural  | `TRUE`
`array`   | _sorting            | Array of column => direction     | `primary key => ASC`
`string`  | _foreign_key_suffix | Suffix to use for foreign keys   | `_id`

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

#### Using `Updated` and `Created` Columns

The `_updated_column` and `_created_column` members are provided to automatically be updated when a model is updated and created.  These are not used by default.  To use them:

	// date_created is the column used for storing the creation date.  Use TRUE to store a timestamp
	protected $_created_column = array('date_created' => TRUE);

	// date_modified is the column used for storing the modified date.  In this case, a string specifying a date() format is used
	protected $_updated_column = array('date_modified' => 'm/d/Y');

### 删除记录

删除记录可以使用 [ORM::delete] 和 [ORM::delet_all] 方法。这两个方法的使用和上面 存储记录 方法类似，但有一点不同的是 [ORM::delete] 方法带有一个删除记录 'id' 的可选参数。

### 关系

ORM provides for powerful relationship support.  Ruby has a great tutorial on relationships at [http://api.rubyonrails.org/classes/ActiveRecord/Associations/ClassMethods.html](http://api.rubyonrails.org/classes/ActiveRecord/Associations/ClassMethods.html)

#### Belongs-To 和 Has-Many

We'll assume we're working with a school that has many students.  Each student can belong to only one school.  You would define the relationships in this manner:

	// Inside the school model
	protected $_has_many = array('students' => array());

	// Inside the student model
	protected $_belongs_to = array('school' => array());

To access a student's school you use:

	$school = $student->school;

To access a school's students, you would use:

	// Note that find_all is required after students
	$students = $school->students->find_all();

	// To narrow results:
	$students = $school->students->where('active', '=', TRUE)->find_all();

By default, ORM will look for a `school_id` model in the student table.  This can be overriden by using the `foreign_key` attribute:

	protected $_belongs_to = array('school' => array('foreign_key' => 'schoolID'));
	
The foreign key should be overridden in both the student and school models.

#### Has-One

Has-One is a special case of Has-Many, the only difference being that there is one and only one record.  In the above example, each school would have one and only one student (although this is a poor example).

	// Inside the school model
	protected $_has_one = array('student' => array());

Like Belongs-To, you do not need to use the `find` method when referencing the Has-One related object - it is done automatically.

#### Has-Many "Through"

The Has-Many "through" relationship (also known as Has-And-Belongs-To-Many) is used in the case of one object being related to multiple objects of another type, and visa-versa.  For instance, a student may have multiple classes and a class may have multiple students.  In this case, a third table and model known as a `pivot` is used.  In this case, we will call the pivot object/model `enrollment`.

	// Inside the student model
	protected $_has_many = array('classes' => array('through' => 'enrollment'));

	// Inside the class model
	protected $_has_many = array('students' => array('through' => 'enrollment'));

The enrollment table should contain two foreign keys, one for `class_id` and the other for `student_id`.  These can be overriden using `foreign_key` and `far_key` when defining the relationship.  For example:

	// Inside the student model (the foreign key refers to this model [student], while the far key refers to the other model [class])
	protected $_has_many = array('classes' => array('through' => 'enrollment', 'foreign_key' => 'studentID', 'far_key' => 'classID'));

	// Inside the class model
	protected $_has_many = array('students' => array('through' => 'enrollment', 'foreign_key' => 'classID', 'far_key' => 'studentID'));

The enrollment model should be defined as such:

	// Enrollment model belongs to both a student and a class
	protected $_belongs_to = array('student' => array(), 'class' => array());

To access the related objects, use:

	// To access classes from a student
	$student->classes->find_all();

	// To access students from a class
	$class->students->find_all();

### 校验
	
ORM is integrated tightly with the [Validate] library.  The ORM provides the following members for validation

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

`username` will be checked to make sure it's not empty.  `email` will be checked to also ensure it is a valid email address.  The empty arrays passed as values can be used to provide optional additional parameters to these validate method calls.

#### `_callbacks`
	
	protected $_callbacks = array
	(
		'username' => array('username_unique'),
	);

`username` will be passed to a callback method `username_unique`.  If the method exists in the current model, it will be used, otherwise a global function will be called.  Here is an example of the definition of this method:

	public function username_unique(Validate $data, $field)
	{
		// Logic to make sure a username is unique
		...
	}

#### `_filters`

	protected $_filters = array
	(
		TRUE       => array('trim' => array()),
		'username' => array('stripslashes' => array()),
	);

`TRUE` indicates that the `trim` filter is to be used on all fields.  `username` will be filtered through `stripslashes` before it is validated.  The empty arrays passed as values can be used to provide additional parameters to these filter method calls.
	
#### 检测对象是否通过校验

Use [ORM::check] to see if the object is currently valid.

	// Setting an object's values, then checking to see if it's valid
	if ($user->values($_POST)->check())
	{
		$user->save();
	}

You can use the `validate()` method to access the model's validation object

	// Add an additional filter manually
	$user->validate()->filter('username', 'trim');

