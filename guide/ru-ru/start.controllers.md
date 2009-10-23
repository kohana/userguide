# Контроллеры

Контроллеры являются промежуточным звеном между моделью и представлением. Они передают информацию в модель для изменения данных, и запрашивают информацию от модели. Например, операции работы с базой данных: вставка (insert), изменение (update) и удаление (delete) как операции редактирования данных, и выборка (select) для извлечения данных. Информацию, полученную от модели, контроллеры перенаправляют в представления, которые содержат конечный результат, предназначенный для отображения пользователям.

Контроллеры вызываются с помощью URL. За более подробной информацией обратитесь к разделу [URL и ссылки](start.urls).



## Название контроллера и его содержание

Имя класса контроллера должно соответствовать имени файла.

**Соглашения при использовании контроллеров**

* имя файла должно быть в нижнем регистре, например: `articles.php`
* файл контроллера должен располагаться в (под-)директории **classes/controller**, например: `classes/controller/articles.php`
* имя класса контроллера должно соответствовать имени файла, начинаться с заглавной буквы и должно начинаться с префикса **Controller_**, например: `Controller_Articles`
* класс контроллера должен быть потомком класса Controller.
* методы контроллера, предназначенные для вызова через URI, должны начинаться с префикса **action_** (например: `action_do_something()` )



### Пример простейшего контроллера

Создадим  простой контроллер, который будет выводить на экран 'Hello World!'.

**application/classes/controller/article.php**
~~~
<?php defined('SYSPATH') OR die('No direct access allowed.');

class Controller_Article extends Controller
{
    public function action_index()
    {
        echo 'Hello World!';
    }
}
~~~
Теперь, если открыть адрес yoursite.com/article (или yoursite.com/index.php/article, если не используется URL rewritting), то можно увидеть
~~~
Hello World
~~~
Вот он, Ваш первый контроллер. Он соответствует всем указанными правилам.



### Пример более сложного контроллера

В предыдущем примере метод `index()` вызывается посредством url yoursite.com/article. Если второй сегмент url не указан, используется метод index. Он также может быть вызван явно следующим url: yoursite.com/article/index

_Второй сегмент url определяет метод, который используется в вызываемом контроллере._

**application/classes/controller/article.php**
~~~
class Controller_Article extends Controller
{
    public function action_index()
    {
        echo 'Hello World!';
    }

    public function action_overview()
    {
        echo 'Article list goes here!';
    }
}
~~~
Теперь, если в адресной строке указать адрес yoursite.com/article/overview, то браузер выведет:
~~~
Article list goes here!
~~~


### Вызов контроллера с аргументами

Допустим, стоит задача отобразить определённую статью. Например, статья с наименованием `your-article-title` и с идентификатором `1`.

URL будет выглядить следующим образом: yoursite.com/article/view/**your-article-title/1**. Последние два сегменти URL строки передаются в метод view().

**application/classes/controller/article.php**
~~~
class Controller_Article extends Controller
{
    public function action_index()
    {
        echo 'Hello World!';
    }

    public function action_overview()
    {
        echo 'Article list goes here!';
    }

    public function action_view($title, $id)
    {
        echo $id . ' - ' . $title;
        // в идеале следует извлекать запись из базы данных
    }
}
~~~
При переходе на страницу yoursite.com/article/view/**your-article-title/1** , можно будет увидеть
~~~
1 - your-article-title
~~~
