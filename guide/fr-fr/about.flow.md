# Processus de traitement des requêtes

Toutes les applications suivent le même processus:

1. L'application commence depuis `index.php`
2. Inclut `APPPATH/bootstrap.php`
3. L'initialisation (bootstrap) appelle [Kohana::modules] avec une liste de modules à utiliser
    1. Génére un tableau de chemins utilisés par l'arborescence en cascade
    2. Vérifie la présence du fichier init.php dans chaque module. Si il existe
	    * Chaque fichier init.php peut définir un ensemble de routes à utiliser, elles sont chargées lorsque le fichier init.php est inclut
4. [Request::instance] est appelé pour traiter la requête
    1. Vérifie toutes les routes jusqu'à ce que l'une d'entres elles concorde
    2. Charge le controleur et lui transmet la requête
    3. Appelle la méthode [Controller::before]
    4. Appelle l'action du controleur
    5. Appelle la méthode [Controller::after]
5. Affiche la réponse à la requête

L'action du controleur peut etre changée suivant ses paramètres de la par [Controller::before].

[!!] Stub
