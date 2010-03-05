# Installation

1. Téléchargez la dernière version **stable** depuis [le site web Kohana](http://kohanaphp.com/)
2. Dézippez l'archive téléchargée pour créer le répertoire `kohana`
3. Uploadez le contenu de ce répertoire sur votre serveur web
4. Ouvrez `application/bootstrap.php` et effectuez les changements suivants:
	- Affecter la [timezone](http://php.net/timezones) par défaut de votre application
	- Affecter `base_url` dans l'appel à [Kohana::init] afin de faire comprendre à votre serveur ou est situé le répertoire kohana uploadé à l'étape précédente
6. Vérifiez que les répertoires `application/cache` et `application/logs` sont inscriptibles en tapant la commande `chmod application/{cache,logs} 0777` (Linux).
7. Testez votre installation en tapant l'URL que vous avez spécifiée dans `base_url` dans votre navigateur préféré

[!!] Suivant votre plateforme, l'extraction de l'archive peut avoir changé les permissions sur les sous répertoires. Rétablissez-les avec la commande suivante: `find . -type d -exec chmod 0755 {} \;` depuis la racine de votre installation Kohana.

Vous devriez alors voir la page d'installation contenant un rapport d'installation. Si une erreur est affichée, vous devez la corriger pour pouvoir continuer.

![Install Page](img/install.png "Example of install page")

Une fois que votre rapport d'installation vous informe que votre environnement est correctement configuré, vous devez soit renommer, soit supprimer le fichier `install.php`. Vous devriez alors voir apparaitre la page de bienvenue de Kohana:

![Welcome Page](img/welcome.png "Example of welcome page")
