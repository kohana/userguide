# Контроллеры

Контроллеры являются промежуточным звеном между моделью и представлением. Они передают информацию в модель, если требуется её изменение и запрашивают информацию от модели. Например, операции работы с базой данных: вставка, изменение, удаление и заброс информации. Информацию, полученную от модели, контроллеры перенаправляют в представления, которые содержат конечный вывод, предназначенный для отображения пользователям.

Контроллеры вызываются посредствам URL. Для детальной информации, обратитесь к разделу [URL и ссылки](start.urls).



## Именование контроллера и его анатомия

Имя класса контроллера должно соответствовать имени файла контроллера.

**Допущения в организации контроллера**

* имя файла контроллерв должно быть в нижнем регистре, например: `articles.php`
* файл контроллера должен располагаться в (под-)директории **classes/controller**, например: `classes/controller/articles.php`
* имя класса контроллера должно соответствовать имени файла, начинаться с заглавной буквы и должно начинаться с префикса **Controller_**, например: `Controller_Articles`
* класс контроллера должен быть унаследован от класса Controller.
* методы контроллера, которые вызываются через URI, должны начинаться с префикса **action_** (например: `action_do_something()` )



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
Теперь, если зайти на страницу yoursite.com/article (или yoursite.com/index.php/article, если не используется URL rewritting), то можно увидеть
~~~
Hello World
~~~
На этом простом примере можно увидеть применение все допущения в организации контроллера.



### Пример более сложного контроллера

В предыдущем примере метод `index()` вызывается посредствам url yoursite.com/article. Метод index вызывается в отсутствие второго сегмента url. Он так же может быть вызван следующим url: yoursite.com/article/index

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
Теперь, если в адресной строке указать url yoursite.com/article/overview, то браузер покажет следующее:
~~~
Article list goes here!
~~~


### Вызов контроллера с аргументами

Допустим, стоит задача отобразить определённую статью. Например, статья с наименованием `your-article-title` и с id `1`.

URL быдет выглядить следющим образом: yoursite.com/article/view/**your-article-title/1**. Последние два сегменти URL строки передаются в метод view().

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
        // you'd retrieve the article from the database here normally
    }
}
~~~
При переходе на страницу yoursite.com/article/view/**your-article-title/1** , можно будет увидеть
~~~
1 - your-article-title
~~~
