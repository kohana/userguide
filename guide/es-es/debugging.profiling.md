# Análisis de Rendimiento

Kohana proporciona una forma muy simple de mostrar estadísticas sobre tu aplicación:

1. Los métodos de [Kohana] más usados
2. Peticiones
3. Consultas a la Base de Datos ([Database])
4. Tiempo de ejecución media de tu aplicación

## Ejemplo

En cualquier momento puedes mostrar o recolectar las estadísticas actuales del analizador ([profiler]):

~~~
<div id="kohana-profiler">
<?php echo View::factory('profiler/stats') ?>
</div>
~~~

## Vista previa

{{profiler/stats}}
