# Sistema de Archivos en Cascada

El sistema de archivos de Kohana se compone de una única estructura de directorios que es repetida a lo largo de todos los directorios, lo que llamamos la ruta de inclusión, que sigue el orden:

1. application
2. modules, según el orden en que sean añadidos
3. system

Los archivos que se encuentran en directorios superiores en la lista de las rutas de inclusión tienen preferencia sobre los archivos del mismo nombre pero que están más abajo, lo cual hace posible sobrecargar cualquier archivo colocando otro archivo con el mismo nombre en un directorio superior:

![Cascading Filesystem Infographic](img/cascading_filesystem.png)

Si tiene un archivo de Vista llamado layout.php en los directorios application/views y system/views, será devuelto el que se encuentra bajo application cuando se busque por layout.php ya que se encuentra más arriba en la lista de inclusión ordenada. Si elimina ese archivo de application/views, el que se encuentra en system/views será devuelto cuando lo busquemos.
