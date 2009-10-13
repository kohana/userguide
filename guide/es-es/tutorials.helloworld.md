# Hola, Mundo

Muchos frameworks proporcionan algún ejemplo de tipo hola mundo, de forma que ¡sería muy grosero por nuestra parte romper esa tradición!

Empezaremos creando un hola mundo muy básico, y luego lo ampliaremos para seguir con los principios del patrón MVC.

## Lo esencial

En primer lugar, tenemos que crear un controlador que kohana usará para manejar la petición

Crea el archivo `application/classes/controller/hello.php` en tu directorio **application** y copia dentro el siguiente código:

    <?php defined('SYSPATH') OR die('No Direct Script Access');

	Class Controller_Hello extends Controller
	{
		function action_index()
		{
			echo 'hello, world!';
		}
	}

Veamos lo que ocurre:

`<?php defined('SYSPATH') OR die('No Direct Script Access');`
:	Deberías reconocer la primera etiqueta como la etiqueta de apertura de php (en caso contrario probablemente deberías [aprender php](http://php.net)).  Lo que sigue es una pequeña comprobación que se asegura que este archivo está siendo incluído por kohana.  Esto impide que se acceda directamente a este archivo desde la url.

`Class Controller_Hello extends Controller`
:	Esta línea declara nuestro controlador, cada clase de tipo controlador tiene que llevar el prefijo `Controller_` y una barra baja delimita la ruta a la carpeta donde se encuentra el controlador (mirar [Convenciones y Estilos](start.conventions) para más información).  Cada controlador debe también extender la clase básica `Controller` la cual proporciona una estrcutura estándar para los controladores.


`function action_index()`
:	Define la acción "index" de nuestro controlador. Kohana intentará llamar a esta acción si el usuario no ha especificado una. (mirar [Rutas, URLs y Enlaces](tutorials.urls))

`echo 'hello, world!';`
:	¡Y esta es la línea que muestra la famosa frase!

Ahora si abres el navegador y vas a http://your/kohana/website/index.php/hello podrás ver algo como esto:

![Hello, World!](img/hello_world_1.png "Hello, World!")

## Eso ha sido bueno, pero podemos hacerlo mejor

Lo que hicimos en la sección anterior fue un buen ejemplo de lo fácil que es crear una aplicación *extremadamente* básica en kohana (de hecho, ¡es tan básica que no se debe hacer de nuevo!)

Si alguna vez has oído hablar del patrón MVC probablemente te habrás dado cuenta que imprimir contenido al navegador con `echo` en un controlador va totalmente en contra de los principios de este patrón de diseño.

La forma correcta de hacerlo con un framework MVC es usar _vistas_ para manejar la presentación de tu aplicación, y dejar hacer al controlador lo que mejor hace - ¡Controlar el flujo de la petición!

Cambiamos ligeramente el controlador original -

    <?php defined('SYSPATH') OR die('No Direct Script Access');

	Class Controller_Hello extends Controller_Template
	{
		public $template = 'site';

		function action_index()
		{
			$this->template->message = 'hello, world!';
		}
	}

`extends Controller_Template`
:	Ahora estamos extendiendo el controlador de plantillas,  el cual es más conveniente para usar vistas en nuestro controlador.

`public $template = 'site';`
:	El controlador de plantillas necesita conocer que plantilla queremos usar. Automáticamente cargará la vista definida en esta variable y le asignará el objeto de tipo vista.

`$this->template->message = 'hello, world!';`
:	`$this->template` es una referencia al objeto tipo vista de nuestra plantilla del sitio.  Lo que estamos haciendo es asignar a una variable de la vista llamada "message" el valor de "hello, world!"

Ahora intentamos ejecutar nuestro código...

<div>{{userguide/examples/hello_world_error}}</div>

Por alguna razón kohana lanza una excepción y no muestra nuestro sorprendente mensaje.

Si miramos dentro del mensaje de error podemos ver que la librería View no es capaz de encontrar la plantilla de nuestro sitio, probablemente porque no ha sido creada todavía - ¡*ouch*!

Vamos y creamos el archivo de vista `application/views/site.php` para nuestro mensaje -

	<html>
		<head>
			<title>We've got a message for you!</title>
			<style type="text/css">
				body {font-family: Georgia;}
				h1 {font-style: italic;}

			</style>
		</head>
		<body>
			<h1><?php echo $message; ?></h1>
			<p>We just wanted to say it! :)</p>
		</body>
	</html>

Si luego refrescamos la página podremos ver el fruto de nuestra labor - 

![hello, world! We just wanted to say it!](img/hello_world_2.png "hello, world! We just wanted to say it!")

## En resumen

En este tutorial has aprendido cómo crear un controlador y usar una vista para separar la lógica de la presentación.

Esto es obviamente una introducción muy básica al trabajo con kohana y no entra de lleno en el potencial que tienes cuando desarrollas aplicaciones con él.
