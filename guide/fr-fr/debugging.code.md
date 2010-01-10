# Debugging du code

Kohana fournit plusieurs outils puissants pour debugger vos applications.

Le plus basique d'entre eux est [Kohana::debug]. Cette méthode permet d'afficher toutes variables à la manière de [var_export] ou [print_r], mais en utilisant HTML pour ajouter du formatage supplémentaire.

~~~
// Affiche le contenu (dump) des variables $foo et $bar
echo Kohana::debug($foo, $bar);
~~~

Kohana fournit aussi une méthode pour afficher le code source d'un fichier en particulier en appelant [Kohana::debug_source].

~~~
// Affiche cette ligne de code source
echo Kohana::debug_source(__FILE__, __LINE__);
~~~

Enfin si vous voulez afficher des informations sur les chemins de votre application sans afficher/exposer le chemin d'installation vous pouvez utiliser [Kohana::debug_path]:

~~~
// Affiche "APPPATH/cache" plutot que le chemin réél
echo Kohana::debug_file(APPPATH.'cache');
~~~
