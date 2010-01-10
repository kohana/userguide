# Mise à jour depuis 2.x

Kohana v3 fonctionne très différemment de Kohana 2.x, néanmoins vous trouverez ci-dessous une liste d'astuces qui pourront vous aider dans votre tâche de mise à jour.

## Conventions de nommage

La série 2.x différentie les 'types' de classes (i.e. controleur, modele etc.) en utilisant des suffixes. Dans la série 3.0, cette approche a été abandonnée au profit des conventions du framework Zend c'est-à-dire que les noms de classe sont les chemins vers les classes elles-mêmes. Les répertoires du chemin sont séparés par le caractère underscore au lieu du slashe (i.e. `/some/class/file.php` devient `Some_Class_File`).

Pour plus d'informations consultez la documentatation sur les [conventions de nommage](start.conventions).

## Librairie Input

La librairie Input a été supprimée en faveur de l'utilisation directe des variables `$_GET` et `$_POST`. 

### Protection XSS

Si vous avez besoin de nettoyer des données contre des attaques XSS, vous pouvez utiliser [Security::xss_clean] de la manière suivante:

	$_POST['description'] = security::xss_clean($_POST['description']);

Vous pouvez aussi utiliser [Security::xss_clean] en tant que filtre via la librairie [Validate]:

	$validation = new Validate($_POST);
	
	$validate->filter('description', 'Security::xss_clean');

### POST & GET

Une des fonctionnalités très intéressante de la librairie Input était que lors de la tentative de lecture d'une variable superglobale, si celle-ci n'existait pas, il était possible de spécifier la valeur par défaut retournée i.e.:

	$_GET = array();
	
	// On assigne à $id la valeur 1
	$id = Input::instance()->get('id', 1);
	
	$_GET['id'] = 25;
	
	// On assigne à $id la valeur 25
	$id = Input::instance()->get('id', 1);

En 3.0 cette fonctionnalité est rendue par la méthode [Arr::get]:

	$_GET = array();
	
	// On assigne à $id la valeur 1
	$id = Arr::get($_GET, 'id', 1);
	
	$_GET['id'] = 42;
	
	// On assigne à $id la valeur 42
	$id = Arr::get($_GET, 'id', 1);

## Librairie ORM

De nombreux changements majeurs ont été faits sur la librairie ORM depuis la série 2.x, et voici quelques-uns des problèmes les plus courants que vous pourrez rencontrer:

### Variables de classe

Toutes les variables de classe sont désormais préfixées par un underscore (_) et ne sont plus accessibles via `__get()`. A la place, vous devez appeler une méthode portant le nom de la propriété sans le caractère underscore.

Par exemple, la propriété `loaded` en 2.x devient désormais `_loaded` et est accessible depuis l'extérieur via `$model->loaded()`.

### Relations

En 2.x, l'itération sur les objets liés à un modèle se faisait comme suit:

	foreach($model->{relation_name} as $relation)

Cependant avec la nouvelle librarie 3.0 cela ne fonctionnera pas. En effet en version 2.3, toutes les requêtes sont générées avec une portée globale, c'est-à-dire qu'il est impossible de construire 2 requêtes simultanément. Par exemple:

# TODO: NEED A DECENT EXAMPLE!!!!

La requête échouera car la seconde requête hérite des conditions de la première et fausse donc les filtres.

En 3.0 ce problème a été corrigé car chaque requête à sa propre portée. Cela signifie aussi que certains de vos anciens codes ne fonctionneront plus. Prenez par exemple:

	foreach(ORM::factory('user', 3)->where('post_date', '>', time() - (3600 * 24))->posts as $post)
	{
		echo $post->title;
	}

[!!] (Voir [le tutorial sur la Base de Données](tutorials.databases) pour la nouvelle syntaxe des requêtes)

En 2.3 on reçoit un itérateur sur tous les posts de l'utilisateur d'id 3 et dont la date est dans l'intervalle spécifié. Au lieu de ça, la condition 'where' sera appliquée au modèle 'user' et la requête retournera un objet `Model_Post` avec les conditions de jointure comme spécifié.

Pour obtenir le même résultat qu'en 2.x, en 3.0 la structure de la requête doit être modifiée:

	foreach(ORM::factory('user', 3)->posts->where('post_date', '>', time() - (36000 * 24))->find_all() as $post)
	{
		echo $post->title;
	}

