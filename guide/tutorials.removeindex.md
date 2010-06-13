# Removing `index.php` From the URL

To keep your URLs clean, you will probably want to be able to access your app without having `/index.php/` in the URL. There are two steps to remove `index.php` from the URL.

1. Edit the bootstrap file
2. Set up rewriting

# Configure Bootstrap

The first thing you will need to change is the `index_file` setting of [Kohana::init]:

    Kohana::init(array(
        'base_url'   => '/myapp/',
        'index_file' => FALSE,
    ));

Now all of the links generated using [URL::site], [URL::base], and [HTML::anchor] will no longer include "index.php" in the URL. All generated links will start with `/myapp/` instead of `/myapp/index.php/`.

# URL Rewriting

Enabling rewriting is done differently, depending on your web server.

## Apache

Rename `example.htaccess` to only `.htaccess` and alter the following line of code:

    RewriteBase /kohana/

This needs to match the `base_url` setting of [Kohana::init]:

    RewriteBase /myapp/

In most cases, this is all you will need to change.

### Failed!

If you get a "Internal Server Error" or "No input file specified" error, try changing:

    RewriteRule ^(?:application|modules|system)\b - [F,L]

Instead, we can try a slash:

    RewriteRule ^(application|modules|system)/ - [F,L]

If that doesn't work, try changing:

    RewriteRule .* index.php/$0 [PT]

To something more simple:

    RewriteRule .* index.php [PT]

### Still Failed!

If you are still getting errors, check to make sure that your host supports URL `mod_rewrite`. If you can change the Apache configuration, add these lines to the the configuration, usually `httpd.conf`:

    <Directory "/var/www/html/myapp">
        Order allow,deny
        Allow from all
        AllowOverride All
    </Directory>

## NGINX

It is hard to give examples of nginx configuration, but here is a sample for a server:

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

The two things to note are the use of [try_files](http://wiki.nginx.org/NginxHttpCoreModule#try_files) and [fastcgi_split_path_info](http://wiki.nginx.org/NginxHttpFcgiModule#fastcgi_split_path_info).

[!!] This assumes that you are running PHP as a FastCGI server on port 9000 and are using nginx v0.7.31 or later.

If you are having issues getting this working, enable debug level logging in nginx and check the access and error logs.
