# Databases

Kohana 3.0 comes with a robust module to working with databases. By default the database module supports drivers for [MySQL](http://php.net/mysql) and [PDO](http://php.net/pdo).

The database module is included with the Kohana 3.0 install but needs to be enabled before you can use it. In your `application/bootstrap.php` file modify the call to [Kohana::modules] and include the database module:

    Kohana::modules(array(
        ...
        'database' => MODPATH.'database',
        ...
    ));

## Configuration

After the module has been enabled you will need to provide a configuration file so that the module knows how to connect to your database. An example config file can be found at `modules/database/config/database.php`.

The structure of a database configuration group, called an "instance", looks like this:

    string INSTANCE_NAME => array(
        'type'         => string DATABASE_TYPE,
        'connection'   => array CONNECTION_ARRAY,
        'table_prefix' => string TABLE_PREFIX,
        'charset'      => string CHARACTER_SET,
        'profiling'    => boolean QUERY_PROFILING,
    ),

[!!] Multiple instances of these settings can be defined within the configuration file.

Understanding each of these settings is important.

INSTANCE_NAME
:  Connections can be named anything you want, but you should always have at least one connection called "default".

DATABASE_TYPE
:  One of the installed database drivers. Kohana comes with "mysql" and "pdo" drivers.

CONNECTION_ARRAY
:  Specific driver options for connecting to your database. (Driver options are explained [below](#connections).)

TABLE_PREFIX
:  Prefix that will be added to all table names by the [query builder](#query_builder).

QUERY_PROFILING
:  Enables [profiling](debugging.profiling) of database queries.

### Example

The example file below shows 2 MySQL connections, one local and one remote.

    return array
    (
        'default' => array
        (
            'type'       => 'mysql',
            'connection' => array(
                'hostname'   => 'localhost',
                'username'   => 'dbuser',
                'password'   => 'mypassword',
                'persistent' => FALSE,
                'database'   => 'my_db_name',
            ),
            'table_prefix' => '',
            'charset'      => 'utf8',
            'profiling'    => TRUE,
        ),
        'remote' => array(
            'type'       => 'mysql',
            'connection' => array(
                'hostname'   => '55.55.55.55',
                'username'   => 'remote_user',
                'password'   => 'mypassword',
                'persistent' => FALSE,
                'database'   => 'my_remote_db_name',
            ),
            'table_prefix' => '',
            'charset'      => 'utf8',
            'profiling'    => TRUE,
        ),
    );

### Connections

Every database driver has different connection settings.

#### MySQL

A MySQL database can accept the following options in the `connection` array:

Type      | Option     |  Description               | Default value
----------|------------|----------------------------| -------------------------
`string`  | hostname   | Hostname of the database   | `localhost`
`integer` | port       | Port number                | `NULL`
`string`  | socket     | UNIX socket                | `NULL`
`string`  | username   | Database username          | `NULL`
`string`  | password   | Database password          | `NULL`
`boolean` | persistent | Persistent connections     | `FALSE`
`string`  | database   | Database name              | `kohana`

#### PDO

A PDO database can accept these options in the `connection` array:

Type      | Option     |  Description               | Default value
----------|------------|----------------------------| -------------------------
`string`  | dsn        | PDO data source identifier | `localhost`
`string`  | username   | Database username          | `NULL`
`string`  | password   | Database password          | `NULL`
`boolean` | persistent | Persistent connections     | `FALSE`

!! If you are using PDO and are not sure what to use for the `dsn` option, review [PDO::__construct](http://php.net/pdo.construct).

## Making Queries

There are two different ways to make queries. The simplest way to make a query is to use the [Database_Query], via [DB::query], to create queries. These queries are called "prepared statements" and allow you to set query parameters which are automatically escaped. The second way to make a query is by building the query using method calls. This is done using the [query builder](#query_builder).

### Prepared Statements

Using prepared statements allows you to write SQL queries manually while still escaping the query values automatically to prevent [SQL injection](http://wikipedia.org/wiki/SQL_Injection). Creating a query is simple:

    $query = DB::query(Database::SELECT, 'SELECT * FROM users WHERE username = :user');

The [DB::query] factory method creates a new [Database_Query] class for us, to allow method chaining. The query contains a `:user` parameter, which we can assign to a value:

    $query->param(':user', 'john');

[!!] Parameter names can be any string, as they are replaced using [strtr](http://php.net/strtr). It is highly recommended to **not** use dollars signs as parameter names to prevent confusion.

If you want to display the SQL that will be executed, simply cast the object to a string:

    echo Kohana::debug((string) $query);
    // Should display:
    // SELECT * FROM users WHERE username = 'john'

You can also update the `:user` parameter by calling [Database_Query::param] again:

    $query->param(':user', $_GET['search']);

[!!] If you want to set multiple parameters at once, you can use [Database_Query::parameters].

Once you have assigned something to each of the parameters, you can execute the query:

    $query->execute();

It is also possible to bind a parameter to a variable, using a [variable reference]((http://php.net/language.references.whatdo)). This can be extremely useful when running the same query many times:

    $query = DB::query(Database::INSERT, 'INSERT INTO users (username, password) VALUES (:user, :pass)')
        ->bind(':user', $username)
        ->bind(':pass', $password);

    foreach ($new_users as $username => $password)
    {
        $query->execute();
    }

In the above example, the variables `$username` and `$password` are changed for every loop of the `foreach` statement. When the parameter changes, it effectively changes the `:user` and `:pass` query parameters. Careful parameter binding can save a lot of code when it is used properly.

### Query Building

Creating queries dynamically using objects and methods allows queries to be written very quickly in an agnostic way. Query building also adds identifier (table and column name) quoting, as well as value quoting.

[!!] At this time, it is not possible to combine query building with prepared statements.

All queries are executed using the `execute` method, which is inherited from [Database_Query::execute].

#### SELECT

Each type of database query is represented by a different class, each with their own methods. For instance, to create a SELECT query, we use [DB::select]:

    $query = DB::select()->from('users')->where('username', '=', 'john');

By default, [DB::select] will select all columns (`SELECT * ...`), but you can also specify which columns you want returned:

    $query = DB::select('password')->from('users')->where('username', '=', 'john');

Now take a minute to look at what this method chain is doing. First, we create a new instance of [Database_Query_Builder_Select] using the [DB::select] method. Next, we set table(s) using the `from` method. Last, we search for a specific records using the `where` method. We can display the SQL that will be executed by casting the query to a string:

    echo Kohana::debug((string) $query);
    // Should display:
    // SELECT `password` FROM `users` WHERE `username` = 'john'

Notice how the column and table names are automatically escaped, as well as the values? This is one of the key benefits of using the query builder.

#### INSERT

To create records into the database, use [DB::insert] to create an INSERT query:

    $query = DB::insert('users', array('username', 'password'))->values(array('fred', 'p@5sW0Rd'));

This query would generate the following SQL:

    INSERT INTO `users` (`username`, `password`) VALUES ('fred', 'p@5sW0Rd')

#### UPDATE

To modify an existing record, use [DB::update] to create an UPDATE query:

    $query = DB::update('users')->set(array('username' => 'jane'))->where('username', '=', 'john');

This query would generate the following SQL:

    UPDATE `users` SET `username` = 'jane' WHERE `username` = 'john'

#### DELETE

To remove an existing record, use [DB::delete] to create a DELETE query:

    $query = DB::delete('users')->where('username', 'IN', array('john', 'jane'));

This query would generate the following SQL:

    DELETE FROM `users` WHERE `username` IN ('john', 'jane')

