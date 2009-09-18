# Snel starten met de database

De Kohana Database module bestaat uit verschillende klasses om [MySQL](http://be2.php.net/mysql) en [PDO](http://be2.php.net/pdo) database toegang te verlenen. Voorbereide statements zijn volledig geïntegreerd met queries om een maximale flexibiliteit en caching potentiëel toe te laten.

## Een query maken

Een query maken:

~~~
$query = DB::query(Database::SELECT, 'SELECT * FROM users');
~~~

Er parameters aan toevoegen: 

~~~
$query = DB::query(Database::SELECT, 'SELECT * FROM users WHERE username = :username')
    ->set(':username', 'john.smith');
~~~

Parameters binden met variabelen: 

~~~
$query = DB::query(Database::SELECT, 'SELECT * FROM users WHERE username = :username')
    ->bind(':username', $username);
~~~

## Queries bouwen

Een select query maken:

~~~
// SELECT * is het standaardgedrag
$query = DB::select()->from('users');
~~~

Met WHERE statements: 

~~~
$query = DB::select()->from('users')->where('username', '=', 'john.smith');
~~~

Een INSERT query maken: 

~~~
$query = DB::insert('users', array('username', 'password'))
    ->values(array('jane.doe', 'tiger'))
    ->values(array('john.doe', 'walrus'));
~~~

Parameters binden met gebouwde queries: 

~~~
$query = DB::select()->from('users')->where('username', '=', DB::expr(':username'))
    ->bind(':username', $username);
~~~

De uiteindelijke SQL string tonen van eender welke query: 

~~~
echo $query->compile(Database::instance());
// Of gebruik __toString() (dit zal altijd de standaard database instantie gebruiken)
echo (string) $query;
~~~

## Een Query uitvoeren

Een SELECT query uitvoeren geeft een resultaten-array:

~~~
$results = DB::query(Database::SELECT, 'SELECT id, username FROM users')->execute();

echo 'Found '.count($results).' users';
foreach ($results as $row)
{
    echo $row['username'].': '.$row['id'];
}
~~~

Een INSERT query uitvoeren geeft het laatst toegevoegde ID en het aantal rijen dat toegevoerd is: 

~~~
list($insert_id, $total_rows) = DB::query(Database::INSERT, 'INSERT INTO users (username, password) VALUES (:username, :password))
    ->set(':username', 'jane.doe')
    ->set(':password', 'secret')
    ->execute();

echo 'Inserted '.$total_rows.' with a starting id of '.$insert_id;
~~~

Alle andere types queries: 

~~~
$total_rows = DB::query(Database::UPDATE, 'UPDATE users SET username = :username WHERE id = :id)
    ->set(':username', 'jane.smith')
    ->set(':id', 1)
    ->execute();

echo 'Updated '.$total_rows;
~~~

Het resultaat van een query cachen: 

~~~
// Cache the result of this query for 60 seconds
$result = $query->cached(60)->execute();
~~~

Een query uitvoeren met een niet-standaard database: 

~~~
// Geef de naam van de instantie
$result = $query->execute('my-instance');

// Of geef een instantieobject
$db = Database::instance('my-instance');
$result = $query->execute($db);
~~~

## Werken met resultaten

Door de resultaten lopen: 

~~~
foreach ($result as $row)
{
    print_r($row);
}
~~~

Eén enkele kolom van een resultaat opvragen: 

~~~
$id = $result->get('id');
~~~

Een resultaat als een associatieve array krijgen: 

~~~
// Krijg al de resulaten georderd op (id=>rij)
$list = $result->as_array('id');

// Krijg al de resultaten als (id => username) en verwerp andere data
$list = $result->as_array('id', 'username');
~~~

