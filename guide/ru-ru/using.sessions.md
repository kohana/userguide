# Сессии и Куки

Kohana предоставляет пару классов, которые облегчают работу с куками и сессиями. На верхнем уровне и сессии, и куки обеспечивают одни и те же функции. Они позволяют разработчику хранить временную или постоянную инфомацию о конкретном клиенте для дальнейшего использования.

Куки следует использовать для хранения публичных (некритичных) данных, неизменных в течении длительного времени. Например, хранить идентификатор пользователя или предпочитаемый язык. Используйте класс [Cookie] для установки и получения кук.

[!!] Kohana работает с "подписанными" куками. Каждая хранимая кука содержит хэша для предотвращения подмены значения куки. Этот хэш генерируется методом [Cookie::salt], который учитывает свойство [Cookie::$salt]. Вам следует [изменить настройки](using.configuration), когда будете опубликовывать свое приложение.

Сессии лучше использовать для хранения временных или секретных данных. Крайне критичную информацию стоит хранить в классе [Session] с драйвером "database" или "native". Когда используется драйвер "cookie", сессия должна быть зашифрована.

[!!] Больше информации о работе с переменными сессии Вы можете получить в статье [семь смертных грехов сессий](http://lists.nyphp.org/pipermail/talk/2006-December/020358.html).

# Хранение, извлечение и удаление данных

[Cookie] и [Session] предоставляют очень схожий API для хранения данных. Главное отличие между ними в том, что доступ к сессии осуществляется как к объекту, а к кукам - как статическому классу (хэлпер).

Получить объект сессии можно посредством метода [Session::instance]:

    // Get the session instance
    $session = Session::instance();

Вы можете также получить все данные сессии с помощью метода [Session::as_array]:

    // Get all of the session data as an array
    $data = $session->as_array();

Также есть возможность переписать глобальную переменную `$_SESSION`. чтобы работать с сессиями в более привычном, стандартном для PHP стиле:

    // Overload $_SESSION with the session data
    $_SESSION =& $session->as_array();
    
    // Set session data
    $_SESSION[$key] = $value;

## Хранение данных {#setting}

Для сохранения данных сессии или куки применяется метод `set`:

    // Set session data
    $session->set($key, $value);

    // Set cookie data
    Cookie::set($key, $value);

    // Store a user id
    $session->set('user_id', 10);
    Cookie::set('user_id', 10);

## Получение данных {#getting}

Извлечение данных сессии или кук возможно посредством метода `get`:

    // Get session data
    $data = $session->get($key, $default_value);

    // Get cookie data
    $data = Cookie::get($key, $default_value);

    // Get the user id
    $user = $session->get('user_id');
    $user = Cookie::get('user_id');

## Удаление данных {#deleting}

Метод `delete` позволяет удалить данные из сессии или кук:

    // Delete session data
    $session->delete($key);

    // Delete cookie data
    Cookie::delete($key);

    // Delete the user id
    $session->delete('user_id');
    Cookie::delete('user_id');

# Настройка {#configuration}

И куки, и сессии имеют несколько параметров, которые влияют на механизм хранение данных. Всегда проверяйте их перед завершением приложения, так как многие из них будут напрямую влиять на безопасность Вашего приложения.

## Настройка кук

Все настройки изменяются через статические свойства. Вы можете изменить их либо через `bootstrap.php`, либо посредством [расширения классов](using.autoloading#class-extension).

Наиболее важный параметр это [Cookie::$salt], он используется для шифрования подписи. Значение необходимо поменять и держать в тайне:

    Cookie::$salt = 'your secret is safe with me';

[!!] Изменение данного значения сделает недействительными все сохраненные ранее куки.

По умолчанию куки хранятся до закрытия браузера. Чтобы указать свое значение для времени жизни, измените параметр [Cookie::$expiration]:

    // Set cookies to expire after 1 week
    Cookie::$expiration = 604800;

    // Alternative to using raw integers, for better clarity
    Cookie::$expiration = Date::WEEK;

Адрес, с которого куки могут быть доступны, может быть ограничен параметром [Cookie::$path].

    // Allow cookies only when going to /public/*
    Cookie::$path = '/public/';

Домен, на котором куки будут доступны, указан в свойстве [Cookie::$domain].

    // Allow cookies only on the domain www.example.com
    Cookie::$domain = 'www.example.com';

Если Вы хотите сделать куку доступной для всех поддоменов, поставьте точку перед началом домена

    // Allow cookies to be accessed on example.com and *.example.com
    Cookie::$domain = '.example.com';

Чтобы разрешить куки только по защищенному (HTTPS) соединению, установите [Cookie::$secure] параметр.

    // Allow cookies to be accessed only on a secure connection
    Cookie::$secure = TRUE;
    
    // Allow cookies to be accessed on any connection
    Cookie::$secure = FALSE;

Защитите куки от доступа через Javascript, изменив параметр [Cookie::$httponly].

    // Make cookies inaccessible to Javascript
    Cookie::$httponly = TRUE;

## Драйверы сессии {#adapters}

При создании или доступе к объекту класс [Session] Вы можете выбрать, какой драйвер использовать. Доступны следующие драйверы:

Native
: Хранит данные в стандартном месте на диске web-сервера. Путь указывается в параметре [session.save_path](http://php.net/manual/session.configuration.php#ini.session.save-path) файла `php.ini` или переопределяется методом [ini_set](http://php.net/ini_set).

Database
: Хранит информацию в базе данных с помощью класса [Session_Database]. Для работы требуется подключенный модуль [Database].

Cookie
: Хранит данные в куках, с помощью класса [Cookie]. **Для данного драйвера предельный размер сессии будет равен 4Кб **

Драйвер по умолчанию может быть установлен в [Session::$default]. Изначально это драйвер "native".

[!!] Как и с куками, установка параметра "lifetime" в "0" означает, что сессия будет уничтожена после закрытия браузера.

### Настройка драйвера сессии

Вы можете применить настройки для каждого драйвера, создав конфигурационный файл `APPPATH/config/session.php`. Следующий пример настроек определяет конфигурацию для каждого драйвера:

    return array(
        'native' => array(
            'name' => 'session_name',
            'lifetime' => 43200,
        ),
        'cookie' => array(
            'name' => 'cookie_name',
            'encrypted' => TRUE,
            'lifetime' => 43200,
        ),
        'database' => array(
            'name' => 'cookie_name',
            'encrypted' => TRUE,
            'lifetime' => 43200,
            'group' => 'default',
            'table' => 'table_name',
            'columns' => array(
                'session_id'  => 'session_id',
        		'last_active' => 'last_active',
        		'contents'    => 'contents'
            ),
            'gc' => 500,
        ),
    );

#### Драйвер Native {#adapter-native}

Тип       | Параметр  | Описание                                          | По умолчанию
----------|-----------|---------------------------------------------------|-----------
`string`  | name      | имя сессии                                        | `"session"`
`integer` | lifetime  | время жизни сессии (в секундах)                   | `0`

#### Cookie Adapter {#adapter-cookie}

Тип       | Параметр  | Описание                                          | По умолчанию
----------|-----------|---------------------------------------------------|-----------
`string`  | name      | имя куки, используемой для хранения сессии        | `"session"`
`boolean` | encrypted | шифровать данные с помощью [Encrypt]?             | `FALSE`
`integer` | lifetime  | время жизни сессии (в секундах)                   | `0`

#### Database Adapter {#adapter-database}

Тип       | Параметр  | Описание                                          | По умолчанию
----------|-----------|---------------------------------------------------|-----------
`string`  | group     | название группы [Database::instance]              | `"default"`
`string`  | table     | имя таблицы, в которой хранить данные             | `"sessions"`
`array`   | columns   | ассоциативный массив псевдонимов полей            | `array`
`integer` | gc        | дает 1:x шанс, что запустится сборка мусора       | `500`
`string`  | name      | имя куки, используемой для хранения сессии        | `"session"`
`boolean` | encrypted | шифровать данные с помощью [Encrypt]?             | `FALSE`
`integer` | lifetime  | время жизни сессии (в секундах)                   | `0`

##### Структура таблицы

Вам придется создать таблицу для хранения сессии в базе данных. Вот структура по умолчанию:

    CREATE TABLE  `sessions` (
        `session_id` VARCHAR(24) NOT NULL,
        `last_active` INT UNSIGNED NOT NULL,
        `contents` TEXT NOT NULL,
        PRIMARY KEY (`session_id`),
        INDEX (`last_active`)
    ) ENGINE = MYISAM;

##### Поля таблицы

Вы можете изменить имя полей, чтобы использовать существующую таблицу. По умолчанию используется имя ключа.

session_id
: название поля "id"

last_active
: метка времени UNIX для последнего времени обновления сессии

contents
: данные сессии, хранимые в виде сериализованной и (необязательно) зашифрованной строки
