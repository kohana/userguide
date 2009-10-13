# Convenciones

## Nombre de las clases y la localización del archivo

Los nombres de las clases en Kohana siguen una forma estricta para facilitar la [autocarga](start.autoloading).

Los nombres de las clases deben tener la primera letra mayúscula con barra baja para separar palabras. Las barras bajas son significativas ya que directamente reflejan la localización del archivo en el sistema de archivos.

	Clase						Archivo
	
	Controller_Template			classes/controller/template.php
	Model_User					classes/model/user.php
	Model_Auth_User				classes/model/auth/user.php
	Auth						classes/auth.php

Los nombres de las clases del estilo de PrimeraMayuscula no deberían ser usadas.

Todos los nombres de los archivos de las clases y los directorios van en minúscula.

Todas las clases deben ir en el directorio `classes`. Esto debe ser así en cualquier nivel del [sistema de archivos en cascada](start.filesystem).

Kohana 3 no diferencia entre *tipos* de clases de la misma forma en que Kohana 2.x y otros frameworks lo hacen. No hay diferencia entre una clase tipo 'helper' o una de tipo 'library' - en Kohana 3 cualquier clase puede implementar cualquier interface que necesite o ser estática totalmente (estilo helper), o instanciable, o una mezcla (por ejemplo singleton).

## Estilo de Código

Se recomienda seguir el estilo de código usado en Kohana. Usamos el [estilo BSD/Allman](http://en.wikipedia.org/wiki/Indent_style#BSD.2FAllman_style). ([Descripción más pormenorizada](http://dev.kohanaphp.com/wiki/kohana2/CodingStyle) del estilo de código preferido por Kohana)
