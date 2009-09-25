# Proceso de las Peticiones

Cada aplicación sigue el siguiente proceso:

1. La aplicación empieza desde el archivo `index.php`
2. Incluye `APPPATH/bootstrap.php`
3. bootstrap.php llama a [Kohana::modules] con la lista de módulos usados
	1. Genera una matriz con las rutas para el sistema de archivos en cascada
	2. Comprueba cada módulo para ver si tiene un init.php, y si lo tiene, lo carga
		* Cada init.php puede definir una serie de rutas a usar, que son cargadas cuando el archivo init.php es incluido
4. [Request::instance] es llamada para procesar la petición
    1. Comprueba cada ruta hasta que se encuentra una coincidencia
    2. Carga el controlador y le pasa la petición
    3. Llama al método [Controller::before]
    4. Llama a la acción del controlador
    5. Llama al método [Controller::after]
5. Muestra la respuesta a la petición ([Request])

La acción del controlador puede ser cambiada por el método [Controller::before] en base a los parámetros de la petición.

[!!] inacabado
