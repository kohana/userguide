# Gestion des Erreurs/Exceptions

Kohana fournit des mécanismes de gestion des exceptions et d'erreurs qui transforment les erreurs en exceptions en utilisant les classes PHP prévues à cet effet [ErrorException](http://php.net/errorexception). De nombreux détails sur l'application ainsi que son état sont affichées :

1. Classe de l'Exception
2. Niveau de l'erreur
3. Message de l'erreur
4. Source de l'erreur, avec la ligne contenant l'erreur surlignée
5. Une [trace de debug](http://php.net/debug_backtrace) du processus d'exécution
6. Les fichiers inclus, les extensions chargées et les variables globales

## Exemple

Cliquez sur l'un des liens ci-dessous pour afficher/masquer des informations additionnelles:

<div>{{userguide/examples/error}}</div>

## Désactiver le support des Exceptions

Si vous ne voulez pas utiliser le mécanisme interne de gestion des exceptions et des erreurs, vous pouvez le désactiver via [Kohana::init]:

~~~
Kohana::init(array('errors' => FALSE));
~~~