# Database Quick Start

The Kohana Database module consists of several classes to provide [MySQL](http://php.net/mysql) and [PDO](http://php.net/pdo) database access. Prepared statements are completely integrated with queries to allow maximum flexibility and caching potential.

## Creating a query

Creating a prepared query statement:

~~~
$query = DB::query(Database::SELECT, 'SELECT * FROM users');
~~~

Adding parameters to a query:

~~~
$query = DB::query(Database::SELECT, 'SELECT * FROM users WHERE username = :username')
    ->set(':username', 'john.smith');
~~~

Binding parameters to variables by reference:

~~~
$query = DB::query(Database::SELECT, 'SELECT * FROM users WHERE username = :username')
    ->bind(':username', $username);
~~~

## Building queries

Building a SELECT query:

~~~
// SELECT * is the default
$query = DB::select()->from('users');
~~~

Adding WHERE statements:

~~~
$query = DB::select()->from('users')->where('username', '=', 'john.smith');
~~~

Building an INSERT query:

~~~
$query = DB::insert('users', array('username', 'password'))
    ->values(array('jane.doe', 'tiger'))
    ->values(array('john.doe', 'walrus'));
~~~

Binding parameters with built queries:

~~~
$query = DB::select()->from('users')->where('username', '=', DB::expr(':username'))
    ->bind(':username', $username);
~~~

Display the final SQL string of any query:

~~~
echo $query->compile(Database::instance());
// Or use __toString() (this will always use the default database instance)
echo (string) $query;
~~~

## Executing a query

Execute a SELECT query, returns a result iterator:

~~~
$results = DB::query(Database::SELECT, 'SELECT id, username FROM users')->execute();

echo 'Found '.count($results).' users';
foreach ($results as $row)
{
    echo $row['username'].': '.$row['id'];
}
~~~

Execute an INSERT query, returns the last insert ID and the number of rows created:

~~~
list($insert_id, $total_rows) = DB::query(Database::INSERT, 'INSERT INTO users (username, password) VALUES (:username, :password))
    ->set(':username', 'jane.doe')
    ->set(':password', 'secret')
    ->execute();

echo 'Inserted '.$total_rows.' with a starting id of '.$insert_id;
~~~

All other types of queries:

~~~
$total_rows = DB::query(Database::UPDATE, 'UPDATE users SET username = :username WHERE id = :id)
    ->set(':username', 'jane.smith')
    ->set(':id', 1)
    ->execute();

echo 'Updated '.$total_rows;
~~~

Caching the results of a query:

~~~
// Cache the result of this query for 60 seconds
$result = $query->cached(60)->execute();
~~~

Executing a query with a non-default database:

~~~
// Pass the instance name
$result = $query->execute('my-instance');

// Or provide a instance object
$db = Database::instance('my-instance');
$result = $query->execute($db);
~~~

## Working with results

Looping through results:

~~~
foreach ($result as $row)
{
    print_r($row);
}
~~~

Getting a single column from a result:

~~~
$id = $result->get('id');
~~~

Getting a result as an associative array:

~~~
// Get all results organized by (id => row)
$list = $result->as_array('id');

// Get all results as (id => username) and discard other data
$list = $result->as_array('id', 'username');
~~~

