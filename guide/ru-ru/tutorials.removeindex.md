# Удаление из URL `index.php`

Для чистоты URL, Вам наверняка захочется иметь доступ до разделов Вашего приложения без `/index.php/` в адресной строке. Для этого необходимо выполнить 2 действия.

1. Откорректировать bootstrap файл
2. Установить возможности rewriting'а на Вашем веб-сервере

# Конфигурирование Bootstrap

Первое, что следует сделать - это изменить значение `index_file` в [Kohana::init]:

    Kohana::init(array(
        'base_url'   => '/myapp/',
        'index_file' => FALSE,
    ));

Теперь все ссылки, генерируемые методами [URL::site], [URL::base], и [HTML::anchor] не будут использовать "index.php" при построении URL. Все генерируемые ссылки будут начинаться с `/myapp/` вместо `/myapp/index.php/`.

# URL Rewriting

В зависимости от Вашего сервера, rewriting активируется по разному.

## Apache

Переименуйте `example.htaccess` в `.htaccess` и измените следующую строчку кода:

    RewriteBase /kohana/

RewriteBase должен совпадать со значением, указанным у Вас в `base_url` свойстве [Kohana::init]:

    RewriteBase /myapp/

В большинстве случаев - это всё, что необходимо сделать.

### Ошибка!

Если вдруг Вы стали получать ошибки в виде "Internal Server Error" или "No input file specified", попытайтесь изменить `.htaccess` следующее:

    RewriteRule ^(?:application|modules|system)\b - [F,L]

Вместо параметра `\b` попробуйте использовать слеш:

    RewriteRule ^(application|modules|system)/ - [F,L]

Если это не поможет, попробуйте изменить следующее:

    RewriteRule .* index.php/$0 [PT]

На что-то более простое:

    RewriteRule .* index.php [PT]

### Всё равно ошибка!

Если всё ещё получаете ошибки, убедитесь, что Ваш хостинг предоставляет поддержку Apache `mod_rewrite`. Если у Вас есть доступ до изменения настроек Apache, то добавьте следующие строки в конфигурационный файл (зачастую это `httpd.conf`):

    <Directory "/var/www/html/myapp">
        Order allow,deny
        Allow from all
        AllowOverride All
    </Directory>

## NGINX

Тяжело дать пример конфигурации nginx сервера, но можно использовать следующий пример для server блока:

    location / {
        index index.php index.html index.htm;
        try_files $uri $uri/ index.php$uri?$args;
    }

    location ~ ^(.+\.php)(.*)$ {
        fastcgi_split_path_info ^(.+\.php)(.*)$;
        fastcgi_param  SCRIPT_NAME        $fastcgi_script_name;
        fastcgi_param  SCRIPT_FILENAME    $document_root/$fastcgi_script_name;
        fastcgi_param  PATH_INFO          $fastcgi_path_info;

        include fastcgi.conf;

        fastcgi_pass  127.0.0.1:9000;
        fastcgi_index index.php;
    }

Заметьте, что в данном примере используются [try_files](http://wiki.nginx.org/NginxHttpCoreModule#try_files) и [fastcgi_split_path_info](http://wiki.nginx.org/NginxHttpFcgiModule#fastcgi_split_path_info) свойства.

[!!] Этот пример подразумевает, что Вы запускаете PHP как FastCGI сервер на порту 9000 и используете nginx v0.7.31 и выше.

Если с этой конфигурацией Вы получаете ошибки, установите для nginx уровень логов в debug и проверьте access и error логи на предмет ошибок.