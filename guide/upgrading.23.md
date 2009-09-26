# Upgrading from 2.3.x

Most of Kohana v3 works very differently from Kohana 2.3, here's a list of common gotchas & tips for upgrading

## Input Library

The Input Library has been removed from 3.0 in favour of just using $_GET and $_POST. 

### XSS Protection

If you need to XSS clean some user input you can use [Security::xss_clean] to sanatise it, like so:

	$_POST['description'] = security::xss_clean($_POST['description']);

You can also use the [Security::xss_clean] as a filter with the [Validate] library:

	$validation = new Validate($_POST);
	
	$validate->filter('description', 'Security::xss_clean');

### POST & GET

One of the great features of the Input library was that if you tried to access the value in one of the superglobal arrays and it didn't exist the Input library would return a default value that you could specify i.e.:

	$_GET = array();
	
	// $id is assigned the value 1
	$id = Input::instance()->get('id', 1);
	
	$_GET['id'] = 25;
	
	// $id is assigned the value 25
	$id = Input::instance()->get('id', 1);

In 3.0 you can duplicate this functionality using [Arr::get]:

	$_GET = array();
	
	// $id is assigned the value 1
	$id = Arr::get($_GET, 'id', 1);
	
	$_GET['id'] = 42;
	
	// $id is assigned the value 42
	$id = Arr::get($_GET, 'id', 1);

## ORM Library

There have been quite a few major changes in ORM since 2.3, here's a list of the more common upgrading problems.

### Relationships

In 2.3 if you wanted to iterate a model's related objects you could do:

	foreach($model->{relation_name} as $relation)

However, in the new system this won't work.   In version 2.3 any queries generated using the Database library were generated in a global scope, meaning that you couldn't try and build two queries simultaneously.  Take for example:

	$users 	= ORM::factory('user')
				->where('activated', TRUE)
				->in('id', ORM::factory('comment', 33)->replies->
	
This query would fail as the second query would 'inherit' the conditions of the first one, thus causing pandemonia.
In v3.0 this has been fixed by creating each query in its own scope, however this also means that some things won't work quite as expected.  Take for example:

	foreach(ORM::factory('user', 3)->where('post_date', '>', time() - (3600 * 24))->posts as $post)
	{
		echo $post->title;
	}

[!!] (See [the Database tutorial](tutorials.databases) for the new query syntax)

In 2.3 you would expect this to return an iterator of all posts by user 3 where `post_date` was some time within the last 24 hours, however instead it'll apply the where condition to the user model and return a `Model_Post` with the joining conditions specified.

To achieve the same effect as in 2.3 you need to rearrange the structure slightly:

	foreach(ORM::factory('user', 3)->posts->where('post_date', '>', time() - (36000 * 24))->find_all() as $post)
	{
		echo $post->title;
	}

This also applies to has_one relationships:

	// Incorrect
	$user = ORM::factory('post', 42)->author;
	// Correct
	$user = ORM::factory('post', 42)->author->find();

### Has and belongs to many relationships

In 2.3 you could specify `has_and_belongs_to_many` relationships.  In 3.0 this functionality has been refactored into `has_many` *through*.

In your models you define a `has_many` relationship to the other model but then you add a `'through' => 'table'` attribute, where `'table'` is the name of your through table. For example (in the context of posts<>categories):

	$has_many = array
	(
		'categories' => 	array
							(
								'model' 	=> 'category', // The foreign model
								'through'	=> 'post_categories' // The joining table
							),
	);

If you've set up kohana to use a table prefix then you don't need to worry about explicitly prefixing the table.