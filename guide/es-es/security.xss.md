# Seguridad  en Cross-Site Scripting (XSS)

El primer paso para prevenir los ataques de [XSS](http://es.wikipedia.org/wiki/Cross-site_scripting) es saber cuando  necesitas protegerte a ti mismo. El XSS sólo puede llevarse a cabo cuando se muestra dentro de contenido HTML, muchas veces vía una entrada de formulario o cuando mostramos datos de resultados de la base de datos. Cualquier variable global que contenga información desde el cliente puede ser contaminada. Esto incluye los datos de las variables $_GET, $_POST, y $_COOKIE.

## Prevención

Hay unas pocas reglas simples para proteger el HTML tu aplicación del XSS. La primera es usar el método [Security::xss] para limpiar cualquier dato de entrada que venga de una variable global. Si no necesitas HTML en una variable, usa [strip_tags](http://php.net/strip_tags) para remover todas las etiquetas HTML innecesarias del contenido.

[!!] Si quieres permitir a los usuarios enviar HTML en tu aplicación, es altamente recomendable usar una herramienta de limpieza de HTML como [HTML Purifier](http://htmlpurifier.org/) o [HTML Tidy](http://php.net/tidy).

La segunda es que siempre debemos escapar los datos cuando se insertan en el HTML. La clase [HTML] proporciona generadores para muchas de las principales etiquetas, incluyendo scripts, hojas de estilo, enlaces, imágenes, y email (mailto). Cualquier contenido no verificado debería escaparse usando [HTML::chars].

## Referencias

* [OWASP XSS Cheat Sheet](http://www.owasp.org/index.php/XSS_(Cross_Site_Scripting)_Prevention_Cheat_Sheet)
