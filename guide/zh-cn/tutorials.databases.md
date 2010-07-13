# 数据库 {#top}

Kohana 3.0 采用了更加健壮的模块方式开发。默认情况下数据库模块支持 [MySQL](http://php.net/mysql) 和 [PDO](http://php.net/pdo) 两种驱动方式。

默认 Kohana 3.0 中包含了数据库模块，但是使用之前必须在 `application/bootstrap.php` 文件中的 [Kohana::modules] 方法中开启它。

    Kohana::modules(array(
        ...
        'database' => MODPATH.'database',
        ...
    ));

## 配置 {#configuration}

开启模块后接着需要配置数据库，这样才能保证数据库的正常连接。比如说配置文件存放在 `modules/database/config/database.php` 文件中。

数据库配置组的结构，我们称之为 "instance"，就像下面这个样子：

    string INSTANCE_NAME => array(
        'type'         => string DATABASE_TYPE,
        'connection'   => array CONNECTION_ARRAY,
        'table_prefix' => string TABLE_PREFIX,
        'charset'      => string CHARACTER_SET,
        'profiling'    => boolean QUERY_PROFILING,
    ),

[!!] 配置文件里可以定义多个 instances 配置组。

重点了解一下各项设置的含义。

INSTANCE_NAME
:  配置组的名称，任意命名，但是最好保留一个名为 "default" 的默认连接。

DATABASE_TYPE
:  数据库驱动类型。Kohana 目前支持 "mysql" 和 "pdo" 两种驱动。

CONNECTION_ARRAY
:  配置上述驱动的连接项。（驱动连接项在[下面](#connection_settings)有说明）

TABLE_PREFIX
:  表前缀，用于通过 [查询器](#query_building) 添加到所有的表名。

QUERY_PROFILING
:  开始数据库查询的 [profiling](debugging.profiling)。

### 范例

范例中共给出了两种 MySQL 连接：一个是本地，一个是远程。

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

### 连接设置 {#connection_settings}

每种数据库驱动的连接方式各有不同。

#### MySQL

在 `connection` 数组中 MySQL 数据库可接受下面选项：

类型      | 选项       |  描述                      | 默认值
----------|------------|----------------------------| -------------------------
`string`  | hostname   | Hostname of the database   | `localhost`
`integer` | port       | Port number                | `NULL`
`string`  | socket     | UNIX socket                | `NULL`
`string`  | username   | Database username          | `NULL`
`string`  | password   | Database password          | `NULL`
`boolean` | persistent | Persistent connections     | `FALSE`
`string`  | database   | Database name              | `kohana`

#### PDO

在 `connection` 数组中 PDO 数据库可接受下面选项：

类型      | 选项       |  描述                      | 默认值
----------|------------|----------------------------| -------------------------
`string`  | dsn        | PDO data source identifier | `localhost`
`string`  | username   | Database username          | `NULL`
`string`  | password   | Database password          | `NULL`
`boolean` | persistent | Persistent connections     | `FALSE`

[!!] 如果你使用的是 PDO 而且并不确定如何去配置 `dsn` 选项，请查阅 [PDO::__construct](http://php.net/pdo.construct) 的相关资料。

## 连接并实例化 {#connections}

每个配置组都可以当作数据库的实例化对象。每个实例化都是通过调用 [Database::instance] 方法来访问:

    $default = Database::instance();
    $remote  = Database::instance('remote');

关闭数据库连接的最简单的方法（销毁对象）：

    unset($default, Database::$instances['default']);

如果你想关闭所有数据库的实例化只需：

    Database::$instances = array();

## 如何查询 {#making_queries}

这里共有两种不同的方法进行查询。最简单的一次查询方式是通过 [DB::query] 使用 [Database_Query] 来创建查询。这些查询被称之为 "预处理语句"，并且允许设置的查询参数自动的转义。而第二种查询方式是通过方法调用来组建查询。它们都是通过[查询器](#query_building) 完成。

[!!] 所有的查询都必须调用 `execute` 方法才能进行运行查询，可接受一个 [Database] 对象或者实例化名称。详情请参见 [Database_Query::execute]。

### 预处理语句

使用预处理语句可以让你手动编写 SQL 语句的同时还能自动转义查询的值以防止 [SQL 注入](http://wikipedia.org/wiki/SQL_Injection)。首先我们先来一个简单的查询：

    $query = DB::query(Database::SELECT, 'SELECT * FROM users WHERE username = :user');

[DB::query] factory 方法为我们创建了一个新的 [Database_Query] 类并允许串连（chaining）。查询语句包含一个 `:user` 参数，这个参数我们可以分配给它一个值：

    $query->param(':user', 'john');

[!!] 参数名称可以是任意的可以使用 [strtr](http://php.net/strtr) 替换的字符串。强烈建议**不要**使用美元符号当作参数名以免混淆。

如果你想显示 SQL 执行的语句，只需要简单的强制转换对象为字符串即可：

    echo Kohana::debug((string) $query);
    // 应该显示：
    // SELECT * FROM users WHERE username = 'john'

如果你想更新 `:user` 参数只需要再次调用 [Database_Query::param] 即可：

    $query->param(':user', $_GET['search']);

[!!] 如果你想一次设置多个参数，你需要使用 [Database_Query::parameters]。

当你分配完毕每个参数之后，你只需要执行下面的方法来执行查询语句：

    $query->execute();

使用[变量引用]((http://php.net/language.references.whatdo)) 也可以绑定参数到一个变量中。当多次执行同条语句的时候是非常管用的：

    $query = DB::query(Database::INSERT, 'INSERT INTO users (username, password) VALUES (:user, :pass)')
        ->bind(':user', $username)
        ->bind(':pass', $password);

    foreach ($new_users as $username => $password)
    {
        $query->execute();
    }

在上面的例子中，变量 `$username` 和 `$password` 在每次使用 `foreach` 语句循环的时候都会改变。如果变量改变了，那么语句中的参数 `:user` 和 `:pass` 也会跟着改变。这种方法使用得当的话是非常节省时间的。

### 查询器 {#query_building}

使用对象和方法动态查询使得查询语句可以以一种不可知论的方法迅速的组建起来。查询器也添加了和值引用一样好的标识符（表和列名）引用。

[!!] 目前为止，查询器无法有效的和预处理语句组合。

#### SELECT

每种数据库查询类型都是用过不同的类引用，它们每一个都有自己的方法。比如，创建一个 SELECT 查询，我们需要使用 [DB::select]：

    $query = DB::select()->from('users')->where('username', '=', 'john');

默认情况下，[DB::select] 会选择所有的列（`SELECT * ...`），但是你也可以指定返回的某些列：

    $query = DB::select('username', 'password')->from('users')->where('username', '=', 'john');

现在让我们花一点时间看看方法是如何串连完成的。首先，我们使用 [DB::select] 方法创建了一个选择对象。接着，我们使用 `from` 方法选择表。最后我们使用 `where` 方法查询一个指定的记录。好了，执行之后我们看看执行了怎么样的一条 SQL 语句，还是老方法，强制转换对象为字符串：

    echo Kohana::debug((string) $query);
    // 应该显示：
    // SELECT `username`, `password` FROM `users` WHERE `username` = 'john'

注意列名，表名是如何和值很好的转义啊？这就是使用查询器最重要的好处。

查询的时候它也支持 `AS` 方式的别名：

    $query = DB::select(array('username', 'u'), array('password', 'p'))->from('users');

生成的 SQL 语句：

    SELECT `username` AS `u`, `password` AS `p` FROM `users`

#### INSERT

向数据库插入一条记录，我们使用 [DB::insert] 方法创建一条 INSERT 语句：

    $query = DB::insert('users', array('username', 'password'))->values(array('fred', 'p@5sW0Rd'));

生成的 SQL 语句：

    INSERT INTO `users` (`username`, `password`) VALUES ('fred', 'p@5sW0Rd')

#### UPDATE

更新已存在的记录，我们使用 [DB::update] 方法创建一条 UPDATE 语句：

    $query = DB::update('users')->set(array('username' => 'jane'))->where('username', '=', 'john');

生成的 SQL 语句：

    UPDATE `users` SET `username` = 'jane' WHERE `username` = 'john'

#### DELETE

删除已存在的记录，我们使用 [DB::delete] 方法创建一条 DELETE 语句：

    $query = DB::delete('users')->where('username', 'IN', array('john', 'jane'));

生成的 SQL 语句：

    DELETE FROM `users` WHERE `username` IN ('john', 'jane')

#### 数据库函数 {#database_functions}

有些时候，你可以会碰到这样的一个情况：当你需要在查询时调用 `COUNT` 或者一些其他数据库自身函数。其实查询器可以通过两种方法支持这些函数。第一种是使用别名引用：

    $query = DB::select(array('COUNT("username")', 'total_users'))->from('users');

这看起来十分的相似于 `AS` 别名，但是注意列名是通过双引号括起来的。任何时候一个带有双引号的值出现在列名内部的时候，这部分在双引号内部的引用**仅仅**只能被转义。上面的查询方法生成的 SQL 语句：

    SELECT COUNT(`username`) AS `total_users` FROM `users`

#### 复杂的表达式

别名引用可以解决大部分的问题。但是有时你可能因需要复杂的表达式陷入困境。由于这些原因，你可以使用数据库表达式 [DB::expr] 创建。数据库表达式可以作为直接输入而并不会执行转义。
