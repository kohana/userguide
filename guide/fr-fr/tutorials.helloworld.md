# Hello, World

Tout framework digne de ce nom possède un exemple d'application "Hello World", alors ne dérogeons pas à la régle!

On commencera donc par décrire un "hello word" très très basique puis on détaillera les principes MVC appliqués à l'exemple.

## Au commencement il n'y avait rien...

La première chose à faire est de créer un controleur de telle sorte que Kohana puisse traiter une requête.

Créér le fichier `application/classes/controller/hello.php` dans votre répertoire application et ajoutez-y le code suivant:

    <?php defined('SYSPATH') OR die('No Direct Script Access');

	Class Controller_Hello extends Controller
	{
		function action_index()
		{
			echo 'hello, world!';
		}
	}

Voyons ce que signifient ces quelques lignes:

`<?php defined('SYSPATH') OR die('No Direct Script Access');`
:	Vous devez sûrement reconnâitre le tag d'ouverture php (si ce n'est pas le cas alors il vous faut d'abord probablement vous [familiariser avec php](http://php.net)).  Ce qui suit est un test permettant de s'assurer que le fichier est bien inclus par Kohana et lui seul. Cela permet d'interdire tout accès au fichier directement depuis une URL.

`Class Controller_Hello extends Controller`
:	Cette ligne déclare notre controleur, chaque controleur doit être préfixé de `Controller_` et exprime un chemin vers le fichier ci-dessus où les répertoires sont séparés par des underscores (voir [Conventions et styles](about.conventions) pour plus d'informations). Chaque contrôleur doit hériter du controleur de base `Controller` qui fournit la structure standard de tout controleur.

`function action_index()`
:	Cette ligne définit l'action "index" de notre controleur.  Kohana essaiera d'appeler cette méthode si l'utilisateur n'en a spécifié aucune. (Voir [Routes, URLs et Liens](tutorials.urls))

`echo 'hello, world!';`
:	Enfin cette dernière ligne magique affichera sous vos yeux ébahis le message souhaité!

Une fois le controleur créé, ouvrez voter navigateur préféré et rendez-vous à l'adresse `http://loaclhost/kohana/index.php/hello` et constatez le résultat:

![Hello, World!](img/hello_world_1.png "Hello, World!")

## C'était pas mal, mais on peut faire mieux

Le chapitre précédent présente à quel point il est facile de créer une application extrêmement basique avec Kohana. Jusque-là tout va bien.

Si vous avez déjà entendu parler du concept MVC alors vous vous disez sans doute qu'afficher du contenu dans un controleur va à l'encontre du principe MVC.

La manière appropriée de coder avec un framework MVC est d'utiliser des _vues_ pour tout ce qui est lié à la présentation/forme de votre application et de laisser au controleur l'enchainement logique du traitement des requêtes.

Changeons donc le controleur:

    <?php defined('SYSPATH') OR die('No Direct Script Access');

	Class Controller_Hello extends Controller_Template
	{
		public $template = 'site';

		function action_index()
		{
			$this->template->message = 'hello, world!';
		}
	}

`extends Controller_Template`
:	nous héritons désormais du controleur template qui rend plus facile l'utilisation de vues au sein d'un controleur.

`public $template = 'site';`
:	le controleur template doit connaitre le template que vous souhaitez utiliser. Il chargera alors automatiquement la vue en question et lui assignera l'objet Vue créé.

`$this->template->message = 'hello, world!';`
:	`$this->template` est une référence vers l'objet Vue du template de notre site. Ce que l'on fait ici est assigner à la vue la variable "message" dont la valeur est "hello, world!".

Maintenant actualisez votre navigateur...

<div>{{userguide/examples/hello_world_error}}</div>

Kohana vous affiche une erreur au lieu du message fascinant qu'il devrait afficher. En regardant de plus près le message d'erreur on peut voir que la librairie View n'a pas été capable de trouver notre template, probablement parceque nous ne l'avons pas encore créé!

Créons donc notre vue en créant le fichier `application/views/site.php` avec le texte suivant:

	<html>
		<head>
			<title>We've got a message for you!</title>
			<style type="text/css">
				body {font-family: Georgia;}
				h1 {font-style: italic;}

			</style>
		</head>
		<body>
			<h1><?php echo $message; ?></h1>
			<p>We just wanted to say it! :)</p>
		</body>
	</html>

Maintenant si vous ré-actualisez, vous devriez voir apparaitre ce qu'il faut:

![hello, world! We just wanted to say it!](img/hello_world_2.png "hello, world! We just wanted to say it!")

## A moi la gloire et l'argent!

Dans ce tutorial on a abordé comment créer un controleur et utiliser une vue pour séparer la logique de la présentation.

Evidemment l'exemple choisi est une introduction basique à Kohana et n'effleure même pas les possibilités infinies de Kohana ;).