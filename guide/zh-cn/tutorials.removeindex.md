# 从 URL 移除 `index.php`

为了保持 URLs 的干净，你可能希望 URL 在访问的时候不包含 `/index.php/`。下面有两步可以实现:

1. 编辑 bootstrap 文件
2. 设置重写规则

# 配置 Bootstrap

首先你需要在 [Kohana::init] 方法中更改 `index_file` 设置:

    Kohana::init(array(
        'base_url'   => '/myapp/',
        'index_file' => FALSE,
    ));

现在所有使用 [URL::site]，[URL::base] 和 [HTML::anchor] 生成的 URL均不会包含 "index.php" 了。

# URL 重写

开启重写配置的方法根据服务器的不同而不同，下面仅供参考:

## Apache

改名 `example.htaccess` 为 `.htaccess` 后修改下面的参数代码:

    RewriteBase /kohana/

这里需要和 [Kohana::init] 方法中的 `base_url` 选项匹配:

    RewriteBase /myapp/

完成了，就这点事！

### 失败了!

如果提示 "Internal Server Error" 或 "No input file specified" 错误，请尝试下面的修改:

    RewriteRule ^(?:application|modules|system)\b - [F,L]

相反，我们可以尝试反斜杠:

    RewriteRule ^(application|modules|system)/ - [F,L]

如果这样还不工作，再试着修改:

    RewriteRule .* index.php/$0 [PT]

再简单点:

    RewriteRule .* index.php [PT]

### 仍然失败!

如果还是提示失败的话，请确保你的服务器支持 URL 的 `mod_rewrite`。
加入你可以修改 Apache 的配置，你可以复制下面的配置到 `httpd.conf`:

    <Directory "/var/www/html/myapp">
        Order allow,deny
        Allow from all
        AllowOverride All
    </Directory>

## NGINX

很难给出 nginx 的配置实例，但是修改其实非常简单:

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

两点需要注意的是使用 [try_files](http://wiki.nginx.org/NginxHttpCoreModule#try_files) 和 [fastcgi_split_path_info](http://wiki.nginx.org/NginxHttpFcgiModule#fastcgi_split_path_info)。

[!!] 以上配置假定你的 PHP 是在端口为 9000 的 FastCGI 服务器，同时 nginx 在 v0.731 以上版本。

如果在运行中遇到的问题，请在 nginx 中启用 debug 级别的日志记录并检查 access 和 error 日志。
