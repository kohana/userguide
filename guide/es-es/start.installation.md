# Instalación

1. Descarga la última versión **estable** de la [web de Kohana](http://kohanaphp.com/)
2. Descomprime el archivo descargado para crear un directorio `kohana`
3. Sube el contenido de esta carpeta a tu servidor
4. Abre `application/bootstrap.php` y haz los cambios siguientes:
	- Establece la [zona horaria](http://php.net/timezones) por defecto para tu aplicación
	- Establece `base_url` en la llamada a [Kohana::init] para reflejar la localización de la carpeta de kohana en tu servidor
6. Comprueba que los directorios `application/cache` y `application/logs` tienen permisos de escritura para todos con `chmod application/{cache,logs} 0777`
7. Comprueba tu instalación abriendo la url que has establecido en `base_url` en tu navegador favorito

[!!] Dependiendo de tu plataforma, los subdirectorios de la instalación podrían haber perdido sus permisos debido a la descompresión zip. Para cambiarle los permisos a todos ejecutar `find . -type d -exec chmod 0755 {} \;` desde la raíz de la instalación de Kohana.

Deberías ver la página de instalación. Si reporta algún error, debes corregirlo antes de continuar.

![Install Page](img/install.png "Ejemplo de la página de instalación")

Una vez que la página de instalación reporta que tu entorno está correcto, debes renombrar o borrar el archivo `install.php` del directorio raíz. Entonces deberías ver la página de bienvenida de Kohana (el texto `hello, world!`).