Cela s'applique aussi aux relations `has_one`:

	// Incorrect
	$user = ORM::factory('post', 42)->author;
	// Correct
	$user = ORM::factory('post', 42)->author->find();

### Relations Has and belongs to many

En 2.x vous pouvez spécifier des relations `has_and_belongs_to_many`.  En 3.0 cette fonctionnalité a été renommée en `has_many` *through*.

Dans vos modèles vous définissez une relation `has_many` avec les autres modèles et vous ajoutez un attribut `'through' => 'table'`, où `'table'` est le nom de la table de jointure. Par exemple dans la relation posts<>catégories:

	$_has_many = array
	(
		'categories' => 	array
							(
								'model' 	=> 'category', // Le modèle étranger
								'through'	=> 'post_categories' // La table de jointure
							),
	);

Si vous avez configuré Kohana pour utiliser une prefixe de table vous n'avez pas besoin d'explicitement préfixer la table.

### Clés étrangères

En 2.x, pour surcharger une clé étrangère vous deviez spécifier la relation auquelle elle appartenait et ajouter votre nouvelle clé étrangère dans la propriété `$foreign_keys`.

En 3.0 il faut juste définir une clé `foreign_key` dans la définition de la relation comme suit:

	Class Model_Post extends ORM
	{
		$_belongs_to = 	array
						(
							'author' => array
										(
											'model' 		=> 'user',
											'foreign_key' 	=> 'user_id',
										),
						);
	}

Dans cet exemple on doit aussi avoir un champ `user_id` dans la table 'posts'.



Dans les relations has_many le champ `far_key` est le champ de la table de jointure qui le lie à la table étrangère et la clé étrangère est le champ de la table de jointure qui lie la table du modèle courant ("this") avec la table de jointure.

Considérez la configuration suivante où les "Posts" appartiennent à plusieurs "Categories" via `posts_sections`.

| categories | posts_sections 	| posts   |
|------------|------------------|---------|
| id		 | section_id		| id	  |
| name		 | post_id			| title   |
|			 | 					| content |

		Class Model_Post extends ORM
		{
			protected $_has_many = 	array(
										'sections' =>	array(
															'model' 	=> 'category',
															'through'	=> 'posts_sections',
															'far_key'	=> 'section_id',
														),
									);
		}
		
		Class Model_Category extends ORM
		{
			protected $_has_many = 	array (
										'posts'		=>	array(
															'model'			=> 'post',
															'through'		=> 'posts_sections',
															'foreign_key'	=> 'section_id',
														),
									);
		}


Bien sûr l'exemple d'aliasing présenté ci-dessus est un peu exagéré, mais c'est un bon exemple de fonctionnement des clés foreign/far.

### Itérateur ORM

Il est important aussi de noter que `ORM_Iterator` a été renommé en `Database_Result`.

Si vous avez besoin de récupérer un tableau d'objets ORM dont la clé est la clé étrangère de l'objet, vous devez utiliser [Database_Result::as_array], e.g.

		$objects = ORM::factory('user')->find_all()->as_array('id');

où `id` est la clé primaire de la table user.

## Librairie Router

En version 2.x il existe une librairie Router qui se charge du traitement des requêtes. Cela permet de définir des routes basiques dans le fichier`config/routes.php` et d'utiliser des expressions régulières mais au détriment de la flexibilité.

## Routes

Le sytème de routage est plus flexible en 3.0.  Les routes sont maintenant définies dans le fichier bootstrap (`application/bootstrap.php`) et dans le cas des modules dans init.php (`modules/module_name/init.php`). Les routes sont évaluées dans l'ordre dans lequel elles sont définies.

Aulieu de définir un tableau de routes, désormais on crée un objet [Route] pour chacunes des routes. Contraitement à la version 2.x, il n'est pas nécessaire d'associer une URI à une autre. Au lieu de ça, il faut spécifier un pattern pour une URI en utilisation des variables pour marquer les segments (i.e. controller, method, id).

Par exemple, en 2.x on créé une route sous forme d'expression régulière comme suit:

	$config['([a-z]+)/?(\d+)/?([a-z]*)'] = '$1/$3/$1';

Cette route associe l'URI `controller/id/method` à `controller/method/id`.  

