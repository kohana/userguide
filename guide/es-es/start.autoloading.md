# Autocarga

Kohana aprovecha la habilidad de PHP [autocarga](http://docs.php.net/manual/es/language.oop5.autoload.php). Esto elimina la necesidad de llamar a [include](http://php.net/include) o [require](http://php.net/require) antes de usar una clase.

Las clases son cargadas usando el método [Kohana::auto_load], el cual hace una simple conversión del nombre de la clase al nombre del archivo:

1. Las clases son colocadas en el directorio `classes/` del [sistema de archivos](start.filesystem)
2. Cualquier caracter de barra baja es convertido a barra invertida
2. El nombre de archivo es todo en minúsculas

Cuando llamamos a una clase que no ha sido cargada (por ejemplo: `Session_Cookie`), Kohana buscará en el sistema de archivos usando [Kohana::find_file] un archivo llamado `classes/session/cookie.php`.

## Autocargadores personalizados

[!!] El autocargador por defecto es activado en `application/bootstrap.php`.

Los cargadores de clases adicionales pueden ser añadidos usando [spl_autoload_register](http://php.net/spl_autoload_register).
