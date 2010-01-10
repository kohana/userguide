# Configuration Générale

[!!] todo, description of benefits of static properties for configuration

## Configuration du noyau

La toute première configuration à modifier lors d'une installation de kohana est de changer les paramètres d'initlalisation [Kohana::init] dans le fichier `application/bootstrap.php`. Ces paramètres sont:

`boolean` errors
:   Utilisation de la gestion des erreurs et des exceptions? (Défaut `TRUE`) Affecter à `FALSE` pour désactiver
    la gestion des erreurs et exceptions.

`boolean` profile
:   Activer le  benchmarking interne? (Défault `TRUE`) Affecter à `FALSE` pour désactiver le benchmarking interne.
    A desactiver en production pour obtenir de meilleures performances.

`boolean` caching
:   Mettre en cache les chemins des fichiers entre les requêtes? (Défault `FALSE`)  Affecter à `TRUE` pour mettre en cache
    les chemins absolus. Ceci peut améliorer drastiquement les performances de la méthode [Kohana::find_file].

`string` charset
:   Jeu de caractères à utiliser pour toutes les entrées et sorties. (Défault `"utf-8"`) Affecter un jeu de caractères supporté aussi bien par [htmlspecialchars](http://fr.php.net/htmlspecialchars) que [iconv](http://fr.php.net/iconv).

`string` base_url
:   URL racine de l'application. (Défault `"/"`) Peut être une URL complète ou partielle. Par exemple "http://example.com/kohana/" ou "/kohana/" fonctionneraient.

`string` index_file
:   Le fichier PHP qui démarre l'application. (Défault `"index.php"`) Affecter à `FALSE` pour enlever le fichier index de l'URL en utilisant l'URL Rewriting.

`string` cache_dir
:   Répertoire de stockage du cache. (Défault `"application/cache"`) Doit pointer vers un répertoire **inscriptible**.

## Paramètres des Cookies

Il y a plusieurs propriétés statiques dans la classe [Cookie] qui doivent être paramétrées, particuliérement sur les sites en production.

`string` salt
:   La chaîne d'aléa (salt) unique utilisée pour [signer les cookies](security.cookies)

`integer` expiration
:   La durée d'expiration par défaut 

`string` path
:   Restreindre l'accès aux cookies par rapport au chemin spécifié

`string` domain
:   Restreindre l'accès aux cookies par rapport au domaine spécifié

`boolean` secure
:   N'autoriser les cookies qu'en HTTPS

`boolean` httponly
:   N'autorise l'accès aux cookies que via HTTP (désactive aussi l'accès javascript)

# Fichiers de configuration

La configuration de Kohana est faite dans des fichiers à plat PHP, qui ressemblent à l'exemple ci-dessous:

~~~
<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'setting' => 'value',
    'options' => array(
        'foo' => 'bar',
    ),
);
~~~

Supposons que le fichier ci-dessus soit appelé `myconf.php`, il est alors possible d'y accèder de la manière suivante:

~~~
$config = Kohana::config('myconf');
$options = $config['options'];
~~~

[Kohana::config] fournit aussi un raccourci pour accèder à des clés spécifiques des tableaux de configuration en utilisant des chemins spérarés par le caractère point.

Récupérer le tableau "options":

~~~
$options = Kohana::config('myconf.options');
~~~

Récupérer la valeur de la clé "foo" du tableau "options":

~~~
$foo = Kohana::config('myconf.options.foo');
~~~

Les tableaux de configuration peuvent aussi être parcourus comme des objets comme suit:

~~~
$options = Kohana::config('myconf')->options;
~~~
