# Routes, URLs et Liens

Ce chapitre fournit les bases permettant de comprendre la logique de traitement des requêtes, de la génération des URLs et des liens.

## Routage

Comment évoqué dans le chapitre [processus de traitement des requêtes](about.flow), une requête est traitée par la classe [Request] qui tente de trouver une [Route] correspondante et charge les méthodes appropriées du controleur qui permettront de traiter la requete.

Si vous regardez le fichier `APPPATH/bootstrap.php` vous pouvez voir le code ci-dessous qui est exécuté juste avant que la requête ne soit traitée par [Request::instance]:

    Route::set('default', '(<controller>(/<action>(/<id>)))')
      ->defaults(array(
        'controller' => 'welcome',
        'action'     => 'index',
      ));

Ce code crée une route appelée `default` dont l'URI doit avoir le format `(<controller>(/<action>(/<id>)))`. Les éléments entourés par `<>` sont des *clés* et ceux entourés par `()` définissent les parties *optionnelles* de l'URI. Dans le code ci-dessus , l'URI entière est optionnelles ce qui signifie que même une URI vide serait traitée en utilisant les valeurs par défaut spécifiées dans la route. Cela se traduirait par le chargement de la classe `Controller_Welcome` et l'exécution de sa méthode `action_index` pour traiter la requête.

A noter que les routes de Kohana peuvent contenir tous caractères exceptés `()<>`. Dans la route ci-dessus le caractère `/` est utilisé comme séparateur mais tant que l'expression matche l'URI demandée il n'y a aucune restriction sur le format des routes.

### Répertoires

