# ORM {#top}

Kohana 3.0 includes a powerful ORM module that uses the active record pattern and database introspection to determine a model's column information.

The ORM module is included with the Kohana 3.0 install but needs to be enabled before you can use it. In your `application/bootstrap.php` file modify the call to [Kohana::modules] and include the ORM module:

	Kohana::modules(array(
		...
		'orm' => MODPATH.'orm',
		...
	));

## Configuration {#configuration}

ORM requires little configuration to get started. Extend your model classes with ORM to begin using the module:

	class Model_User extends ORM
	{
		...
	}

In the example above, the model will look for a `users` table in the default database.

### Model Configuration Properties

The following properties are used to configure each model:

Type      | Option              |  Description                     | Default value
----------|---------------------|----------------------------------| -------------------------
`string`  |  _table_name        | Table name to use                | `singular model name`
`string`  | _db                 | Name of the database to use      | `default`
`string`  | _primary_key        | Column to use as primary key     | `id`
`string`  | _primary_val        | Column to use as primary value   | `name`
`bool`    | _table_names_plural | Whether tables names are plural  | `TRUE`
`array`   | _sorting            | Array of column => direction     | `primary key => ASC`
`string`  | _foreign_key_suffix | Suffix to use for foreign keys   | `_id`

## Using ORM

### Loading a Record

To create an instance of a model, you can use the [ORM::factory] method or the [ORM::__construct]:

	$user = ORM::factory('user');
	// or
	$user = new Model_User();

The constructor and factory methods also accept a primary key value to load the given model data:

	// Load user ID 5
	$user = ORM::factory('user', 5);

	// See if the user was loaded successfully
	if ($user->loaded()) { ... }

You can optionally pass an array of key => value pairs to load a data object matching the given criteria:

	// Load user with email joe@example.com
	$user = ORM::factory('user', array('email' => 'joe@example.com'));

### Searching for a Record or Records

ORM supports most of the [Database] methods for powerful searching of your model's data. See the `_db_methods` property for a full list of supported method calls. Records are retrieved using the [ORM::find] and [ORM::find_all] method calls.

	// This will grab the first active user with the name Bob
	$user = ORM::factory('user')
		->where('active', '=', TRUE)
		->where('name', '=', 'Bob')
		->find();

	// This will grab all users with the name Bob
	$users = ORM::factory('user')
		->where('name', '=', 'Bob')
		->find_all();

When you are retrieving a list of models using [ORM::find_all], you can iterate through them as you do with database results:

	foreach ($users as $user)
	{
		...
	}

A powerful feature of ORM is the [ORM::as_array] method which will return the given record as an array. If used with [ORM::find_all], an array of all records will be returned. A good example of when this is useful is for a select list:

	// Display a select field of usernames (using the id as values)
	echo Form::select('user', ORM::factory('user')->find_all()->as_array('id', 'username'));

### Counting Records

Use [ORM::count_all] to return the number of records for a given query.

	// Number of users
	$count = ORM::factory('user')->where('active', '=', TRUE)->count_all();

If you wish to count the total number of users for a given query, while only returning a certain subset of these users, call the [ORM::reset] method with `FALSE` before using `count_all`:

	$user = ORM::factory('user');

	// Total number of users (reset FALSE prevents the query object from being cleared)
	$count = $user->where('active', '=', TRUE)->reset(FALSE)->count_all();

	// Return only the first 10 of these results
	$users = $user->limit(10)->find_all();

### Accessing Model Properties

All model properties are accessible using the `__get` and `__set` magic methods.

	$user = ORM::factory('user', 5);

	// Output user name
	echo $user->name;

	// Change user name
	$user->name = 'Bob';

To store information/properties that don't exist in the model's table, you can use the `_ignored_columns` data member. Data will be stored in the internal `_object` member, but ignored at the database level.

	class Model_User extends ORM
	{
		...
		protected $_ignored_columns = array('field1', 'field2', ...);
		...
	}

Multiple key => value pairs can be set by using the [ORM::values] method.

	$user->values(array('username' => 'Joe', 'password' => 'bob'));

