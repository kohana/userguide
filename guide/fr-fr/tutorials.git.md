# Travailler avec Git

Kohana utilise [git](http://git-scm.com/) comme système de gestion de versions et [github](http://github.com/kohana) pour l'aspect collaboratif. Ce tutorial présente comment utiliser git et github pour mettre en place une application simple.

## Structure initiale

[!!] Ce tutorial prend comme prérequis le fait que votre serveur web est déjà mis en place et que vous être dans l'étape de création d'une nouvelle application située à <http://localhost/gitorial/>.

En utilisant votre console, placez vous dans le répertoire `gitorial` et exécutez `git init`. Cela créera la structure du dépôt git.

Ensuite, nous allons créér un [sous-module](http://www.kernel.org/pub/software/scm/git/docs/git-submodule.html) pour le répertoire `system`. Allez à l'URL <http://github.com/kohana/core> et copiez l'URL de clonage:

![Github Clone URL](http://img.skitch.com/20091019-rud5mmqbf776jwua6hx9nm1n.png)

Maintenant utilisez cette URL pour créér le sous-module `system`:

~~~
git submodule add git://github.com/kohana/core.git system
~~~

[!!] Cela créera un lien vers la version stable en développement. La version stable en développement est sûre à utiliser pour vos environnements de production et possède la même API que la version stable en téléchargement à laquelle sont ajoutés les correctifs de bugs.

A partir de là vous pouvez ajouter les modules que vous souhiatez, par exemple le module [Base de données](http://github.com/kohana/database):

~~~
git submodule add git://github.com/kohana/database.git modules/database
~~~

Une fois les sous-modules ajoutés, vous devez les initialiser:

~~~
git submodule init
~~~

Enfin il faut les commiter:

~~~
git commit -m 'Added initial submodules'
~~~

L'étape suivante consiste en la création de la structure des répertoires de votre application kohana. Le minimum requis est:

~~~
mkdir -p application/classes/{controller,model}
mkdir -p application/{config,views}
mkdir -m 0777 -p application/{cache,logs}
~~~

Si vous lancez la commande linux `find application` vous devez voir:

~~~
application
application/cache
application/config
application/classes
application/classes/controller
application/classes/model
application/logs
application/views
~~~

Puisque l'on ne souhaite pas que les changements sur les logs et les mises en cache soient pris en compte, il faut ajouter un fichier `.gitignore` à chacun de ces répertoires. Cela aura pour effet d'ignorer tous les fichiers non cachés du répertoire:

~~~
echo '[^.]*' > application/{logs,cache}/.gitignore
~~~

[!!] Git ignore les répertoires vides, donc le fait d'ajouter le fichier `.gitignore` vous assure que git prendra en compte le répertoire mais pas les fichiers qu'il contient.

Ensuite il faut récupérer les fichiers `index.php` et `bootstrap.php`:

~~~
wget http://github.com/kohana/kohana/raw/master/index.php
wget http://github.com/kohana/kohana/raw/master/application/bootstrap.php -O application/bootstrap.php
~~~

Commiter tous les changements:

~~~
git add application
git commit -m 'Added initial directory structure'
~~~

C'est tout! Vous avez désormais une application gérée sous Git.

## Mettre à jour les sous-modules

Tôt ou tard vous allez sûrement avoir besoin de mettre à jour vos sous-modules. Pour mettre à jour l'ensemble de vos sous-modules à la version la plus récente `HEAD`, entrez:

~~~
git submodule foreach
~~~

Pour mettre à jour un seul sous-module, par exemple `system`, entrez:

~~~
cd system
git checkout master
git fetch
git merge origin/master
cd ..
git add system
git commit -m 'Updated system to latest version'
~~~

Enfin si vous souhaitez mettre à jour un sous-module par rapport à une révision particulière, entrez:

~~~
cd modules/database
git fetch
git checkout fbfdea919028b951c23c3d99d2bc1f5bbeda0c0b
cd ../..
git add database
git commit -m 'Updated database module'
~~~


