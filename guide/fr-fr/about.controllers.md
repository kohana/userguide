#Controleurs

Dans une application MVC, les Controleurs se placent entre les Modèles et les Vues. Ils passent l'information aux modèles lorsque les données nécessitent des traitements et ils demandent l'information nécessaire aux modèles. Les Controleurs transmettent les informations du Modèle aux Vues qui ciontiennent le code à afficher aux utilisateurs.

Les controleurs sont appelés par rapport à l'URL appelée, pour plus d'informations consulter la documentation sur [les URLs et les Liens](about.urls).

## Nommage des controleurs et fonctionnement

Le nom d'un controleur doit correspondre exactement au nom de fichier.

**Conventions d'écriture**

* les nom de fichiers des controleurs doivent être en minuscule, e.g. `articles.php`
* ils doivent être situés dans le (sous-)dossier **classes/controller**, e.g. `classes/controller/articles.php`
* la classe du controleur doit correspondre au fichier, commencer par une majuscule et être préfixée par **Controller_**, e.g. `Controller_Articles`
* elle doit hérité de la classe Controller
* les méthodes du controleur doivent être précédées de **action_** (e.g. `action_do_something()` ) pour pouvoir être appelées par rapport à l'URL



### Un controleur simple

Ci-dessous un exemple de controleur qui affiche Hello World à l'écran.

**application/classes/controller/article.php**
~~~
<?php defined('SYSPATH') OR die('No direct access allowed.');
 
class Controller_Article extends Controller
{
    public function action_index()
    {
        echo 'Hello World!';
    }
}
~~~
Si vous entrez alors l'URL yoursite.com/article dans votre navigateur (ou yoursite.com/index.php/article sans URL rewritting) vous devriez voir apparaître:
~~~
Hello World
~~~
C'est tout pour votre premier controleur. Toutes les conventions ont été appliquées.



### Un controleur plus avancé

Dans l'exemple ci-dessus la méthode `index()` est appelée par l'URL yoursite.com/article. Si le second segment de l'URL est vide, la méthode index est appelée par défaut. Elle pourrait aussi être appelée en entrant l'URL yoursite.com/article/index.

_Si le second segment de l'URL n'est pas vide, il détermine la méthode dub controleur à appeler._

**application/classes/controller/article.php**
~~~
class Controller_Article extends Controller
{
    public function action_index()
    {
        echo 'Hello World!';
    }
 
    public function action_overview()
    {
        echo 'Article list goes here!';
    }
}
~~~
Maintenant, si vous entrez l'URL yoursite.com/article/overview vous devriez voir apparaître:
~~~
Article list goes here!
~~~


### Un controleur avec des arguments

Imaginons que l'on souhaite afficher un article particulier, identifié par l'id `1` et le titre `your-article-title`.

L'URL ressemblerait alors à yoursite.com/article/view/**your-article-title/1**. Les 2 derniers segments sont passées à la méthode view() du controleur.

**application/classes/controller/article.php**
~~~
class Controller_Article extends Controller
{
    public function action_index()
    {
        echo 'Hello World!';
    }
 
    public function action_overview()
    {
        echo 'Article list goes here!';
    }
 
    public function action_view($title, $id)
    {
        echo $id . ' - ' . $title;
        // you'd retrieve the article from the database here normally
    }
}
~~~
Si vous appelez yoursite.com/article/view/**your-article-title/1** vous devriez voir apparaître:
~~~
1 - your-article-title
~~~
