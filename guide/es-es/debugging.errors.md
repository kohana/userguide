# Gestión de Errores/Excepciones

Kohana proporciona un gestor de errores y excepciones que transforma errores en excepciones usando la clase [ErrorException](http://php.net/errorexception) de PHP. Se muestran muchos detalles del error y del estado interno de la aplicación:

1. Clase de excepción
2. Nivel del error
3. Mensaje de error
4. Fuente del error, con la línea del error resaltada
5. Una [depuración hacia atrás](http://php.net/debug_backtrace) del flujo de ejecución
6. Archivos incluídos, extensiones cargadas y variables globales

## Ejemplo

Haz clic en cualquiera de los enlaces para mostrar la información adicional:

<div>{{userguide/examples/error}}</div>

## Desactivando la Gestión de Errores/Excepciones

Si no quieres usar la gestión de errores interna, la puedes desactivar cuando se llama a [Kohana::init]:

~~~
Kohana::init(array('errors' => FALSE));
~~~
