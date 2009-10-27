# Работа с Git

Kohana применяет [git](http://git-scm.com/) для контроля версий и [github](http://github.com/kohana) для совместной разработки. Данная статья покажет Вам как использовать git и github для создания простейшего приложения.

## Подготовка

[!!] Предполагается, что Ваш web-сервер уже настроен, и Вы будете использовать адрес <http://localhost/gitorial/> для нового приложения.

Откройте консоль, перейдите в пустую директорию `gitorial` и выполните команду `git init`. Она создаст заготовку под новый git-репозиторий.

Далее, мы создадим подпроект ([submodule](http://www.kernel.org/pub/software/scm/git/docs/git-submodule.html)) для директории `system`. Откройте <http://github.com/kohana/core> и скопируйте значение Clone URL:

![Github Clone URL](http://img.skitch.com/20091019-rud5mmqbf776jwua6hx9nm1n.png)

Используйте скопированный URL для создания подпроекта `system`:

~~~
git submodule add git://github.com/kohana/core.git system
~~~

[!!] Будет создана связь с текущей разрабатываемой версией следующего стабильного релиза. Эта версия должна практически всегда быть безопасна для использования, иметь тот же API, что в текущем стабильном релизе с исправленными ошибками.

Теперь добавьте остальные необходимые подпроекты. Например, если нужен модуль [Database](http://github.com/kohana/database):

~~~
git submodule add git://github.com/kohana/database.git modules/database
~~~

После добавления модули должны быть проиниализированы:

~~~
git submodule init
~~~

Теперь мы должны зафиксировать текущее состояние:

~~~
git commit -m 'Added initial submodules'
~~~

Следующий шаг - создание структуры папок для приложения. Вот необходимый минимум:

~~~
mkdir -p application/classes/{controller,model}
mkdir -p application/{config,views}
mkdir -m 0777 -p application/{cache,logs}
~~~

Если запустить команду `find application`, Вы должны увидеть такой список:

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

Мы не хотим, чтобы git обрабатывал логи или файлы кэша, поэтому добавим файл `.gitignore` в соответствуюшие директории logs и cache. Теперь все нескрытые (non-hidden) файлы будут проигнорированы git'ом:

~~~
echo '[^.]*' > application/{logs,cache}/.gitignore
~~~

[!!] Git пропускает пустые папки, так что добавляя файл `.gitignore`, мы дополнительно заставляем git учитывать данную директорию, но не файлы внутри нее.

Теперь загружаем файлы `index.php` и `bootstrap.php`:

~~~
wget http://github.com/kohana/kohana/raw/master/index.php
wget http://github.com/kohana/kohana/raw/master/application/bootstrap.php -O application/bootstrap.php
~~~

Фиксируем эти изменения:

~~~
git add application
git commit -m 'Added initial directory structure'
~~~

Это все необходимые изменения. Теперь у Вас имеется приложение, использующее Git для контроля версий. Скорее всего, на определенном этапе Вы захотите обновить свои подпроекты. Например, для синхронизации подпроекта `system` выполните:

~~~
cd system
git checkout master
git pull
cd ..
git add system
git commit -m 'Updated system directory'
~~~

