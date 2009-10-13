# Configuración General

[!!] por hacer, descripción de los beneficios de las propiedades estáticas para la configuración

## Configuración Principal

La primera tarea de configuración de cualquier nueva instalación de Kohana es cambiar la configuración de inicio [Kohana::init] en `application/bootstrap.php`. Los datos configurables son:

`boolean` errors
:   ¿Usar el gestor de errores y excepciones interno? (Por defecto `TRUE`) Establecer a `FALSE` para desactivar el gestor de errores y excepciones de Kohana.

`boolean` profile
:   ¿Hacer análisis de rendimiento interno? (Por defecto `TRUE`) Establecer a `FALSE` para desactivarlo. En sitios en producción debería estar desactivado para un mejor rendimiento.

`boolean` caching
:   ¿Cachear la localización de los archivos entre peticiones? (Por defecto `FALSE`) Establecer a `TRUE` para cachear la
    ruta absoluta de los archivos. Esto aumenta dramáticamente la velocidad de [Kohana::find_file] y puede muchas veces
    tener un impacto dramático en el desempeño. Sólo activar en sitios en producción o para su prueba.

`string` charset
:   Juego de caracteres usado para todas las entradas y salidas. (Por defecto `"utf-8"`) Debería ser un juego de caracteres que sea soportado por [htmlspecialchars](http://php.net/htmlspecialchars) e [iconv](http://php.net/iconv).

`string` base_url
:   URL base de la aplicación. (Por defecto `"/"`) Puede ser una URL completa o parcial. Por ejemplo "http://example.com/kohana/" o sólo "/kohana/" funcionan ambas por igual.

`string` index_file
:   El archivo PHP que inicia la aplicación. (Por defecto `"index.php"`) Establecer a `FALSE` cuando elimines el archivo index con la reescritura de la URL (mod_rewrite y similares).

`string` cache_dir
:   Directorio de la Cache. (Por defecto `"application/cache"`) Debe apuntar a un directorio  **escribible**.

## Configuración de las Cookies

Hay varias propiedades estáticas en la clase [Cookie] que deberían establecerse, especialmente en sitios en producción.

`string` salt
:   Cadena que es usada para crear [cookies cifradas](security.cookies)

`integer` expiration
:   Tiempo de expiración en segundos

`string` path
:   Ruta URL para restringir dónde pueden ser accedidas las cookies

`string` domain
:   Dominio URL para restringir dónde pueden ser accedidas las cookies

`boolean` secure
:   Permitir que las cookies sólo sean accedidas por HTTPS

`boolean` httponly
:   Permitir que las cookies sólo sean accedidas por HTTP (también desactiva el acceso por Javascript)

# Archivos de Configuración

La configuración se establece en archivos PHP planos, del estilo de:

~~~
<?php defined('SYSPATH') or die('No direct script access.');

return array(
    'setting' => 'value',
    'options' => array(
        'foo' => 'bar',
    ),
);
~~~

Si el archivo de configuración anterior se llamaba `myconf.php`, puedes acceder a él usando:

~~~
$config = Kohana::config('myconf');
$options = $config['options'];
~~~

[Kohana::config] también proporciona una forma corta para acceder a valores individuales del array de configuración usando "rutas con puntos".

Obtener el array "options":

~~~
$options = Kohana::config('myconf.options');
~~~

Obtener el valor de "foo" del array "options":

~~~
$foo = Kohana::config('myconf.options.foo');
~~~

Los arrays de configuración también pueden ser accedidos como objetos, si prefieres ese método:

~~~
$options = Kohana::config('myconf')->options;
~~~
