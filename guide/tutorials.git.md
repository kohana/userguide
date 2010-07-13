# Working With Git

Kohana uses [git](http://git-scm.com/) for version control and [github](http://github.com/kohana) for collaboration. This tutorial will show you how to use git and github to build a simple application.

## Installing and setting up Git on your machine

### Installing Git

- OSX: [Git-OSX](http://code.google.com/p/git-osx-installer/)
- Windows: [Msygit](http://code.google.com/p/msysgit/)
- Or download it from [git-site](http://git-scm.com/) and install it manually (see git website)

### Basic global settings

    git config --global user.name "Your Name"
    git config --global user.email "youremail@website.com"

### Additional but preferable settings

To have a better visualisation of the git commandos and repositories in your command-line, you can set these:

    git config --global color.diff auto
    git config --global color.status auto
    git config --global color.branch auto

### Setting auto-completion

[!!] These lines are only to use on an OSX machine

These lines will do all the dirty work for you, so auto-completion can work for your git-environment

    cd /tmp
    git clone git://git.kernel.org/pub/scm/git/git.git
    cd git
    git checkout v`git --version | awk '{print $3}'`
    cp contrib/completion/git-completion.bash ~/.git-completion.bash
    cd ~
    rm -rf /tmp/git
    echo -e "source ~/.git-completion.bash" >> .profile
	
### Always use LF line endings

This is the convention that we make for Kohana. Please set this settings for your own good and especially if you want to contribute to the Kohana community.

    git config --global core.autocrlf input
    git config --global core.savecrlf true

[!!] More information about line endings at [github](http://help.github.com/dealing-with-lineendings/)

### More information to get you on the track

- [Git Screencasts](http://www.gitcasts.com/)
- [Git Reference](http://gitref.org/)
- [Pro Git book](http://progit.org/book/)

## Initial Structure

[!!] This tutorial will assume that your web server is already set up, and you are going to create a new application at <http://localhost/gitorial/>.

Using your console, change to the empty directory `gitorial` and run `git init`. This will create the bare structure for a new git repository.

Next, we will create a [submodule](http://www.kernel.org/pub/software/scm/git/docs/git-submodule.html) for the `system` directory. Go to <http://github.com/kohana/core> and copy the "Clone URL":

![Github Clone URL](http://img.skitch.com/20091019-rud5mmqbf776jwua6hx9nm1n.png)

Now use the URL to create the submodule for `system`:

    git submodule add git://github.com/kohana/core.git system

[!!] This will create a link to the current development version of the next stable release. The development version should almost always be safe to use, have the same API as the current stable download with bugfixes applied.

Now add whatever submodules you need. For example if you need the [Database] module:

    git submodule add git://github.com/kohana/database.git modules/database

After submodules are added, they must be initialized:

    git submodule init

Now that the submodules are added, you can commit them:

    git commit -m 'Added initial submodules'

Next, create the application directory structure. This is the bare minimum required:

    mkdir -p application/classes/{controller,model}
    mkdir -p application/{config,views}
    mkdir -m 0777 -p application/{cache,logs}

If you run `find application` you should see this:

    application
    application/cache
    application/config
    application/classes
    application/classes/controller
    application/classes/model
    application/logs
    application/views

We don't want git to track log or cache files, so add a `.gitignore` file to each of the directories. This will ignore all non-hidden files:

    echo '[^.]*' > application/{logs,cache}/.gitignore

[!!] Git ignores empty directories, so adding a `.gitignore` file also makes sure that git will track the directory, but not the files within it.

Now we need the `index.php` and `bootstrap.php` files:

    wget http://github.com/kohana/kohana/raw/master/index.php
    wget http://github.com/kohana/kohana/raw/master/application/bootstrap.php -O application/bootstrap.php

Commit these changes too:

    git add application
    git commit -m 'Added initial directory structure'

That's all there is to it. You now have an application that is using Git for versioning.

## Updating Submodules

At some point you will probably also want to upgrade your submodules. To update all of your submodules to the latest `HEAD` version:

    git submodule foreach 'git checkout master && git pull origin master'

To update a single submodule, for example, `system`:

    cd system
    git checkout master
    git pull origin master
    cd ..
    git add system
    git commit -m 'Updated system to latest version'

If you want to update a single submodule to a specific commit:

    cd modules/database
    git pull origin master
    git checkout fbfdea919028b951c23c3d99d2bc1f5bbeda0c0b
    cd ../..
    git add database
    git commit -m 'Updated database module'

Note that you can also check out the commit at a tagged official release point, for example:

    git checkout 3.0.6

Simply run `git tag` without arguments to get a list of all tags.

All done!
