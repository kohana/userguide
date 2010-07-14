# `index.php` verwijderen uit de URL

Om uw URLs proper te houden, wil je hoogtswaarschijnlijk je applicatie kunnen benaderen zonder /index.php/` in uw URL te gebruiken. Er zijn twee stappen om `index.php` te verwijderen uit de URL.

1. Het bootstrap bestand aanpassen
2. Herschrijven van URL's instellen

# Configuratie van de Bootstrap

Het eerste dat je moet veranderen is de `index_file` instelling van [Kohana::init]:

    Kohana::init(array(
        'base_url'   => '/myapp/',
        'index_file' => FALSE,
    ));

Nu zullen alle links die gegeneerd worden met [URL::site], [URL::base] en [HTML::anchor] niet meer "index.php" gebruiken in de URL. Alle gegenereerde links zullen starten met `/myapp/` in plaats van `/myapp/index.php/`.

# URL Herschrijven

Het herschrijven van URL kan verschillen, naargelang je web server.

## Apache

Hernoem `example.htaccess` naar `.htaccess` en verander de volgende regel code:

    RewriteBase /kohana/

Dit moet gelijk zijn met de `base_url` instelling van [Kohana::init]:

    RewriteBase /myapp/

In de meeste gevallen is dit het enige dat je moet veranderen.

### Er loopt iets fout!

Als je een "Internal Server Error" of "No input file specified" error krijgt, probeer dan hetvolgende te veranderen:

    RewriteRule ^(?:application|modules|system)\b - [F,L]

Door enkel een slash te gebruiken:

    RewriteRule ^(application|modules|system)/ - [F,L]

Als het nog steeds niet werkt, probeer dan hetvolgende te veranderen:

    RewriteRule .* index.php/$0 [PT]

Naar iets simpeler:

    RewriteRule .* index.php [PT]

### Nog steeds niet loopt het fout!

Als je nog steeds fouten krijgt, controleer dan zeker dat je host wel URL `mod_rewrite` ondersteund. Als je de Apache configuratie kunt aanpassen, voeg dan deze lijnen toe aan de configuratie, meestal in `httpd.conf`:

    <Directory "/var/www/html/myapp">
        Order allow,deny
        Allow from all
        AllowOverride All
    </Directory>

## NGINX

Het is moeilijk om voorbeelden te geven van een nginx configuratie, maar hier is een voorbeeld voor een server:

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

Er zijn twee dingen te melden: het gebruik van [try_files](http://wiki.nginx.org/NginxHttpCoreModule#try_files) en [fastcgi_split_path_info](http://wiki.nginx.org/NginxHttpFcgiModule#fastcgi_split_path_info).

[!!] Dit in de veronderstelling dat je PHP draait als een FastCGI server op poort 9000 en dat je nginx v0.7.31 of later gebruikt.

Als je problemen hebt om dit te laten werken, zet dan het deub level logging aan in nginx en controleer de toegangs- en foutenlogs.
