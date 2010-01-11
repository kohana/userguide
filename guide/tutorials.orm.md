# ORM {#top}

Kohana 3.0 includes a powerful [ORM](http://en.wikipedia.org/wiki/Orm) module that uses the active record pattern and database introspection to determine a model's column information.

The ORM module is included with the Kohana 3.0 install but needs to be enabled before you can use it. In your `application/bootstrap.php` file modify the call to [Kohana::modules] and include the ORM module:

	Kohana::modules(array(
		...
		'orm' => MODPATH.'orm',
		...
	));

## Configuration {#configuration}

ORM requires little configuration to get started.  Extend your model classes with ORM to begin using the module:

	class Model_User extends ORM
	{
		...
	}

In the example above, the model will look for a `users` table in the default database.

### Model Conguration Properties

The following properties are used to configure each model:

Type      | Option          |  Description                   | Default value
----------|-----------------|--------------------------------| -------------------------
`string`  |  _table_name    | Table name to use              | 
`string`  | _db             | Name of the database to use    |`default`
`string`  | _primary_key    | Column to use as primary key   |`id`
`string`  | _primary_val    | Column to use as primary value |`name`

## Using ORM

### Loading a Record

To create an instance of a model, you can use the [ORM::factory] method or the [ORM::__construct]:

	$user = ORM::factory('user');
	// or
	$user = new Model_User();

The constructor and factory methods also accept a primary key value to load the given model data:

	// Load user ID 5
	$user = ORM::factory('user', 5);

[ORM::loaded] checks to see if the given model has been loaded successfully.

### Searching for a Record

ORM supports most of the [Database] methods for powerful searching of your model's data.  See the `_db_methods` property for a full list of supported method calls.  Records are retrieved using the [ORM::find] and [ORM::find_all] method calls.

	// This will grab the first active user with the name Bob
	$user = ORM::factory('user')
		->where('active', '=', TRUE)
		->where('name', '=', 'Bob')
		->find();

	// This will grab all users with the name Bob
	$users = ORM::factory('user')
		...
		->find_all();
	
When you are retrieving a list of models using [ORM::find_all], you can iterate through them as you do with database results:

	foreach ($users as $user)
	{
		...
	}

### Accessing Model Properties

All model properties are accessible using the `__get` and `__set` magic methods. 

	$user = ORM::factory('user', 5);
	
	// Output user name
	echo $user->name;

	// Change user name
	$user->name = 'Bob';

To store information/properties that don't exist in the model's table, you can use the `_ignored_columns` data member.

	class Model_User extends ORM
	{
		...
		protected $_ignored_columns = array('field1', 'field2', ...)
		...
	}

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

You can update multiple records by using the [ORM::save_all] method:

	$user = ORM::factory('user');
	$user->name = 'Bob';

	// Change all active records to name 'Bob'
	$user->where('active', '=', TRUE)->save_all();

[ORM::saved] checks to see if the given model has been saved.

### Deleting Records

Records are deleted with [ORM::delete] and [ORM::delet_all].  These methods operate in the same fashion as saving described above with the exception that [ORM::delete] takes one optional paramter, the `id` of the record to delete.