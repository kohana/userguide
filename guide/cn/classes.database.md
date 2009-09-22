# Database 快速上手

Kohana Database 模块包含多个类：[MySQL](http://php.net/mysql) 和 [PDO](http://php.net/pdo) 来支持数据库的访问。预处理查询语句完美集成以获得最大的灵活性和缓存能力。

## 创建一个查询

创建一个预处理查询语句:

~~~
$query = DB::query(Database::SELECT, 'SELECT * FROM users');
~~~

添加变量到查询语句中:

~~~
$query = DB::query(Database::SELECT, 'SELECT * FROM users WHERE username = :username')
    ->set(':username', 'john.smith');
~~~

绑定引用变量：

~~~
$query = DB::query(Database::SELECT, 'SELECT * FROM users WHERE username = :username')
    ->bind(':username', $username);
~~~

## 组建查询语句

组建一个 SELECT 语句:

~~~
// 默认是 SELECT *
$query = DB::select()->from('users');
~~~

添加 WHERE 语句:

~~~
$query = DB::select()->from('users')->where('username', '=', 'john.smith');
~~~

组建一条 INSERT 语句:

~~~
$query = DB::insert('users', array('username', 'password'))
    ->values(array('jane.doe', 'tiger'))
    ->values(array('john.doe', 'walrus'));
~~~

绑定带有组建查询参数的查询语句：

~~~
$query = DB::select()->from('users')->where('username', '=', DB::expr(':username'))
    ->bind(':username', $username);
~~~

显示任何组建语句的最终 SQL 字符串：

~~~
echo $query->compile(Database::instance());
// 或使用 __toString() (它将一直使用默认的数据库实例)
echo (string) $query;
~~~

## 执行查询语句

执行一个 SELECT 语句并返回其结果迭代：

~~~
$results = DB::query(Database::SELECT, 'SELECT id, username FROM users')->execute();

echo 'Found '.count($results).' users';
foreach ($results as $row)
{
    echo $row['username'].': '.$row['id'];
}
~~~

执行一个 INSERT 语句，返回最后一个插入 ID 号和创建行的数量：

~~~
list($insert_id, $total_rows) = DB::query(Database::INSERT, 'INSERT INTO users (username, password) VALUES (:username, :password))
    ->set(':username', 'jane.doe')
    ->set(':password', 'secret')
    ->execute();

echo 'Inserted '.$total_rows.' with a starting id of '.$insert_id;
~~~

语句的其他类型：

~~~
$total_rows = DB::query(Database::UPDATE, 'UPDATE users SET username = :username WHERE id = :id)
    ->set(':username', 'jane.smith')
    ->set(':id', 1)
    ->execute();

echo 'Updated '.$total_rows;
~~~

缓存一个查询语句的结果：

~~~
// 缓存此条查询结果 60 秒
$result = $query->cached(60)->execute();
~~~

执行一条使用非缺省数据库对象的查询语句：

~~~
// 传递一个实例对象名
$result = $query->execute('my-instance');

// 或提供一个实例对象
$db = Database::instance('my-instance');
$result = $query->execute($db);
~~~

## 结果处理

循环结果：

~~~
foreach ($result as $row)
{
    print_r($row);
}
~~~

从结果中获得单列：

~~~
$id = $result->get('id');
~~~

获得关联数组形式的结果：

~~~
// 获得所有列等于 id 的结果 (id => row)
$list = $result->as_array('id');

// 获得所有 (id => username) 的结果并丢弃其他数据
$list = $result->as_array('id', 'username');
~~~