### Creating and Saving Records

The [ORM::save] method is used to both create new records and update existing records.

	// Creating a record
	$user = ORM::factory('user');
	$user->name = 'New user';
	$user->save();

	// Updating a record
	$user = ORM::factory('user', 5);
	$user->name = 'User 2';
	$user->save();

	// Check to see if the record has been saved
	if ($user->saved()) { ... }

You can update multiple records by using the [ORM::save_all] method:

	$user = ORM::factory('user');
	$user->name = 'Bob';

	// Change all active records to name 'Bob'
	$user->where('active', '=', TRUE)->save_all();

#### Using `Updated` and `Created` Columns

The `_updated_column` and `_created_column` members are provided to automatically be updated when a model is updated and created. These are not used by default. To use them:

	// date_created is the column used for storing the creation date. Use format => TRUE to store a timestamp.
	protected $_created_column = array('column' => 'date_created', 'format' => TRUE);

	// date_modified is the column used for storing the modified date. In this case, a string specifying a date() format is used.
	protected $_updated_column = array('column' => 'date_modified', 'format' => 'm/d/Y');

### Deleting Records

Records are deleted with [ORM::delete] and [ORM::delete_all]. These methods operate in the same fashion as saving described above with the exception that [ORM::delete] takes one optional parameter, the `id` of the record to delete. Otherwise, the currently loaded record is deleted.

### Relationships

ORM provides for powerful relationship support. Ruby has [a great tutorial on relationships](http://api.rubyonrails.org/classes/ActiveRecord/Associations/ClassMethods.html).

#### Belongs-To and Has-Many

We'll assume we're working with a school that has many students. Each student can belong to only one school. You would define the relationships in this manner:

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

By default, ORM will look for a `school_id` model in the student table. This can be overriden by using the `foreign_key` attribute:

	protected $_belongs_to = array('school' => array('foreign_key' => 'schoolID'));

The foreign key should be overridden in both the student and school models.

#### Has-One

Has-One is a special case of Has-Many, the only difference being that there is one and only one record. In the above example, each school would have one and only one student (although this is a poor example).

	// Inside the school model
	protected $_has_one = array('student' => array());

Like Belongs-To, you do not need to use the `find` method when referencing the Has-One related object - it is done automatically.

#### Has-Many "Through"

The Has-Many "through" relationship (also known as Has-And-Belongs-To-Many) is used in the case of one object being related to multiple objects of another type, and visa-versa. For instance, a student may have multiple classes and a class may have multiple students. In this case, a third table and model known as a `pivot` is used. In this case, we will call the pivot object/model `enrollment`.

	// Inside the student model
	protected $_has_many = array('classes' => array('through' => 'enrollment'));

	// Inside the class model
	protected $_has_many = array('students' => array('through' => 'enrollment'));

The enrollment table should contain two foreign keys, one for `class_id` and the other for `student_id`. These can be overriden using `foreign_key` and `far_key` when defining the relationship. For example:

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

### Validation

ORM is integrated tightly with the [Validate] library. The ORM provides the following members for validation:

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

`username` will be checked to make sure it's not empty. `email` will be checked to also ensure it is a valid email address. The empty arrays passed as values can be used to provide optional additional parameters to these validate method calls.

#### `_callbacks`

	protected $_callbacks = array
	(
		'username' => array('username_unique'),
	);

`username` will be passed to a callback method `username_unique`. If the method exists in the current model, it will be used, otherwise a global function will be called. Here is an example of the definition of this method:

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

`TRUE` indicates that the `trim` filter is to be used on all fields. `username` will be filtered through `stripslashes` before it is validated. The empty arrays passed as values can be used to provide additional parameters to these filter method calls.

#### Checking if the Object is Valid

Use [ORM::check] to see if the object is currently valid.

	// Setting an object's values, then checking to see if it's valid
	if ($user->values($_POST)->check())
	{
		$user->save();
	}

You can use the `validate()` method to access the model's validation object.

	// Add an additional filter manually
	$user->validate()->filter('username', 'trim');