En 3.0 on utilise:

	Route::set('reversed','(<controller>(/<id>(/<action>)))')
			->defaults(array('controller' => 'posts', 'action' => 'index'));

[!!] Chaque URI doit avoir un nom unique (dans l'exemple ci-dessus c'est `reversed`). La raison de ce choix est expliquée dans le [tutorial sur les URLs](tutorials.urls).

Les chevrons sont utilisés pour définir des sections dynamiques qui doivent être transformées en variables. Les parenthèses dénotent une section optionnelle. Si vous ne souhaitez matcher que les URIs commençant par admin, vous pouvez utiliser:

	Rouse::set('admin', 'admin(/<controller>(/<id>(/<action>)))');

Et si vous voulez forcer l'utilisateur à spécifier un controleur:

	Route::set('admin', 'admin/<controller>(/<id>(/<action>))');
	
De plus Kohana 3.0 ne définit pas de routes par défaut. Si votre action (méthode) par défaut est 'index', alors vous devez le spécifier comme tel. Cela se fait via la méthode [Route::defaults]. Si vous voulez utiliser des expressions régulières pour des segments de votre URI alors il suffit de passer un tableau associatif `segment => regex` i.e.:

	Route::set('reversed', '(<controller>(/<id>(/<action>)))', array('id' => '[a-z_]+'))
			->defaults(array('controller' => 'posts', 'action' => 'index'))

Cette route force la valeur de `id` à être en minuscule et composée uniquement de caractères alphabétiques et du caractère underscore.

### Actions

Une dernière chose importante à noter est que toute méthode accessible d'un controleur (càd via l'URI) sont appelées "actions", et sont préfixées de 'action_'. Dans l'exemple ci-dessus, `admin/posts/1/edit` appelle l'action `edit` mais la méthode rééllement apelée dans le controleur est `action_edit`.  Pour plus d'informations voir [le tutorial sur les URLs](tutorials.urls).

## Sessions

Les méthodes Session::set_flash(), Session::keep_flash() et Session::expire_flash() n'existent plus. A la place la méthode [Session::get_once] peut être utilisée.

## Helper URL

Seules des modifications mineures ont été apportées sur l'helper URL. `url::redirect()` est désormais fait via `$this->request->redirect()` dans les controleurs et via `Request::instance()->redirect()` ailleurs.

`url::current` a été remplacé par `$this->request->uri()`.

## Validation

La syntaxe a subit quelque modifications. Pour valider un tableau il faut maintenant faire:

	$validate = new Validate($_POST);
	
	// Apply a filter to all items in the arrays
	$validate->filter(TRUE, 'trim');
	
	// To specify rules individually use rule()
	$validate
		->rule('field', 'not_empty')
		->rule('field', 'matches', array('another_field'));
	
	// To set multiple rules for a field use rules(), passing an array of rules => params as the second argument
	$validate->rules('field', 	array(
									'not_empty' => NULL,
									'matches'	=> array('another_field')
								));

La règle 'required' a été renommée en 'not_empty' pour plus de clarté.

## Librairie View

En 2.x, les vues sont rendues dans la portée d'un controleur, vous permettant ainsi d'utiliser `$this` dans la vue comme référence vers le controleur. 
En 3.0 les vues sont rendues sans aucune portée. Si vous souhaitez utiliser `$this` dans vos vues alors vous devez l'affecter par référence avec [View::bind]: 

	$view->bind('this', $this)

Néanmoins c'est une mauvaise pratique car cela couple votre vue avec le controleur limitant ainsi la réutilisation du code. Il est vivement recommandé de ne passer que les variables requises par la vue:

	$view = View::factory('my/view');
	
	$view->variable = $this->property;
	
	// ou par chainage
	
	$view
		->set('variable', $this->property)
		->set('another_variable', 42);
		
	// NON Recommandé
	$view->bind('this', $this);

Etant donné qu'une vue n'a pas de portée, la méthode `Controller::_kohana_load_view` est redondante.  Si vous avez besoin de modifier la vue avant qu'elle ne soit rendue (par exemple pour ajouter un menu global à toutes vos pages) vous pouvez utiliser [Controller::after].

	Class Controller_Hello extends Controller_Template
	{
		function after()
		{
			$this->template->menu = '...';
			
			return parent::after();
		}
	}
