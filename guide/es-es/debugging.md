# Depuración

Kohana incluye varias herramientas útiles que te ayudarán en la depuración de tus aplicaciones.

La más básica de ellas es [Kohana::debug]. Este método simple mostrará cualquier número de variables, similar a [var_export] o [print_r], pero usando HTML para una mejor visualización.

~~~
// Mostrar el contenido de las variables $foo y $bar 
echo Kohana::debug($foo, $bar);
~~~

Kohana también proporciona un método para mostrar el código fuente de una línea particular usando [Kohana::debug_source].

~~~
// Mostrar esta línea del código
echo Kohana::debug_source(__FILE__, __LINE__);
~~~

Si quieres mostrar información sobre los archivos de tu aplicación sin exponer el directorio de instalación, puedes usar [Kohana::debug_path]:

~~~
// Mostrar "APPPATH/cache" en vez de la ruta real
echo Kohana::debug_file(APPPATH.'cache');
~~~
