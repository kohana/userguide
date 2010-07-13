# Установка

1. Загрузите последний **стабильный** релиз с [сайта Kohana](http://kohanaframework.org/)
2. Распакуйте загруженный архив (появится директория `kohana`)
3. Загрузите содержание архива на Ваш web-сервер
4. Откройте файл `application/bootstrap.php` и произведите следующие изменения:
	- Установите [часовой пояс](http://php.net/timezones) (timezone), который будет использоваться по-умолчанию в Вашем приложении
	- Установите `base_url` в параметрах вызова [Kohana::init], чтобы обозначить расположение фреймворка на Вашем сервере
6. Убедитесь в том, что папки `application/cache` и `application/logs` доступны для записи (для *nix ОС воспользуйтесь командой `chmod application/{cache,logs} 0777`)
7. Проверьте правильность установки, открыв URL, указанный Вами ранее как `base_url` в Вашем любимом браузере

[!!] В зависимости от платформы, установленные поддиректории могут потерять значения прав доступа из-за особенностей процесса zip распаковки. Чтобы выправить права доступа, измените права на 755, выполнив в командной строке `find . -type d -exec chmod 0755 {} \;` из корневой директории Kohana.

Вы увидите страницу установки. Если будут отображены какие-либо ошибки, необходимо их устранить перед тем, как продолжать работать.

![Страница установки](img/install.png "Пример страницы установки")

После того, как Вы убедитесь, что все сконфигурировано правильно, переименуйте или удалите файл `install.php`. После этого Вы увидите приветственную страницу Kohana:

![Страница приветствия](img/welcome.png "Example of welcome page")

## Настройка продуктив-окружения

Имеется несколько вещеё, которые Вы наверняка захотите сделать перед публикацией Вашего приложения.

1. Прочитайте описание процесса [настройки](about.configuration) этой документации. Оно охватывает большинство глобальных настроек, которые требуют изменения при смене окружения. Основное правило для сайтов в продуктиве - это активация кэширования и отключение профилирования (свойства [Kohana::init]). [Кэширование маршрутов](api/Route#cache) так же может быть полезным при наличии большого числа маршрутов.
2. Обрабатывайте все исключения в `application/bootstrap.php` таким образом, чтобы не было утечки конфиденциальной информации при попытках трассировки запросов. Изучите нижеизложенный пример, который был взят из [исходных кодов сайта wingsc.com](http://github.com/shadowhand/wingsc), написанного Shadowhand'ом.
3. Включите APC или любой другой вид кэширования кода. Это единственный и самый простой способ увеличения производительности, который можно применить к самому PHP. Чем сложнее и больше Ваше приложение, тем больше выгода от использования кэширования кода.

		/**
		 * Set the environment string by the domain (defaults to Kohana::DEVELOPMENT).
		 */
		Kohana::$environment = ($_SERVER['SERVER_NAME'] !== 'localhost') ? Kohana::PRODUCTION : Kohana::DEVELOPMENT;
		/**
		 * Initialise Kohana based on environment
		 */
		Kohana::init(array(
			'base_url'   => '/',
			'index_file' => FALSE,
			'profile'    => Kohana::$environment !== Kohana::PRODUCTION,
			'caching'    => Kohana::$environment === Kohana::PRODUCTION,
		));

		/**
		 * Execute the main request using PATH_INFO. If no URI source is specified,
		 * the URI will be automatically detected.
		 */
		$request = Request::instance($_SERVER['PATH_INFO']);

		try
		{
			// Attempt to execute the response
			$request->execute();
		}
		catch (Exception $e)
		{
			if (Kohana::$environment === Kohana::DEVELOPMENT)
			{
				// Just re-throw the exception
				throw $e;
			}

			// Log the error
			Kohana::$log->add(Kohana::ERROR, Kohana::exception_text($e));

			// Create a 404 response
			$request->status = 404;
			$request->response = View::factory('template')
			  ->set('title', '404')
			  ->set('content', View::factory('errors/404'));
		}

		if ($request->send_headers()->response)
		{
			// Get the total memory and execution time
			$total = array(
			  '{memory_usage}' => number_format((memory_get_peak_usage() - KOHANA_START_MEMORY) / 1024, 2).'KB',
			  '{execution_time}' => number_format(microtime(TRUE) - KOHANA_START_TIME, 5).' seconds');

			// Insert the totals into the response
			$request->response = str_replace(array_keys($total), $total, $request->response);
		}


		/**
		 * Display the request response.
		 */
		echo $request->response;

