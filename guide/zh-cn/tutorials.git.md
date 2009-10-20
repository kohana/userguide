# 使用 Git 开发

Kohana 使用 [git](http://git-scm.com/) 作为版本控制并托管在 [github](http://github.com/kohana) 网站上面。本教程将会讲解如何让你使用 Git 并从 github 上部署一个简单的应用。

## Initial Structure

[!!] 开始本教程前务必保证开发环境以及设置完毕，接下来我们要做一个可以通过 <http://localhost/gitorial/> 访问的新应用。

打开你的控制台（译者：Windows 平台为命令提示符，*nux 平台为终端），创建并切换到 `gitorial` 空目录下面（译者：此目录即为新应用的目录），执行 `git init`。这是为当前目录创建一个新的 git 空版本库。

下一步，我们为 `system` 目录要创建一个 [submodule](http://www.kernel.org/pub/software/scm/git/docs/git-submodule.html)（子模块）。访问 <http://github.com/kohana/core> 页面并复制克隆（Clone） URL：

![Github Clone URL](http://img.skitch.com/20091019-rud5mmqbf776jwua6hx9nm1n.png)

现在使用复制后的 URL 去创建 `system` 子模块：

~~~
git submodule add git://github.com/kohana/core.git system
~~~

[!!] 上面的链接是 Kohana 为下一个稳定版本准备的当前的开发版本。开发版本几乎是可以拿来做开发的，他拥有当前稳定版本同样的 API 和一些补丁修复。

现在，我们准备添加自己开发所需的子模块。比如你可能需要使用 [Database](http://github.com/kohana/database) 模块：

~~~
git submodule add git://github.com/kohana/database.git modules/database
~~~

添加子模块之后，我们必须让其初始化：

~~~
git submodule init
~~~

子模块我们已经添加完毕，接着我们去提交当前版本：

~~~
git commit -m 'Added initial submodules'
~~~

下一步，创建应用文件结构。下面的是最低要求：

~~~
mkdir -p application/classes/{controller,model}
mkdir -p application/{config,views}
mkdir -m 0777 -p application/{cache,logs}
~~~

如果你执行 `find application` 你应该会看到：

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

如果我们不想让 git 去追踪日志（log）或者缓存（Cache）文件，我们需要为每个目录添加一个 `.gitignore` 文件。它会忽略所有的非隐藏文件：

~~~
echo '[^.]*' > application/{logs,cache}/.gitignore
~~~

[!!] Git 会忽略空目录，所有我们添加 `.gitignore` 文件以保证 git 会追踪其目录，但是不会追踪目录下面的文件。

现在我们还缺 `index.php` 和 `bootstrap.php` 文件：

~~~
wget http://github.com/kohana/kohana/raw/master/index.php
wget http://github.com/kohana/kohana/raw/master/application/bootstrap.php -O application/bootstrap.php
~~~

再次提交：

~~~
git add application
git commit -m 'Added initial directory structure'
~~~

所有的工作都完成了！你现在可以使用 Git 作为版本控制开发 Kohana 应用了。或许你可能也需要更新你的子模块。比如，更新 `system`：

~~~
cd system
git checkout master
git pull
cd ..
git add system
git commit -m 'Updated system directory'
~~~

