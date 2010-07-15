# Базы данных {#top}

Kohana 3.0 поставляется с надежным модулем для работы с базами данных. По умолчанию модуль БД содержит драйверы для [MySQL](http://ru.php.net/manual/en/book.mysql.php) и [PDO](http://ru2.php.net/pdo).

Модуль Database содержится в дистрибутиве Kohana 3.0, но требует подключение перед использованием. В файле `application/bootstrap.php` измените вызов метода [Kohana::modules], подключив модуль Database:

    Kohana::modules(array(
        ...
        'database' => MODPATH.'database',
        ...
    ));

## Настройка {#configuration}

После подключения модуля необходимо создать файл настроек, чтобы модуль знал как соединиться с базой данных. Пример конфигурационного файла можно найти в `modules/database/config/database.php`.

Структура группы настроек базы данных ("instance") выглядит следующим образом:

    string INSTANCE_NAME => array(
        'type'         => string DATABASE_TYPE,
        'connection'   => array CONNECTION_ARRAY,
        'table_prefix' => string TABLE_PREFIX,
        'charset'      => string CHARACTER_SET,
        'profiling'    => boolean QUERY_PROFILING,
    ),

[!!] В одном конфигурационном файле можно определить несколько таких групп.

Очень важно понимать каждый параметр конфигурации.

INSTANCE_NAME
:  Соединения могут быть названы как Вы захотите, но одно из них обязательно должно называться "default" (группа по умолчанию).

DATABASE_TYPE
:  Один из установленных драйверов баз данных. Kohana поставляется с драйверами "mysql" и "pdo".

CONNECTION_ARRAY
:  Специфические настройки драйвера для соединения с БД. (Настройки драйвера описаны [ниже](#connection_settings).)

TABLE_PREFIX
:  Префикс, который будет добавлен к названиям таблиц классом [query builder](#query_building).

QUERY_PROFILING
:  Включает [профилирование](debugging.profiling) запросов к БД.

### Пример

Ниже описаны два соединения с MySQL, одно локальное, а другое удаленное.

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

### Настройки соединения {#connection_settings}

Каждый драйвер БД имеет свои настройки соединения.

#### MySQL

БД MySQL поддерживает следующие параметры массива `connection`:

Тип       | Параметр   |  Описание                   | Значение по умолчанию
----------|------------|-----------------------------| -------------------------
`string`  | hostname   | Имя сервера или IP-адрес    | `localhost`
`integer` | port       | Номер порта                 | `NULL`
`string`  | socket     | сокет UNIX                  | `NULL`
`string`  | username   | Имя пользователя            | `NULL`
`string`  | password   | Пароль                      | `NULL`
`boolean` | persistent | Постоянное соединение       | `FALSE`
`string`  | database   | Имя базы данных (схемы)     | `kohana`

#### PDO

База данных PDO database принимает следующие опции массива `connection`:

Тип       | Параметр   |  Описание                   | Значение по умолчанию
----------|------------|-----------------------------| -------------------------
`string`  | dsn        | Идентификатор источника PDO | `localhost`
`string`  | username   | Имя пользователя            | `NULL`
`string`  | password   | Пароль                      | `NULL`
`boolean` | persistent | Постоянное соединение       | `FALSE`

!! Если Вы используете PDO и не уверены, что прописывать в параметре `dsn`, ознакомьтесь с [PDO::__construct](http://php.net/pdo.construct).

## Соединения и сущности {#connections}

Каждая группа настроек связана с экземпляром базы данных ("сущность"). Каждая сущность может быть получена через вызов [Database::instance]:

    $default = Database::instance();
    $remote  = Database::instance('remote');

Чтобы порвать соединение с базой данных, просто уничтожьте объект:

    unset($default, Database::$instances['default']);

Если Вы хотите разорвать соединения со всеми сущностями за раз:

    Database::$instances = array();

## Создаем запросы {#making_queries}

Существует два способа создать запросы. Простейший путь - использование [Database_Query] для создания запросов, через [DB::query]. Эти запросы называются "подготовленные выражения" и позволяют устанавливать параметры, которые автоматически экранируются. Второй путь - построение через специальные методы. Это возможно с помощью объекта [query builder](#query_building).

[!!] Все запросы выполняются методом `execute`, который принимает объект [Database] или имя сущности. Смотри [Database_Query::execute].

### Подготовленные выражения

Подготовленные выражения позволяют писать SQL-запросы вручную, в то же время значения будут автоматически экранированы, чтобы избежать [SQL-инъекций](http://wikipedia.org/wiki/SQL_Injection). Создать запрос просто:

    $query = DB::query(Database::SELECT, 'SELECT * FROM users WHERE username = :user');

Фабричный метод [DB::query] создает новый класс [Database_Query] и возвращает его, поддерживая цепочки вызовов. Запрос содержит параметр `:user`, которому будет назначено значение:

    $query->param(':user', 'john');

[!!] Имя параметра может быть любой строкой, в дальнейшем оно будет заменено функцией [strtr](http://php.net/strtr). Рекомендуется **не** использовать знак доллара в составе имени параметра во избежание путаницы.

Если Вы хотите увидеть SQL, предназначенный для выполнения, просто преобразуйте объект в строку:

    echo Kohana::debug((string) $query);
    // выведет:
    // SELECT * FROM users WHERE username = 'john'

Также Вы можете изменить параметр `:user`, снова вызвав [Database_Query::param]:

    $query->param(':user', $_GET['search']);

[!!] Для задания нескольких параметров за раз используйте [Database_Query::parameters].

После установки всех параметров можно выполнить запрос:

    $query->execute();

Также допустимо привязать параметр к переменной, используя [ссылки на переменные]((http://php.net/language.references.whatdo)). Это может стать очень полезным при многократном выполнении схожих запросов:

    $query = DB::query(Database::INSERT, 'INSERT INTO users (username, password) VALUES (:user, :pass)')
        ->bind(':user', $username)
        ->bind(':pass', $password);

    foreach ($new_users as $username => $password)
    {
        $query->execute();
    }

В данном примере переменные `$username` и `$password` меняются в каждой итерации цикла `foreach`. Когда переменные меняются, также изменяют значение и параметры запроса `:user` и `:pass`. Правильное и уместное использование привязки параметров может сделать код более компактным.

### Конструктор запросов {#query_building}

Динамическое создание запросов с использованием объектов и методов позволяет писать запросы очень быстро и предсказуемо. Построитель запросов также заключает в кавычки идентификаторы (имена таблиц и полей), также как и экранирует значения.

[!!] На данный момент невозможно комбинировать построитель запросов с подготовленными выражениями.

#### Выборка (SELECT)

Каждый тип запросов представлен отдельным классом со своими методами. К примеру, чтобы создать запрос типа SELECT, используем [DB::select]:

    $query = DB::select()->from('users')->where('username', '=', 'john');

По умолчанию, [DB::select] будет запрашивать все поля (`SELECT * ...`), но можно указать, какие столбцы извлекать:

    $query = DB::select('username', 'password')->from('users')->where('username', '=', 'john');

А теперь посмотрим, к чему привела эта цепочка вызовов. Сперва мы создаем новый объект выборки методом [DB::select]. Далее, устанавливаем таблицу(ы) с помощью метода `from`. И напоследок, ищем конкретные записи через метод `where`. Можно посмотреть генерируемый код SQL просто преобразовывая объект к строке:

    echo Kohana::debug((string) $query);
    // Покажет:
    // SELECT `username`, `password` FROM `users` WHERE `username` = 'john'

Обратили внимание, что имена полей и таблиц автоматически экранированы, также как и значения? Это одно из ключевых преимуществ использования построителя запросов.

Также допустимо создавать псевдонимы `AS` для выборки:

    $query = DB::select(array('username', 'u'), array('password', 'p'))->from('users');

Сгенерируется следующий SQL-запрос:

    SELECT `username` AS `u`, `password` AS `p` FROM `users`

#### Вставка (INSERT)

Чтобы создать записи в базе данных, используйте [DB::insert], создающий запросы INSERT:

    $query = DB::insert('users', array('username', 'password'))->values(array('fred', 'p@5sW0Rd'));

Запрос сформирует код:

    INSERT INTO `users` (`username`, `password`) VALUES ('fred', 'p@5sW0Rd')

#### Обновление (UPDATE)

Для редактирования существующей записи предназначен метод [DB::update], он возвращает запрос UPDATE:

    $query = DB::update('users')->set(array('username' => 'jane'))->where('username', '=', 'john');

В результате получим запрос:

    UPDATE `users` SET `username` = 'jane' WHERE `username` = 'john'

#### Удаление (DELETE)

Для удаления записи используется [DB::delete], он создает запрос DELETE:

    $query = DB::delete('users')->where('username', 'IN', array('john', 'jane'));

Получим следующий запрос:

    DELETE FROM `users` WHERE `username` IN ('john', 'jane')

#### Функции работы с базами данных {#database_functions}

Иногда Вы можете столкнуться с ситуацией, когда надо вызвать `COUNT` или другую функцию СУБД внутри запроса. Построитель запросов позволяет использовать эти функции двумя способами. Первый - применение кавычек внутри псевдонимов:

    $query = DB::select(array('COUNT("username")', 'total_users'))->from('users');

Это выглядит почти также, как и стандартные псевдонимы, но имя поля обрамлено двойными кавычками. Каждый раз, когда значение в двойных кавычках обнаруживается внутри имени столбца, **только** часть внутри этих кавычек будет экранирована. Сгенерируется код SQL:

    SELECT COUNT(`username`) AS `total_users` FROM `users`

#### Сложные выражения

"Закавыченные" псевдонимы могут решить многие проблемы, но время от времени может понадобиться сложное выражение. В таких случаях надо использовать выражения, создаваемые методом [DB::expr]. Выражение используется для прямого вывода, экранирование не происходит.

