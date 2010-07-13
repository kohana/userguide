# Werken met Git

Kohana gebruikt [git](http://git-scm.com/) als versie controle systeem en [github](http://github.com/kohana) voor community-bijdragen. Deze tutorial zal je tonen hoe je beide platformen kunt gebruiken om een applicatie op te zetten.

## Het installeren en instellen van Git op uw computer

### Installeren van Git

- OSX: [Git-OSX](http://code.google.com/p/git-osx-installer/)
- Windows: [Msygit](http://code.google.com/p/msysgit/)
- Of download het van de [git-site](http://git-scm.com/) en installeer het manueel (zie de git website)

### Basis globale instellingen

    git config --global user.name "Uw Naam"
    git config --global user.email "uwemail@website.com"

### Extra, maar aan te raden instellingen

Om een beter visueel overzicht te hebben van de git commando's en repositories in je console stel je best volgende in:

    git config --global color.diff auto
    git config --global color.status auto
    git config --global color.branch auto

### Automatische aanvulling installeren

[!!] Deze lijnen code zijn enkel van toepassing voor OSX

Deze lijnen code doen al het vuile werk voor je zodat automatische aanvulling kan werken voor uw git-omgeving

    cd /tmp
    git clone git://git.kernel.org/pub/scm/git/git.git
    cd git
    git checkout v`git --version | awk '{print $3}'`
    cp contrib/completion/git-completion.bash ~/.git-completion.bash
    cd ~
    rm -rf /tmp/git
    echo -e "source ~/.git-completion.bash" >> .profile
	
### Gebruik altijd LF als regeleinden

Dit is de conventie die we maken met Kohana. Stel deze instellingen voor uw eigen goed en vooral als je wilt bijdragen aan de Kohana community.

    git config --global core.autocrlf input
    git config --global core.savecrlf true

[!!] Meer informatie over regeleinden kan je vinden op [github](http://help.github.com/dealing-with-lineendings/)

### Meer informatie op je op weg te zetten

- [Git Screencasts](http://www.gitcasts.com/)
- [Git Reference](http://gitref.org/)
- [Pro Git book](http://progit.org/book/)

## Initiële structuur

[!!] Deze tutorial zal ervan uitgaan dat uw webserver al is ingesteld, en dat je een nieuwe applicatie zal maken op <http://localhost/gitorial/>.

Met behulp van je console, ga naar de lege map `gitorial` en voer `git init` uit. Dit zal een ruwe structuur voor een nieuwe git repository aanmaken.

Vervolgend zullen we een [submodule](http://www.kernel.org/pub/software/scm/git/docs/git-submodule.html) maken voor de `system` folder. Ga naar <http://github.com/kohana/core> en kopieer de "Clone URL":

![Github Clone URL](http://img.skitch.com/20091019-rud5mmqbf776jwua6hx9nm1n.png)

Gebruik nu de URL om de submodule aan te maken voor `system`:

    git submodule add git://github.com/kohana/core.git system

[!!] Dit creëert een link naar de huidige ontwikkelingsversie voor de volgende stabiele uitgave. De ontwikkelingsversie is meestal veilig om te gebruiken, het heeft dezelfde API als de huidige stabiele download maar met bugfixes al toegepast.

Voeg nu elke submodule toe dat je wil. Bijvoorbeeld als je de [Database] module nodig hebt:

    git submodule add git://github.com/kohana/database.git modules/database

Nadat de submodules zijn toegevoegd, moet je ze nog initialiseren:

    git submodule init

Nu dat de submodules zijn toegevoegd en geinitialiseerd, kan je ze commit'en:

    git commit -m 'Added initial submodules'

Vervolgens creëren we de applicatie folder structuur. Hier is een absoluut minimum vereist:

    mkdir -p application/classes/{controller,model}
    mkdir -p application/{config,views}
    mkdir -m 0777 -p application/{cache,logs}

Als je nu `find application` uitvoert, moet je dit zien:

    application
    application/cache
    application/config
    application/classes
    application/classes/controller
    application/classes/model
    application/logs
    application/views

We willen niet dat git de log of cache bestanden volgt dus voegen we een `.gitignore` bestand toe aan deze folders. Dit zal alle niet-verborgen bestanden negeren:

    echo '[^.]*' > application/{logs,cache}/.gitignore

[!!] Git negeert lege folders, dus het toevoegen van een `.gitignore` bestand zorgt er voor dat git de folder volgt maar niet de bestanden er in.

Nu hebben we nog de `index.php` en `bootstrap.php` bestanden nodig:

    wget http://github.com/kohana/kohana/raw/master/index.php
    wget http://github.com/kohana/kohana/raw/master/application/bootstrap.php -O application/bootstrap.php

Commit deze veranderingen ook:

    git add application
    git commit -m 'Added initial directory structure'

Dit is alles wat je nodig hebt. Je hebt nu een applicatie dat Git gebruikt als versiesysteem.

## Updaten van Submodules

Op een gegeven moment zal je waarschijnlijk ook je submodules willen upgraden. Om al je submodules te updaten naar de laatste "HEAD" versie:

    git submodule foreach 'git checkout master && git pull origin master'

Om een enkele submodule te update, bijvoorbeel `system`:

    cd system
    git checkout master
    git pull origin master
    cd ..
    git add system
    git commit -m 'Updated system to latest version'

Als je een enkele submodule wilt updaten naar een specifieke commit:

    cd modules/database
    git pull origin master
    git checkout fbfdea919028b951c23c3d99d2bc1f5bbeda0c0b
    cd ../..
    git add database
    git commit -m 'Updated database module'

Merk op dat je ook een commit kunt uitchecken via een tag, zoals een officieel versie, bijvoorbeeld:

    git checkout 3.0.6

Voer gewoon `git tag` uit zonder parameters om een lijst van alle tags te krijgen.

U weet nu "alles" over git!
