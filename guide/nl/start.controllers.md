#Controllers

Controllers stand in between the models and the views in an application. They pass information on to the model when data needs to be changed and they request information from the model. For example database inserts, updates and deletes for data change and database selects for information retrieval. Controllers pass on the information of the model to the views, the views contain the final output for the users.

Controllers are called by a URL, see [URLs and Links](start.urls) for more information.



## Controller naming and anatomy

The name of the controller class must correspond to the filename.

**Conventions for a controller**

* controller filename must be lowercase, e.g. `articles.php`
* must reside in a **classes/controller** (sub-)directory, e.g. `classes/controller/articles.php`
* controller class must map to filename, be capitalized, and be prepended with **Controller_**, e.g. `Controller_Articles`
* must have the Controller class as (grand)parent
* controller methods must be preceded by **action_** (e.g. `action_do_something()` ) to be called by the URI mapping



### A simple controller

We start with a simple controller. It will show Hello World on the screen.

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
Now if you enter yoursite.com/article (or yoursite.com/index.php/article without URL rewritting) you should see
~~~
Hello World
~~~
That's it, your first controller. You can see all conventions are applied.



### More advanced controller

In the example above the `index()` method is called by the yoursite.com/article url. If the second segment of the url is empty, the index method is called. It would also be called by the following url: yoursite.com/article/index

_If the second segment of the url is not empty, it determines which method of the controller is called._

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
Now if you call the url yoursite.com/article/overview it will display
~~~
Article list goes here!
~~~


### Controller with arguments

Say we want to display a specific article, for example the article with the title being `your-article-title` and the id of the article is `1`.

The url would look like yoursite.com/article/view/**your-article-title/1** The last two segments of the url are passed on to the view() method.

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
When you call yoursite.com/article/view/**your-article-title/1** it will display
~~~
1 - your-article-title
~~~
