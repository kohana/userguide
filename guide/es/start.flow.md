# Proceso de las Peticiones

Cada aplicación sigue el siguiente proceso:

1. La aplicación empieza desde el archivo `index.php`
2. Incluye `APPPATH/bootstrap.php`
3. [Request::instance] es llamada para procesar la petición
    1. Comprueba cada ruta hasta que se encuentra una coincidencia
    2. Carga el controlador y le pasa la petición
    3. Llama al método [Controller::before]
    4. Llama a la acción del controlador
    5. Llama al método [Controller::after]
4. Muestra la respuesta a la petición ([Request])

La acción del controlador puede ser cambiada por el método [Controller::before] en base a los parámetros de la petición.

[!!] inacabado
