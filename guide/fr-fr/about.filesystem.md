# Arborescence de fichiers en cascade

L'arborescence de fichiers de Kohana est construite autour d'une structure de répertoires unique qui est dupliquée dans tous les répertoires formant ce que l'on appelle l'"include path". Cette structure est composée des répertoires suivants et dans cet ordre:

1. application
2. modules, dans l'ordre dans lequel ils ont été ajoutés
3. system

Les fichiers qui sont dans les répertoires les plus haut de l'"include path" sont prioritaires par rapport aux fichiers de même noms dans des répertoires plus bas. Cela rend possible la surcharge de nimporte quel fichier en plaçant un fichier de même nom dans un répertoire de niveau supérieur:

![Cascading Filesystem Infographic](img/cascading_filesystem.png)

Par exemple, si vous avez un fichier appelé layout.php dans les répertoires application/views et system/views, alors celui contenu dans le répertoire application sera retourné lors de l'appel à layout.php du fait qu'il est plus haut dans l'"include path". Si vous supprimez le fichier de application/views, alors c'est celui contenu dans system/views qui sera alors retourné.