# Base de données {#top}

Kohana 3.0 intégre un robuste module permettant de travailler avec les base de données. Par défauut, le module supporte [MySQL](http://php.net/mysql) et [PDO](http://php.net/pdo).

Le module base de données est inclus par défaut dans votre installation de Kohana 3.0 mais n'est pas activé. Pour l'activer, éditez le fichier `application/bootstrap.php` et modifiez l'appel à [Kohana::modules] pour y inclure le module base de données:

    Kohana::modules(array(
        ...
        'database' => MODPATH.'database',
        ...
    ));

## Configuration {#configuration}

Aprés activation du module, il vous faut préciser les paramètres de configuration permettant à votre application de se connecter à la base de données. Un exemple de fichier de configuration peut être trouvé sous `modules/database/config/database.php`.

La structure d'un groupe de configuration pour une base de données, appelé instance, est de cette forme:

    string INSTANCE_NAME => array(
        'type'         => string DATABASE_TYPE,
        'connection'   => array CONNECTION_ARRAY,
        'table_prefix' => string TABLE_PREFIX,
        'charset'      => string CHARACTER_SET,
        'profiling'    => boolean QUERY_PROFILING,
    ),

[!!] Plusieurs instances différentes de ces configurations peuvent être définies dans le fichier de configuration.

La compréhension de l'ensemble de ces paramètres est importante:

INSTANCE_NAME
:  nom personnalisé de l'instance. Il est obligatoire d'avoir au moins une instance appelée "default".

DATABASE_TYPE
:  type de base de données. Valeurs acceptées: "mysql" et "pdo".

CONNECTION_ARRAY
:  options de connection spécifiques au type de base de données choisis. Ces options sont explicités [plus bas](#connection_settings).

TABLE_PREFIX
:  prefixe qui sera ajouté à tous les noms de table par le [constructeur de requêtes](#query_building).

QUERY_PROFILING
:  activer le [profiling](debugging.profiling) des requêtes.

### Exemple

L'exemple ci-dessous est composé de 2 connections MySQL, la première locale et l'autre distante:

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

### Options de connection {#connection_settings}

Chacun des types de base de données possède des options différentes de connection.

#### MySQL

Les options de connection MySQL sont les suivantes:

Type      | Option     |  Description               | Valeur par défaut
----------|------------|----------------------------|--------------------------
`string`  | hostname   | Hôte hébergeant la base    | `localhost`
`integer` | port       | Numéro de port             | `NULL`
`string`  | socket     | Socket UNIX                | `NULL`
`string`  | username   | Utilisateur                | `NULL`
`string`  | password   | Mot de passe               | `NULL`
`boolean` | persistent | Connections persistantes   | `FALSE`
`string`  | database   | Nom de base de la base     | `kohana`

#### PDO

Les options de connection PDO sont les suivantes:

Type      | Option     |  Description               | Valeur par défaut
----------|------------|----------------------------|--------------------------
`string`  | dsn        | Source PDO                 | `localhost`
`string`  | username   | Utilisateur                | `NULL`
`string`  | password   | Mot de passe               | `NULL`
`boolean` | persistent | Connections persistantes   | `FALSE`

!! Si vous utilisez PDO et n'êtes pas sûr de la valeur du `dsn`, veuillez consulter [PDO::__construct](http://php.net/pdo.construct).

## Connections et Instances {#connections}

Chaque groupe de configuration est accessible en tant qu'instance de base de données. On accède à une instance en appelant [Database::instance]:

    $default = Database::instance();
    $remote  = Database::instance('remote');

Pour se déconnecter de la base de données, il suffit de détruire l'objet correspondant:

    unset($default, Database::$instances['default']);

Si vous souhaitez déconnecter l'ensemble des instances d'un coup alors écrivez:

    Database::$instances = array();

## Ecrire des requêtes {#making_queries}

Il existe 2 manières d'écrire des requêtes dans Kohana. La manière la plus simple est d'utiliser le [constructeur de requête](Query_Builder), via [DB::query]. Ces requêtes sont appelées des "requêtes préparées" ou prepared statements et permettent l'échappement automatique des paramètres de la requête.

La seconde manière est d'appeler directement les méthodes voulues. 

[!!] Toutes les requêtes sont executées via la méthode `execute`, qui prend en paramètre un objet base de données ou un nom d'instance. Pour plus d'informations, consultez [Database_Query::execute].

### Requêtes préparées

L'utilisation de requêtes préparées permet d'écrire des requetes SQL manuellement tout en échappant les paramètres de la requête automatiquement permettant ainsi de se prémunir contre les [injections SQL](http://wikipedia.org/wiki/SQL_Injection). La création d'une requête est simple:

    $query = DB::query(Database::SELECT, 'SELECT * FROM users WHERE username = :user');

La méthode [DB::query] créé un objet [Database_Query] et permet un chainage des méthodes. La requête contient un paramètre `:user` que l'on peut assigner comme suit:

    $query->param(':user', 'john');

[!!] Les noms de paramètre peuvent être nimporte quelle chaine de caractères puisqu'elles sont remplacées en utilisant la fonction [strtr](http://php.net/strtr). Il est vivement recommandé de ne **pas** utiliser de signe dollars ($) pour éviter toute confusion.

Si vous souhaitez afficher la requête SQL qui va être exécutée, il vous suffit de caster l'objet en chaine de caractères comme suit:

    echo Kohana::debug((string) $query);
    // Affichera:
    // SELECT * FROM users WHERE username = 'john'

Vous pouvez aussi ré-assigner `:user` ultérieurement en appelant [Database_Query::param]:

    $query->param(':user', $_GET['search']);

[!!] Pour assigner plusieurs paramètres à la fois, vous pouvez utiliser [Database_Query::parameters].

Une fois chacuns des paramètres de votre requête assignés, l'exécution de la requête se fait via:

    $query->execute();

Enfin, il est aussi possible d'assigner un paramètre à une [variable passée par référence](http://php.net/language.references.whatdo). Cela peut s'avérer très utile lors de l'exécution de la même requête plusieurs fois avec des paramètres différents:

    $query = DB::query(Database::INSERT, 'INSERT INTO users (username, password) VALUES (:user, :pass)')
        ->bind(':user', $username)
        ->bind(':pass', $password);

    foreach ($new_users as $username => $password)
    {
        $query->execute();
    }

Dans l'exemple ci-dessus, les variables `$username` and `$password` sont changées à chacune des itérations de la boucle `foreach`. Cela s'avére très puissant et peut vous permettre d'alléger votre code.

### Construction de requêtes {#query_building}

La création dynamique de requêtes en utilisant des objets et des méthodes de classe permet de créér des requêtes sans avoir de connaissances sur le langage SQL. Le constructeur se charge d'échapper les noms de table et colonnes mais aussi les valeurs des paramètres des requêtes.

[!!] A ce jour, Kohana ne dispose pas de moyens de combiner les requêtes préparées et la construction dynamique de requêtes.

#### SELECT

Chaque type de requête en base de données est représenté par une classe, chacunes possédant ses propres méthodes. Par exemple, pour créér une requête SELECT, utilisez [DB::select]:

    $query = DB::select()->from('users')->where('username', '=', 'john');

Par défault, [DB::select] sélectionnera toutes les colonnes (`SELECT * ...`), mais vous pouvez aussi spécifier ces colonnes:

    $query = DB::select('username', 'password')->from('users')->where('username', '=', 'john');

L'exemple ci-dessus illustre aussi la puissance du chainage de méthodes qui permet en une seule ligne de spécifier les paramètres de sélection, la table et les critères de filtrage via la méthode `where`. De la même manière que précédemment, si vous souhaitez afficher la requête SQL qui va être exécutée, il vous suffit de caster l'objet en chaine de caractères comme suit:

    echo Kohana::debug((string) $query);
    // Affichera:
    // SELECT `username`, `password` FROM `users` WHERE `username` = 'john'

Notez que tout est échappé correctement et c'est là l'un des grands avantages de l'utilisation du constructeur de requêtes.

La création d'alias `AS` se fait comme ci-dessous:

    $query = DB::select(array('username', 'u'), array('password', 'p'))->from('users');
    // Requête exécutée:
    // SELECT `username` AS `u`, `password` AS `p` FROM `users`

#### INSERT

Pour insérer des enregistrements dans la base de données il faut utiliser [DB::insert]:

    $query = DB::insert('users', array('username', 'password'))->values(array('fred', 'p@5sW0Rd'));
    // Requête exécutée:
    // INSERT INTO `users` (`username`, `password`) VALUES ('fred', 'p@5sW0Rd')

#### UPDATE

La modification d'un enregistrement en base se fait via [DB::update]:

    $query = DB::update('users')->set(array('username' => 'jane'))->where('username', '=', 'john');
    // Requête exécutée:
    // UPDATE `users` SET `username` = 'jane' WHERE `username` = 'john'

#### DELETE

Pour supprimer un enregistrement, il faut utiliser [DB::delete]:

    $query = DB::delete('users')->where('username', 'IN', array('john', 'jane'));
    // Requête exécutée:
    // DELETE FROM `users` WHERE `username` IN ('john', 'jane')

#### Fonctions spécifiques {#database_functions}

Il est commun d'utiliser des fonctions spécifiques de base de données telles que `COUNT`. Le constructeur de requête vous permet de les utiliser de 2 manières. La première est la suivante:

    $query = DB::select(array('COUNT("username")', 'total_users'))->from('users');

Ca ressemble beaucoup à l'aliasing `AS` mais notez que le nom de colonne est entouré de doubles quotes. A chaque fois qu'un nom de colonne est entouré de doubles quotes, alors **seules** les parties entourées seront échappées. Cette requête générerait le SQL suivant:

    SELECT COUNT(`username`) AS `total_users` FROM `users`

#### Expressions complexes

De temps à autre on a besoin d'écrire des requêtes contenant des expressions complexes. Dans ce cas, cette expression sera créé via [DB::expr]. Une expression est prise telle quelle par la méthode et de ce fait aucun échappement n'est fait.