Par soucis d'organisation, il est commun de vouloir organiser certains de vos controleurs dans des sous-répertoires. Par exemple pour grouper votre section d'administration (tout vos controleurs d'administration) de votre site dans un sous-répertoire admin:

    Route::set('admin', 'admin(/<controller>(/<action>(/<id>)))')
      ->defaults(array(
        'directory'  => 'admin',
        'controller' => 'home',
        'action'     => 'index',
      ));

Cette route indique qu'il faut que l'URI commence obligatoirement par `admin` pour matcher. Le sous-répertoire est statiquement assigné à `admin` dans les paramètres par défaut. De cette manière, la requête `admin/users/create` chargera la classe `Controller_Admin_Users` et appellera la méthode `action_create`.

### Expressions régulières

Le système de routage de Kohana utilise des expressions régulière compatible Perl. Par défaut les clés (entourées par `<>`) sont matchées par l'expression `[a-zA-Z0-9_]++` mais vous pouvez définir vos propres expressions pour chacunes des clés en passant un tableau associatif de clés et d'expressions comme paramètre additionnel de la méthode [Route::set]. 

Par exemple, imaginons qu'en plus d'une section administration, votre site contient une section blog dont les controleurs sont situés dans un sous-répertoire blog. Alors vous pouvez soit écrire 2 routes distinctes ou bien tout simplement faire:

    Route::set('sections', '<directory>(/<controller>(/<action>(/<id>)))',
      array(
        'directory' => '(admin|blog)'
      ))
      ->defaults(array(
        'controller' => 'home',
        'action'     => 'index',
      ));
      
Cette route vous permet donc d'avoir 2 sections, 'admin' et 'blog' et d'organiser les controleurs dans des sous-répertoires distincts.

### Exemples de routes

Les possibilités sont bien sûres infinies, néanmoins voici quelques exemples courants:

    /*
     * Raccourcis d'authentification
     */
    Route::set('auth', '<action>',
      array(
        'action' => '(login|logout)'
      ))
      ->defaults(array(
        'controller' => 'auth'
      ));
      
    /*
     * Feeds multi-formats
     *   452346/comments.rss
     *   5373.json
     */
    Route::set('feeds', '<user_id>(/<action>).<format>',
      array(
        'user_id' => '\d+',
        'format' => '(rss|atom|json)',
      ))
      ->defaults(array(
        'controller' => 'feeds',
        'action' => 'status',
      ));
    
    /*
     * Pages statiques
     */
    Route::set('static', '<path>.html',
      array(
        'path' => '[a-zA-Z0-9_/]+',
      ))
      ->defaults(array(
        'controller' => 'static',
        'action' => 'index',
      ));
      
    /*
     * Vous n'aimez pas les slashes?
     *   EditGallery:bahamas
     *   Watch:wakeboarding
     */
    Route::set('gallery', '<action>(<controller>):<id>',
      array(
        'controller' => '[A-Z][a-z]++',
        'action'     => '[A-Z][a-z]++',
      ))
      ->defaults(array(
        'controller' => 'Slideshow',
      ));
      
    /*
     * Recherche rapide
     */
    Route::set('search', ':<query>', array('query' => '.*'))
      ->defaults(array(
        'controller' => 'search',
        'action' => 'index',
      ));

Les Routes sont évaluées dans l'odre dans lequel elles sont définies. C'est pour cette raison que la route par défaut est définie à la fin de sorte que les routes spécifiques soient testées avant.

De plus cela implique qu'il faut faire attention si vous définissez des routes après le chargement des modules, car les routes incluses dans ceux-ci pourrait entrer en conflit.
      
### Paramétres des requêtes

Le répertoire (directory), le controleur (controller) et l'action sont accessibles à travers l'instance [Request] d'une des 2 manières suivantes:

    $this->request->action;
    Request::instance()->action;
    
Toutes les autres clés spécifiées dans vos routes sont accessibles en utilisant:

    $this->request->param('key_name');
    
La méthode [Request::param] peut prendre un second paramètre optionnel permettant de spécifier une valeur par défaut à retourner au cas où la clé n'est pas affectée par la route. Si aucun argument n'est passé, toutes les clés sont passés sous forme d'un tableau associatif.

### Convention

La convention est de placer toutes vos routes dans le fichier `MODPATH/<module>/init.php` si elles concernent un module et sinon, si elles sont spécifiques à l'application, il faut tout simplement les ajouter au fichier `APPPATH/bootstrap.php` au-dessus de la route par défaut. Bien sûr cela ne vous empêche pas de les inclure depuis un fichier externe ou de les générer dynamiquement.
    
## URLs

Outre les capacités puissantes de gestion des routes de Kohana, Kohana fournit aussi des méthodes de génération d'URLs pour vos routes. Vous pouvez bien sûr spécifier des URIs en utilisant [URL::site] pour créer une URL complète:

    URL::site('admin/edit/user/'.$user_id);

Cependant, Kohana fournit aussi une méthode permettant de générer les URIs à partir de la définition des routes que vous avez écrites. C'est extrêmement utile si vos routes sont amenées à changer car vous n'aurez pas à remodifier votre code partout où vous avez spécifié des URIs comme ci-dessus. Voici un exemple de génération dynamique qui correspond à la route `feeds` définie dans la liste d'exemples plus haut:

    Route::get('feeds')->uri(array(
      'user_id' => $user_id,
      'action' => 'comments',
      'format' => 'rss'
    ));

Imaginez que plus tard vous décidez de changer la définition de la route en `feeds/<user_id>(/<action>).<format>`. Avec le code ci-dessus l'URI générée est toujours valide après ce changement! Lorsqu'une partie de l'URI est entourée de paranthèses et qu'elle représente une clé qui n'est pas fournie dans la génération de l'URI et qui n'a pas de valeur par défaut alors cette partie est enlevée de l'URI. C'est le cas de la partie `(/<id>)` de la route par défaut; elle ne sera pas incluse dans l'URI générée si l'id n'est pas fourni.

Une autre méthode pratique est [Request::uri] qui fait la même chose que la précédente méthode excepté qu'elle utilise la route courante. Si la route courante est la route par défaut dont l'URI est `users/list`, il est possible d'écrire le code suivant pour générer des URIs au format `users/view/$id`:

    $this->request->uri(array('action' => 'view', 'id' => $user_id));
    
Au sein d'une vue il est préferrable d'utiliser:

    Request::instance()->uri(array('action' => 'view', 'id' => $user_id));

## Liens

[!!] links stub
