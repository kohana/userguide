# Auto-chargement de classes

Kohana tire partie de la fonctionnalité PHP d'[auto-chargement de classes](http://php.net/manual/fr/language.oop5.autoload.php) permettant de s'affranchir des inclusions manuelles avec [include](http://de.php.net/manual/fr/function.include.php) ou [require](http://de.php.net/manual/fr/function.require.php).

Les classes sont chargées via la méthode [Kohana::auto_load], qui à partir du nom d'une classe, retrouve le fichier associé:

1. Les classes sont placées dans le répertoire `classes/` de l'[arborescence de fichiers](about.filesystem)
2. Les caractères underscore '_' sont convertis en slashes '/'
2. Les noms de fichier doivent être en minuscule

Lors de l'appel à une classe non chargée (eg: `Session_Cookie`), Kohana recherchera dans son arboresence via la méthode [Kohana::find_file] le fichier `classes/session/cookie.php`.

## Auto-chargement tiers

[!!] Le mécanisme par défaut d'auto-chargement de classes est défini dans le fichier `application/bootstrap.php`.

Des mécanismes d'auto-chargement supplémentaires peuvent être ajoutés en utilisant [spl_autoload_register](http://php.net/spl_autoload_register).