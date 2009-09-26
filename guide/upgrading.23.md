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

## Router Library

In version 2 there was a Router library that handled the main request.  It let you define basic routes in a `config/routes.php` file and it would allow you to use custom regex for the routes, however it was fairly inflexible if you wanted to do something radical.

## Routes

The routing system (now reffered to as the request system) is a lot more flexible in 3.0.  Routes are now definied in the bootstrap file (`application/bootstrap.php`) and the module init.php (`modules/module/init.php`). (It's also worth noting that routes are evaluated in the order that they are defined).

Instead of defining an array of routes you now create a new [Route] object for each route.  Unlike in the 2.x series there is no need to map one uri to another.  Instead you specify a pattern for a uri, using variables to mark the sections (i.e. controller, method, id).

For example, in the old system these regexes

	$config['([a-z]+)/?(\d+)/?([a-z]*)'] = '$1/$3/$1';

Would map the uri controller/id/method to controller/method/id.  In 3.0 you'd use:

	Route::set('reversed','(<controller>(/<id>(/<action>)))')
			->defaults(array('controller' => 'posts', 'action' => 'index'));

[!!] Each uri should have be given a unique name (in this case it's `reversed`), the reasoning behind this is explained in [the url tutorial](tutorials.urls).

Angled brackets denote dynamic sections that should be parsed into variables; Rounded brackets mark an optional section which is not required. If you wanted to only match uris beggining with admin you could use:

	Rouse::set('admin', 'admin(/<controller>(/<id>(/<action>)))');

And if you wanted to force the user to specify a controller:

	Route::set('admin', 'admin/<controller>(/<id>(/<action>))');
	
Also, Kohana does not use any 'default defaults'.  If you want kohana to assume your defaut action is 'index', then you have to tell it so! You can do this via [Route::defaults].  If you need to use custom regex for uri segments then call [Route::regex] on the route. i.e.:

	Route::set('reversed','(<controller>(/<id>(/<action>)))')
			->defaults(array('controller' => 'posts', 'action' => 'index'))
			->regex(array('id' => '[a-z_]+'));

This would force the id value to consist of lowercase alpha characters & underscores.

### Actions

One more thing we need to mention is that methods in a controller that can be accessed via the url are now called "actions", and are prefixed with 'action_'. e.g. in the above example, if the user calls `admin/posts/1/edit` then the action is `edit` but the method called on the controller will be `action_edit`.  See [the url tutorial](tutorials.urls) for more info.

### Before, During and After



## View Library

There have been a few minor changes to the View library which are worth noting.

In 2.3 views were rendered within the cope of the controller, allowing you to use `$this` as a reference to the controller within the view, this has been changed in 3.0. Views now render in an empty scope, if you need to use $this in your view you can bind a reference to it using [View::bind] - `$view->bind('this', $this)`

It's worth noting though that this is *very* bad practice as it couples your view to the controller, preventing reuse.  The reccomended way is to pass the required variables to the view like so:

	$view = View::factory('my/view');
	
	$view->variable = $this->property;
	
	// OR if you want to chain this
	
	$view
		->set('variable', $this->property)
		->set('another_variable', 42);
		
	// NOT Reccomended
	$view->bind('this', $this);

Because the view is rendered in an empty scope `Controller::_kohana_load_view` is now redundent.  If you need to modify the view before it's rendered (i.e. to add a generate a site-wide menu) you can use [Controller::after]

	<?php
	
	Class Controller_Hello extends Controller_Template
	{
		function after()
		{
			$this->template->menu = '...';
			
			return parent::after();
		}
	}
