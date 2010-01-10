# Conventions et style de codage

## Nom de classe et emplacement des fichiers

Les noms de classe dans Kohana suivent des règles strictes pour faciliter l'[auto-chargement de classes](about.autoloading).

Ils doivent avoir la première lettre en majuscule, et les mots doivent être séparés par des underscores. Les underscores sont très importants car ils déterminent le chemin d'accès au fichier.

Nom de classe         | Chemin
----------------------|-------------------------------
Controller_Template   | classes/controller/template.php
Model_User            | classes/model/user.php
Database              | classes/database.php
Database_Query        | classes/database/query.php

Les noms de classe ne doivent pas utiliser de syntaxe CamelCase sauf si vous ne souhaitez pas créer un nouveau niveau de répertoire.

Tous les noms de fichier et répertoire sont en minuscule.

Toutes les classes doivent être dans le répertoire `classes`. Elles peuvent néanmoins être sur plusieurs niveaux de répertoire de l'[arborescence](about.filesystem).

Kohana 3 ne différencie pas les *types* de classe comme le fait Kohana 2.x. Il n'y a pas de distinction entre une classe 'helper' ou une 'librairie' – avec Kohana 3 toute classe peut implémenter l'interface que vous souhaitez, qu'elle soit statique (helper), instanciable, ou mixte (e.g. singleton).

## Style de codage

Il est vivement conseillé de suivre les [styles de codage](http://dev.kohanaphp.com/wiki/kohana2/CodingStyle) de Kohana c'est-à-dire le [style BSD/Allman](http://en.wikipedia.org/wiki/Indent_style#BSD.2FAllman_style) pour les accolades, entre autres choses.