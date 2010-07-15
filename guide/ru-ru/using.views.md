# Использование представлений

Представления - файлы, содержащие отображаемую информацию Вашего приложения. Чаще всего это HTML, CSS и Javascript, но может быть чем угодно, например XML или JSON для AJAX-вызовов. Цель представлений - хранить эту информацию отдельно от логики приложения для облегчения повторного использования и более чистого кода.

Несмотря на это, представления сами по себе могут содержать код, используемый для отображения сохраненных в них данных. Например, циклический перебор элементов массива данных о продукте и отображение каждого в отдельном табличном ряду. Представления есть PHP-файлы, так что Вы можете свободно использовать там любой код, как обычно.

# Создание файлов представлений

Файлы представлений располагаются в директории `views` [файловой системы](about.filesystem). Вы также можете создавать поддиректории в ней для упорядочивания файлов. Все приведенные ниже примеры файлов являются допустимыми:

    APPPATH/views/home.php
    APPPATH/views/pages/about.php
    APPPATH/views/products/details.php
    MODPATH/error/views/errors/404.php
    MODPATH/common/views/template.php

## Загрузка представлений

Объект [View] обычно создается в контроллере ([Controller]) с помощью метода [View::factory]. Чаще всего представление записывается в свойство [Request::$response] или в другое представление.

    public function action_about()
    {
        $this->request->response = View::factory('pages/about');
    }

Когда представление сохранено в [Request::$response], как в примере выше, оно будет автоматически отображено при необходимости. Для получения сгенерированного вывода представления используйте метод [View::render] или просто преобразуйте в строку. Когда представление генерируется, файл представления загружается, и формируется HTML.

    public function action_index()
    {
        $view = View::factory('pages/about');

        // Render the view
        $about_page = $view->render();

        // Or just type cast it to a string
        $about_page = (string) $view;

        $this->request->response = $about_page;
    }

## Переменные в представлениях

Как только представление было загружено, к нему могут быть присоединены переменные, методами [View::set] и [View::bind].

    public function action_roadtrip()
    {
        $view = View::factory('user/roadtrip')
            ->set('places', array('Rome', 'Paris', 'London', 'New York', 'Tokyo'));
            ->bind('user', $this->user);

        // The view will have $places and $user variables
        $this->request->response = $view;
    }

[!!] Единственная разница между `set()` и `bind()` в том, что `bind()` присоединяет переменную по ссылке. Если Вы вызываете `bind()` переменной до того, как она определена, переменная будет создана со значением `NULL`.

### Глобальные переменные

Приложение может иметь несколько представлений, которым нужен доступ к одним и тем же переменным. Например, чтобы отобразить заголовок страницы и в шапке представления, и в теле содержимого страницы. Вы можете создать переменные, которые будут доступны в любом представлении, используя [View::set_global] и [View::bind_global].

    // Assign $page_title to all views
    View::bind_global('page_title', $page_title);

Пусть приложение имеет три представления, которые генерируют главную страницу:  `template`, `template/sidebar`, и `pages/home`. Сперва, напишем абстрактный контроллер для создания шаблона:

    abstract class Controller_Website extends Controller_Template {

        public $page_title;

        public function before()
        {
            parent::before();

            // Make $page_title available to all views
            View::bind_global('page_title', $this->page_title);

            // Load $sidebar into the template as a view
            $this->template->sidebar = View::factory('template/sidebar');
        }

    }

Далее, главный контроллер будет расширять `Controller_Website`:

    class Controller_Home extends Controller_Website {

        public function action_index()
        {
            $this->page_title = 'Home';

            $this->template->content = View::factory('pages/home');
        }

    }

## Представления внутри представлений

Если Вы хотите вложить одно представление в другое, есть два варианта. Используя [View::factory], Вы можете его заключить в текущем представлении. Это означает, что Вы должны будут передать в него все необходимые переменные методами [View::set] и [View::bind]:

    // Only the $user variable will be available in "views/user/login.php"
    <?php echo View::factory('user/login')->bind('user', $user) ?>

Другой способ - подключение представлений напрямую, что делает все текущие переменные доступными в подключаемом представлении:

    // Any variable defined in this view will be included in "views/message.php"
    <?php include Kohana::find_file('views', 'user/login') ?>

Естественно, Вы также можете загрузить  объект [Request] в представление:

    <?php echo Request::factory('user/login')->execute() ?>

Это пример [HMVC](about.mvc), который делает возможным создавать и считывать вызовы других URL Вашего приложения.

# Переход с версии 2.x

В отличие от версии Kohana 2.x, представления не создаются в контексте текущего контроллера, так что Вы не сможете использовать `$this` в качестве контроллера, в который загружено данное представление. Контроллер должен быть передан туда явно:

    $view->bind('controller', $this);
