## A brief explaination of how the userguide works:

The userguide uses [Markdown](http://daringfireball.net/projects/markdown/) and [Markdown Extra](http://michelf.com/projects/php-markdown/extra/) for the documentation.  Here is a short intro to [Markdown syntax](http://kohanut.com/docs/using.markdown), as well as the [complete guide](http://daringfireball.net/projects/markdown/syntax), and the things [Markdown Extra adds](http://michelf.com/projects/php-markdown/extra/).  ((The userguide also adds some things we need to mention.))

### Userguide pages

Userguide pages are in the module they apply to, in `guide/<module>`. Documentation for Kohana is in `system/guide/kohana` and documentation for orm is in `modules/orm/guide/orm`, etc.

Each module has an index page at `guide/<module>/index.md`.

Each module's menu is in `guide/<module>/menu.md`.  If you feel a menu need to be changed or a module needs new pages, please open a [bug report](http://dev.kohanaframework.org/projects/userguide3/issues/new) to discuss it.

### Images

Any images used in the userguide pages must be in `media/guide/<module>/`.  For example, if a userguide page has `![Image Title](hello-world.jpg)` the image would be located at `media/guide/<module>/hello-world.jpg`.  Images for the ORM module are in `modules/orm/media/guide/orm`, and images for the Kohana docs are in `system/media/guide/kohana`.

### API browser

The API browser is generated from the actual source code.  The descriptions for classes, constants, properties, and methods is extracted from the comments and parsed in Markdown.  For example if you look in the comment for [Kohana_Core::init](http://github.com/kohana/core/blob/c443c44922ef13421f4a/classes/kohana/core.php#L5) you can see a markdown list and table.  These are parsed and show correctly in the API browser.  `@param`, `@uses`, `@throws`, `@returns` and other tags are parsed as well.

## How to Contribute

### If you don't know git, or you don't feel like you are a good documentation writer:

Just submit a [bug report](http://dev.kohanaframework.org/projects/userguide3/issues/new) and explain what you think can be improved.  If you are a good writer but don't know git, just provide some content in your bug report and we will merge it in.

### If you know git:

**Bluehawk's forks all have a `docs` branch.  Please do all work in that branch.**  As a side note, the "docs" branch of <github.com/bluehawk/kohana> contains git submodule links to all the other "docs" branches.  The main Kohana docs are in <github.cm/bluehawk/core>. 

**Short version**: Fork bluehawk's fork of the module whose docs you wish to improve (e.g. `git://github.com/bluehawk/orm.git` or `git://github.com/bluehawk/core.git`), checkout the `docs` branch, make changes, and then send bluehawk a pull request.

**Long version:**  (This still assumes you at least know your way around git, especially how submodules work.)

 1. Fork the specific repo you want to contribute to on github. (For example go to http://github.com/bluehawk/core and click the fork button.)

 1. To make pulling the new userguide changes easier, I have created a branch of `kohana` called `docs` which contains git submodules of all the other doc branchs.  You can either manually add my remotes to your existing kohana repo, or create a new kohana install from mine by doing these commands:
	
		git clone git://github.com/bluehawk/kohana
		
		# Get the docs branch
		git checkout origin/docs
		
		# Fetch the system folder and all the modules
		git submodule init
		git submodule update

 1. Now go into the repo of the area of docs you want to contribute to and add your forked repo as a new remote, and push to it.
 
		cd system
		
		# make sure we are up to date with the docs branch
		git merge origin/docs
		(if this fails or you can't commit later type "git checkout -b docs" to create a local docs branch)
		
		# add your repository as a new remote
		git remote add <your name> git@github.com:<your name>/core.git
		
		# (make some changes to the docs)
		
		# now commit the changes and push to your repo
		git commit
		git push <your name> docs

 1. Send a pull request on github.
