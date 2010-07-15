# Работа с Git

Kohana применяет [git](http://git-scm.com/) для контроля версий и [github](http://github.com/kohana) для совместной разработки. Данная статья покажет Вам как использовать git и github для создания простейшего приложения.

## Установка и настройка Git на Вашей машине

### Установка Git

- для OSX: [Git-OSX](http://code.google.com/p/git-osx-installer/)
- для Windows: [Msygit](http://code.google.com/p/msysgit/)
- Или загрузите git с [официального сайта](http://git-scm.com/) и установите его самостоятельно (подробности установки смотрите на сайт Git)

### Основные глобальные настройки

    git config --global user.name "Your Name"
    git config --global user.email "youremail@website.com"

### Дополнительные, но предпочтимые настройки

Для лучшей визуализации команд и репозиторий в командной строке, используйте следующее:

    git config --global color.diff auto
    git config --global color.status auto
    git config --global color.branch auto

### Настройка автозавершения

[!!] Следующие строки применимы только для OSX машин

Эти строки сделают всю грязную работу за вас и после этого Вы сможете спокойно работать с git-окружением, используя автозавершение команд:

	cd /tmp
	git clone git://git.kernel.org/pub/scm/git/git.git
	cd git
	git checkout v`git --version | awk '{print $3}'`
	cp contrib/completion/git-completion.bash ~/.git-completion.bash
	cd ~
	rm -rf /tmp/git
	echo -e "source ~/.git-completion.bash" >> .profile

### Всегда используйте LF в окончаниях строк

Это соглашение, которое было принято Kohana сообществом. Выставьте эту настройку во имя Вашего Господа, особенно если хотите участвовать в kohana коммьюнити!

    git config --global core.autocrlf input
    git config --global core.savecrlf true

[!!] Более подробную информацию об окончаниях строк читайте на [GitHub'е](http://help.github.com/dealing-with-lineendings/)

### Информация для размышления

- [Git скринкасты](http://www.gitcasts.com/)
- [Git справочник](http://gitref.org/)
- [Pro Git book](http://progit.org/book/)

## Основная структура

[!!] Предполагается, что Ваш web-сервер уже настроен, и Вы будете использовать адрес <http://localhost/gitorial/> для нового приложения.

Откройте консоль, перейдите в пустую директорию `gitorial` и выполните команду `git init`. Она создаст заготовку под новый git-репозиторий.

Далее, мы создадим подпроект ([submodule](http://www.kernel.org/pub/software/scm/git/docs/git-submodule.html)) для директории `system`. Откройте <http://github.com/kohana/core> и скопируйте значение Clone URL:

![Github Clone URL](http://img.skitch.com/20091019-rud5mmqbf776jwua6hx9nm1n.png)

Используйте скопированный URL для создания подпроекта `system`:

	git submodule add git://github.com/kohana/core.git system

[!!] Будет создана связь с текущей разрабатываемой версией следующего стабильного релиза. Эта версия должна практически всегда быть безопасна для использования, иметь тот же API, что в текущем стабильном релизе с исправленными ошибками.

Теперь добавьте остальные необходимые подпроекты. Например, если нужен модуль [Database](http://github.com/kohana/database):

	git submodule add git://github.com/kohana/database.git modules/database

После добавления модули должны быть проиниализированы:

	git submodule init

Теперь мы должны зафиксировать текущее состояние:

	git commit -m 'Added initial submodules'

Следующий шаг - создание структуры папок для приложения. Вот необходимый минимум:

	mkdir -p application/classes/{controller,model}
	mkdir -p application/{config,views}
	mkdir -m 0777 -p application/{cache,logs}

Если запустить команду `find application`, Вы должны увидеть такой список:

	application
	application/cache
	application/config
	application/classes
	application/classes/controller
	application/classes/model
	application/logs
	application/views

Мы не хотим, чтобы git обрабатывал логи или файлы кэша, поэтому добавим файл `.gitignore` в соответствуюшие директории logs и cache. Теперь все нескрытые (non-hidden) файлы будут проигнорированы git'ом:

	echo '[^.]*' > application/{logs,cache}/.gitignore

[!!] Git пропускает пустые папки, так что добавляя файл `.gitignore`, мы дополнительно заставляем git учитывать данную директорию, но не файлы внутри нее.

Теперь загружаем файлы `index.php` и `bootstrap.php`:

	wget http://github.com/kohana/kohana/raw/master/index.php
	wget http://github.com/kohana/kohana/raw/master/application/bootstrap.php -O application/bootstrap.php

Фиксируем эти изменения:

	git add application
	git commit -m 'Added initial directory structure'

Это все необходимые изменения. Теперь у Вас имеется приложение, использующее Git для контроля версий.

## Обновление подмодулей

Скорее всего, на определенном этапе Вы захотите обновить свои подпроекты. Чтобы обновить все модули до последних версий `HEAD`, введите:

	git submodule foreach 'git checkout master && git pull origin master'

Для синхронизации подпроекта `system` выполните:

	cd system
	git checkout master
	git pull
	cd ..
	git add system
	git commit -m 'Updated system directory'

Обновление отдельного модуля до определенной ревизии:

	cd modules/database
	git fetch
	git checkout fbfdea919028b951c23c3d99d2bc1f5bbeda0c0b
	cd ../..
	git add database
	git commit -m 'Updated database module'

Заметьте, что можно так же загрузить коммит по официальной метке релиза. Например:

    git checkout 3.0.7

Для того, чтобы увидеть все метки, просто запустите `git tag` без дополнительных аргументов.

Вот и всё!